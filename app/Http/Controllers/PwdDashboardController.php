<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\SkillTraining;
use App\Models\Announcement;
use App\Models\JobApplication;
use App\Models\TrainingEnrollment;
use App\Models\Document;
use App\Models\DisabilityType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PwdDashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security check - this should work with your User model
        if (!$user->isPwd()) {
            Log::error('SECURITY: Non-PWD user accessed PWD dashboard!', [
                'user_id' => $user->id,
                'role' => $user->role
            ]);
            abort(403, 'Access denied.');
        }

        Log::info('Loading PWD dashboard data', ['user_id' => $user->id]);

        // Collect filters from query string with proper validation
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'employment_type' => ['nullable', 'string'],
            'remote' => ['nullable', 'in:1,0'],
            'disability_type_id' => ['nullable', 'integer', 'exists:disability_types,id'],
            'posted_within_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'accommodations' => ['nullable', 'in:1,0'],
            'sort_by' => ['nullable', 'in:latest,urgent']
        ]);

        // Debug employment types in database
        $this->debugEmploymentTypes();

        // Get recommended jobs based on filters or user profile
        $jobPostings = $this->getRecommendedJobsWithFilters($user, $filters);

        // Load user relationships safely
        $user->load([
            'jobApplications.jobPosting.employer',
            'trainingEnrollments.skillTraining',
            'documents',
            'pwdProfile',
            'notifications' => function($query) {
                $query->latest()->take(10);
            }
        ]);

        // Get user's job application IDs for quick reference
        $userJobApplications = $user->jobApplications->pluck('job_posting_id')->toArray();

        // Application statistics
        $applicationCount = $user->jobApplications->count();
        $applicationStats = [
            'total' => $applicationCount,
            'pending' => $user->jobApplications->where('status', 'pending')->count(),
            'approved' => $user->jobApplications->where('status', 'approved')->count(),
            'rejected' => $user->jobApplications->where('status', 'rejected')->count(),
            'hired' => $user->jobApplications->where('status', 'hired')->count(),
        ];

        // Training enrollments count and stats
        $enrollmentCount = $user->trainingEnrollments->count();
        $trainingStats = [
            'total' => $enrollmentCount,
            'pending' => $user->trainingEnrollments->where('status', 'pending')->count(),
            'confirmed' => $user->trainingEnrollments->where('status', 'confirmed')->count(),
            'completed' => $user->trainingEnrollments->where('status', 'completed')->count(),
            'cancelled' => $user->trainingEnrollments->where('status', 'cancelled')->count(),
        ];

        // Document count
        $documentCount = $user->documents->count();

        // Use the User model's method for profile completion
        $profileCompletion = $user->getProfileCompletionPercentage();

        // Get additional data for enhanced dashboard
        $recentApplications = $this->getRecentApplications($user);
        $upcomingTrainings = $this->getUpcomingTrainings($user);
        $quickActions = $this->getQuickActions($user);
        $dashboardAlerts = $this->getDashboardAlerts($user);

        // Disability types list for filter controls
        $disabilityTypes = DisabilityType::orderBy('type')->get();

        // Get unique employment types for filter - organized and cleaned
        $employmentTypes = $this->getOrganizedEmploymentTypes();

        return view('dashboard.pwd', compact(
            'user',
            'jobPostings',
            'filters',
            'disabilityTypes',
            'employmentTypes',
            'userJobApplications',
            'applicationCount',
            'applicationStats',
            'enrollmentCount',
            'trainingStats',
            'documentCount',
            'profileCompletion',
            'recentApplications',
            'upcomingTrainings',
            'quickActions',
            'dashboardAlerts'
        ));
    }

    /**
     * Debug method to check employment types in database
     */
    private function debugEmploymentTypes()
    {
        try {
            $allJobs = JobPosting::select('id', 'title', 'employment_type', 'is_active')
                ->where('is_active', true)
                ->where('application_deadline', '>=', now())
                ->get();

            $employmentTypeCounts = $allJobs->groupBy('employment_type')->map->count();

            Log::info('Employment Types Debug:', [
                'total_active_jobs' => $allJobs->count(),
                'employment_types_found' => $employmentTypeCounts->toArray(),
                'sample_jobs' => $allJobs->take(3)->map(function($job) {
                    return [
                        'id' => $job->id,
                        'title' => $job->title,
                        'employment_type' => $job->employment_type,
                        'is_active' => $job->is_active
                    ];
                })->toArray()
            ]);

            return $employmentTypeCounts;
        } catch (\Exception $e) {
            Log::error('Error debugging employment types: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get organized employment types with proper formatting and validation
     */
    private function getOrganizedEmploymentTypes()
    {
        try {
            // Get all distinct employment types from active jobs
            $types = JobPosting::where('is_active', true)
                ->where('application_deadline', '>=', now())
                ->select('employment_type')
                ->distinct()
                ->get()
                ->pluck('employment_type')
                ->filter(function($type) {
                    return !empty(trim($type));
                })
                ->map(function($type) {
                    // Clean and standardize the employment type
                    $cleaned = trim($type);
                    // Convert to proper case for consistency
                    return Str::title($cleaned);
                })
                ->unique()
                ->sort()
                ->values();

            Log::info('Employment types from database:', [
                'count' => $types->count(),
                'types' => $types->toArray()
            ]);

            // If no types found, check all jobs (including inactive) for debugging
            if ($types->isEmpty()) {
                Log::warning('No employment types found in active jobs, checking all jobs');
                $allTypes = JobPosting::select('employment_type')
                    ->distinct()
                    ->get()
                    ->pluck('employment_type')
                    ->filter(function($type) {
                        return !empty(trim($type));
                    })
                    ->map(function($type) {
                        return Str::title(trim($type));
                    })
                    ->unique()
                    ->sort()
                    ->values();

                Log::info('All employment types in database:', [
                    'count' => $allTypes->count(),
                    'types' => $allTypes->toArray()
                ]);

                $types = $allTypes;
            }

            // Standardize common employment types
            $standardTypes = collect([
                'Full-time', 'Part-time', 'Contract', 'Temporary', 'Internship', 'Freelance', 'Remote'
            ]);

            // Merge found types with standard types and remove duplicates
            $finalTypes = $types->merge($standardTypes)
                ->map(function($type) {
                    return Str::title(trim($type));
                })
                ->unique()
                ->sort()
                ->values();

            Log::info('Final employment types for dropdown:', [
                'types' => $finalTypes->toArray()
            ]);

            return $finalTypes;

        } catch (\Exception $e) {
            Log::error('Error fetching employment types: ' . $e->getMessage());

            // Return comprehensive default types on error
            return collect([
                'Full-time', 'Part-time', 'Contract', 'Temporary',
                'Internship', 'Freelance', 'Remote', 'Seasonal'
            ]);
        }
    }

    /**
     * Get recommended jobs with applied filters - ensure we always return results
     */
    private function getRecommendedJobsWithFilters($user, $filters = [])
    {
        $query = JobPosting::query()
            ->active()
            ->open()
            ->with(['employer', 'applications' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);

        // Apply keyword search: title, description, requirements, company
        if (!empty($filters['q'])) {
            $keyword = trim($filters['q']);
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('requirements', 'LIKE', "%{$keyword}%")
                  ->orWhereHas('employer', function($q) use ($keyword) {
                      $q->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('company_name', 'LIKE', "%{$keyword}%");
                  });
            });
        }

        // Location filter
        if (!empty($filters['location'])) {
            $loc = trim($filters['location']);
            $query->where(function($q) use ($loc) {
                $q->where('location', 'LIKE', "%{$loc}%")
                  ->orWhere('city', 'LIKE', "%{$loc}%")
                  ->orWhere('province', 'LIKE', "%{$loc}%");
            });
        }

        // Employment type filter - improved with case-insensitive matching
        if (!empty($filters['employment_type'])) {
            $employmentType = trim($filters['employment_type']);
            if (!empty($employmentType)) {
                // Use case-insensitive search with multiple approaches
                $query->where(function($q) use ($employmentType) {
                    $q->where('employment_type', 'LIKE', "%{$employmentType}%")
                      ->orWhere('employment_type', '=', $employmentType)
                      ->orWhereRaw('LOWER(employment_type) = LOWER(?)', [$employmentType])
                      ->orWhereRaw('UPPER(employment_type) = UPPER(?)', [$employmentType]);
                });

                Log::info('Filtering by employment type:', [
                    'requested_type' => $employmentType,
                    'query_applied' => true
                ]);
            }
        }

        // Remote filter
        if (isset($filters['remote']) && $filters['remote'] == 1) {
            if (Schema::hasColumn('job_postings', 'is_remote')) {
                $query->where('is_remote', true);
            } else {
                $query->where(function($q) {
                    $q->where('title', 'LIKE', '%remote%')
                      ->orWhere('description', 'LIKE', '%remote%')
                      ->orWhere('location', 'LIKE', '%remote%')
                      ->orWhere('employment_type', 'LIKE', '%remote%');
                });
            }
        }

        // Disability type filter
        if (!empty($filters['disability_type_id'])) {
            try {
                if (method_exists($query->getModel(), 'scopeForDisabilityTypes')) {
                    $query->forDisabilityTypes([$filters['disability_type_id']]);
                } else {
                    $query->whereHas('suitableDisabilityTypes', function($q) use ($filters) {
                        $q->where('disability_types.id', $filters['disability_type_id']);
                    });
                }
            } catch (\Exception $e) {
                Log::warning('Disability type filter failed: ' . $e->getMessage());
            }
        }

        // Accommodations filter
        if (isset($filters['accommodations']) && $filters['accommodations'] == 1) {
            if (Schema::hasColumn('job_postings', 'provides_accommodations')) {
                $query->where('provides_accommodations', true);
            } else {
                $query->where(function($q) {
                    $q->where('description', 'LIKE', '%accommodat%')
                      ->orWhere('requirements', 'LIKE', '%accommodat%')
                      ->orWhere('title', 'LIKE', '%accessib%')
                      ->orWhere('benefits', 'LIKE', '%accommodat%');
                });
            }
        }

        // Posted within N days
        if (!empty($filters['posted_within_days'])) {
            $days = (int) $filters['posted_within_days'];
            $query->where('created_at', '>=', now()->subDays($days));
        }

        // Apply user-specific recommendations if no filters are active
        $hasActiveFilters = !empty(array_filter($filters, function($value) {
            return $value !== null && $value !== '' && $value !== '0';
        }));

        if (!$hasActiveFilters && $user->pwdProfile) {
            $query = $this->applyUserRecommendations($query, $user);
        }

        // If no jobs found with current filters, get fallback jobs
        $jobCount = $query->count();
        if ($jobCount === 0) {
            Log::warning('No jobs found with current filters, using fallback', [
                'filters' => $filters,
                'user_id' => $user->id,
                'has_active_filters' => $hasActiveFilters
            ]);
            return $this->getFallbackJobs($user, $filters);
        }

        // Sorting
        switch ($filters['sort_by'] ?? 'latest') {
            case 'urgent':
                $query->orderBy('application_deadline', 'asc')->orderByDesc('created_at');
                break;
            default:
                $query->latest();
                break;
        }

        // Return paginated results
        return $query->paginate(10);
    }

    /**
     * Get fallback jobs when no jobs match the filters
     */
    private function getFallbackJobs($user, $filters)
    {
        Log::info('No jobs found with filters, using fallback', ['filters' => $filters, 'user_id' => $user->id]);

        // Start with base query without employment type filter
        $query = JobPosting::query()
            ->active()
            ->open()
            ->with(['employer', 'applications' => function($q) use ($user) {
                $q->where('user_id', $user->id);
            }]);

        // Try without employment type filter but keep other filters
        if (!empty($filters['q'])) {
            $keyword = trim($filters['q']);
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('requirements', 'LIKE', "%{$keyword}%")
                  ->orWhereHas('employer', function($q) use ($keyword) {
                      $q->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('company_name', 'LIKE', "%{$keyword}%");
                  });
            });
        }

        // Location filter without employment type
        if (!empty($filters['location'])) {
            $loc = trim($filters['location']);
            $query->where(function($q) use ($loc) {
                $q->where('location', 'LIKE', "%{$loc}%")
                  ->orWhere('city', 'LIKE', "%{$loc}%")
                  ->orWhere('province', 'LIKE', "%{$loc}%");
            });
        }

        // If still no results, get any active jobs without any filters
        if ($query->count() === 0) {
            Log::info('No jobs even with keyword/location only, getting all active jobs');
            return JobPosting::query()
                ->active()
                ->open()
                ->with(['employer', 'applications' => function($q) use ($user) {
                    $q->where('user_id', $user->id);
                }])
                ->latest()
                ->paginate(10);
        }

        return $query->latest()->paginate(10);
    }

    /**
     * Apply user-specific recommendations based on profile and skills
     */
    private function applyUserRecommendations($query, $user)
    {
        if (!$user->pwdProfile) {
            return $query;
        }

        $skills = $user->pwdProfile->skills ? explode(',', $user->pwdProfile->skills) : [];
        $disabilityType = $user->pwdProfile->disability_type;

        Log::info('Applying user recommendations', [
            'user_id' => $user->id,
            'skills_count' => count($skills),
            'disability_type' => $disabilityType
        ]);

        // If user has skills, prioritize jobs matching those skills
        if (!empty($skills)) {
            $query->where(function($q) use ($skills) {
                foreach ($skills as $skill) {
                    $trimmedSkill = trim($skill);
                    if (!empty($trimmedSkill)) {
                        $q->orWhere('title', 'LIKE', "%{$trimmedSkill}%")
                          ->orWhere('description', 'LIKE', "%{$trimmedSkill}%")
                          ->orWhere('requirements', 'LIKE', "%{$trimmedSkill}%");
                    }
                }
            });
        }

        // If user has disability type, prioritize jobs suitable for that type
        if (!empty($disabilityType)) {
            try {
                $query->whereHas('suitableDisabilityTypes', function($q) use ($disabilityType) {
                    $q->where('disability_types.type', 'LIKE', "%{$disabilityType}%");
                });
            } catch (\Exception $e) {
                Log::warning('Disability type recommendation failed: ' . $e->getMessage());
            }
        }

        return $query;
    }

    /**
     * Get recent job applications for the user
     */
    private function getRecentApplications($user, $limit = 5)
    {
        return $user->jobApplications()
            ->with(['jobPosting.employer'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get upcoming training enrollments
     */
    private function getUpcomingTrainings($user, $limit = 5)
    {
        return $user->trainingEnrollments()
            ->with('skillTraining')
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereHas('skillTraining', function($query) {
                $query->where('start_date', '>=', now())
                      ->orWhere('end_date', '>=', now());
            })
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get quick actions for PWD dashboard
     */
    private function getQuickActions($user): array
    {
        $actions = [
            [
                'title' => 'Browse Jobs',
                'description' => 'Find disability-friendly job opportunities',
                'icon' => 'fas fa-briefcase',
                'url' => route('job-postings.public'),
                'color' => 'primary'
            ],
            [
                'title' => 'Skill Trainings',
                'description' => 'Enhance your skills with our training programs',
                'icon' => 'fas fa-graduation-cap',
                'url' => route('skill-trainings.public.index'),
                'color' => 'success'
            ],
            [
                'title' => 'Update Profile',
                'description' => 'Keep your profile and skills updated',
                'icon' => 'fas fa-user-edit',
                'url' => route('profile.edit'),
                'color' => 'info'
            ]
        ];

        // Add resume action if no resume uploaded
        if (!$user->hasResume()) {
            $actions[] = [
                'title' => 'Upload Resume',
                'description' => 'Upload your resume for better job matching',
                'icon' => 'fas fa-file-upload',
                'url' => route('profile.edit'),
                'color' => 'warning'
            ];
        }

        return $actions;
    }

    /**
     * Get dashboard alerts for PWD users
     */
    private function getDashboardAlerts($user): array
    {
        $alerts = [];

        // Check for incomplete profile
        if ($user->getProfileCompletionPercentage() < 80) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Complete your profile to get better job recommendations.',
                'action' => route('profile.edit')
            ];
        }

        // Check for missing resume
        if (!$user->hasResume()) {
            $alerts[] = [
                'type' => 'info',
                'message' => 'Upload your resume to improve your job applications.',
                'action' => route('profile.edit')
            ];
        }

        // Check for pending applications
        $pendingApplications = $user->jobApplications()->where('status', 'pending')->count();
        if ($pendingApplications > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "You have {$pendingApplications} pending job application(s).",
                'action' => route('applications.index')
            ];
        }

        // Check for upcoming training deadlines
        $upcomingTrainings = $user->trainingEnrollments()
            ->where('status', 'pending')
            ->whereHas('skillTraining', function($query) {
                $query->where('registration_deadline', '<=', now()->addDays(3))
                      ->where('registration_deadline', '>=', now());
            })
            ->count();

        if ($upcomingTrainings > 0) {
            $alerts[] = [
                'type' => 'warning',
                'message' => "You have {$upcomingTrainings} training(s) with approaching registration deadlines.",
                'action' => route('enrollments.index')
            ];
        }

        // Check for unread notifications
        $unreadNotifications = $user->unreadNotifications()->count();
        if ($unreadNotifications > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => "You have {$unreadNotifications} unread notification(s).",
                'action' => route('notifications.index')
            ];
        }

        return $alerts;
    }
}
