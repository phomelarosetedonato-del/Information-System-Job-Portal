<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\JobPosting;
use App\Models\JobApplication;

class EmployerDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        $stats = [];
        $verificationStatus = $user->getEmployerVerificationStatus();
        $profileCompletion = $user->getEmployerProfileCompletion();
        $recentActivity = $this->getRecentActivity($user);
        $upcomingDeadlines = $this->getUpcomingDeadlines($user);
        $alerts = $this->getDashboardAlerts($user);
        $quickActions = $this->getQuickActions($user);

        if ($user->isEmployerVerified()) {
            // Full statistics for verified employers
            $stats = $user->getEmployerStats();
            $performanceMetrics = $this->getPerformanceMetrics($user);
            $applicationTrends = $this->getApplicationTrends($user);

            // Get recent applications with actual data
            $recentApplications = $this->getRecentActivity($user)['recent_applications'] ?? collect();
        } else {
            // Basic statistics for unverified employers
            $stats = [
                'verification_status' => $verificationStatus,
                'profile_completion' => $profileCompletion,
                'can_post_jobs' => false,
                'can_apply_verification' => $this->canApplyForVerification($user),
                'message' => $this->getVerificationMessage($verificationStatus, $user),
                'basic_stats' => $this->getBasicEmployerStats($user),
            ];

            $performanceMetrics = [];
            $applicationTrends = [];
            $recentApplications = collect();
        }

        return view('employer.dashboard', compact(
            'stats',
            'verificationStatus',
            'profileCompletion',
            'recentActivity',
            'upcomingDeadlines',
            'performanceMetrics',
            'applicationTrends',
            'alerts',
            'quickActions',
            'recentApplications'
        ));
    }

    /**
     * Get verification message with detailed information
     */
    private function getVerificationMessage($status, $user): string
    {
        return match($status) {
            'Pending Verification' => 'Your verification request is under review. This usually takes 1-2 business days.',
            'Verification Rejected' => $user->verification_rejected_reason
                ? "Your verification was rejected: {$user->verification_rejected_reason}"
                : 'Your verification was rejected. Please check the requirements and reapply.',
            'Verification Expired' => 'Your employer verification has expired. Please renew your verification to continue posting jobs.',
            'Not Applied' => $this->canApplyForVerification($user)
                ? 'Please complete employer verification to post jobs and access all features.'
                : 'Please complete your employer profile (minimum 70%) before applying for verification.',
            default => 'Employer verification status unknown.'
        };
    }

    /**
     * Check if employer can apply for verification
     */
    private function canApplyForVerification($user): bool
    {
        if ($user->isEmployerPendingVerification() || $user->isEmployerVerified()) {
            return false;
        }

        if ($user->isEmployerRejected()) {
            return $user->canResubmitVerification();
        }

        // For new applications, require minimum profile completion
        return $user->getEmployerProfileCompletion() >= 70;
    }

    /**
     * Get basic employer statistics (for unverified employers)
     */
    private function getBasicEmployerStats($user): array
    {
        return [
            'profile_completion_percentage' => $user->getEmployerProfileCompletion(),
            'missing_fields' => $this->getMissingProfileFields($user),
            'resume_uploaded' => $user->hasResume(),
            'account_created' => $user->created_at->format('M j, Y'),
            'last_profile_update' => $user->updated_at->format('M j, Y'),
        ];
    }

    /**
     * Get missing required profile fields
     */
    private function getMissingProfileFields($user): array
    {
        $requiredFields = [
            'name' => 'Full Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'address' => 'Physical Address',
            'company_name' => 'Company Name',
            'company_size' => 'Company Size',
            'company_type' => 'Company Type',
            'website' => 'Company Website',
            'description' => 'Company Description',
        ];

        $missing = [];
        foreach ($requiredFields as $field => $label) {
            if (empty($user->$field)) {
                $missing[] = $label;
            }
        }

        return $missing;
    }

    /**
     * Get recent activity for the employer
     */
    private function getRecentActivity($user)
    {
        $activity = [];

        if ($user->isEmployerVerified()) {
            // Recent applications
            $recentApplications = JobApplication::whereIn('job_posting_id', function($query) use ($user) {
                $query->select('id')
                      ->from('job_postings')
                      ->where('created_by', $user->id);
            })
            ->with(['jobPosting', 'user'])
            ->latest()
            ->limit(5)
            ->get();

            // Recent job postings
            $recentJobPostings = $user->jobPostings()
                ->latest()
                ->limit(3)
                ->get();

            $activity = [
                'recent_applications' => $recentApplications,
                'recent_job_postings' => $recentJobPostings,
                'total_activities' => $recentApplications->count() + $recentJobPostings->count(),
            ];
        } else {
            // Basic activity for unverified employers
            $activity = [
                'profile_views' => 0,
                'saved_jobs' => 0,
                'recent_activities' => [],
            ];
        }

        return $activity;
    }

    /**
     * Get upcoming application deadlines
     */
    private function getUpcomingDeadlines($user)
    {
        if (!$user->isEmployerVerified()) {
            return collect();
        }

        return $user->jobPostings()
            ->where('is_active', true)
            ->where('application_deadline', '>=', now())
            ->where('application_deadline', '<=', now()->addDays(7))
            ->orderBy('application_deadline')
            ->limit(5)
            ->get();
    }

    /**
     * Get performance metrics for verified employers
     */
    private function getPerformanceMetrics($user): array
    {
        $jobPostings = $user->jobPostings();
        $totalApplications = $user->getTotalApplicationsReceived();

        return [
            'average_application_time' => $this->calculateAverageApplicationTime($user),
            'conversion_rate' => $this->calculateConversionRate($user),
            'completion_rate' => $this->calculateJobCompletionRate($user),
            'response_time' => $this->calculateAverageResponseTime($user),
            'popular_job_categories' => $this->getPopularJobCategories($user),
        ];
    }

    /**
     * Calculate average time to receive applications after posting
     */
    private function calculateAverageApplicationTime($user): ?string
    {
        $jobPostings = $user->jobPostings()
            ->has('applications')
            ->with('applications')
            ->get();

        if ($jobPostings->isEmpty()) {
            return null;
        }

        $totalDays = 0;
        $count = 0;

        foreach ($jobPostings as $job) {
            $firstApplication = $job->applications->sortBy('created_at')->first();
            if ($firstApplication) {
                $daysToFirstApplication = $job->created_at->diffInDays($firstApplication->created_at);
                $totalDays += $daysToFirstApplication;
                $count++;
            }
        }

        return $count > 0 ? round($totalDays / $count, 1) . ' days' : 'No data';
    }

    /**
     * Calculate application to hire conversion rate
     */
    private function calculateConversionRate($user): float
    {
        $totalApplications = $user->getTotalApplicationsReceived();
        $hiredApplications = JobApplication::whereIn('job_posting_id', function($query) use ($user) {
            $query->select('id')
                  ->from('job_postings')
                  ->where('created_by', $user->id);
        })->where('status', 'approved')->count();

        return $totalApplications > 0 ? round(($hiredApplications / $totalApplications) * 100, 2) : 0.0;
    }

    /**
     * Calculate job posting completion rate
     */
    private function calculateJobCompletionRate($user): float
    {
        $totalJobs = $user->jobPostings()->count();
        $completedJobs = $user->jobPostings()
            ->where('application_deadline', '<', now())
            ->orWhere('is_active', false)
            ->count();

        return $totalJobs > 0 ? round(($completedJobs / $totalJobs) * 100, 2) : 0.0;
    }

    /**
     * Calculate average response time to applications
     */
    private function calculateAverageResponseTime($user): ?string
    {
        $applications = JobApplication::whereIn('job_posting_id', function($query) use ($user) {
            $query->select('id')
                  ->from('job_postings')
                  ->where('created_by', $user->id);
        })
        ->whereNotNull('status_updated_at')
        ->get();

        if ($applications->isEmpty()) {
            return null;
        }

        $totalHours = 0;
        foreach ($applications as $application) {
            $responseTime = $application->created_at->diffInHours($application->status_updated_at);
            $totalHours += $responseTime;
        }

        $averageHours = $totalHours / $applications->count();

        if ($averageHours < 24) {
            return round($averageHours, 1) . ' hours';
        } else {
            return round($averageHours / 24, 1) . ' days';
        }
    }

    /**
     * Get popular job categories
     */
    private function getPopularJobCategories($user): array
    {
        return $user->jobPostings()
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderBy('total', 'desc')
            ->limit(5)
            ->get()
            ->pluck('total', 'category')
            ->toArray();
    }

    /**
     * Get application trends for the last 30 days
     */
    private function getApplicationTrends($user): array
    {
        $trends = [];
        $startDate = now()->subDays(30);

        for ($i = 0; $i <= 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');

            $count = JobApplication::whereIn('job_posting_id', function($query) use ($user) {
                $query->select('id')
                      ->from('job_postings')
                      ->where('created_by', $user->id);
            })
            ->whereDate('created_at', $date)
            ->count();

            $trends['labels'][] = $date;
            $trends['data'][] = $count;
        }

        return $trends;
    }

    /**
     * Get quick actions based on employer status
     */
    public function getQuickActions($user): array
    {
        $actions = [];

        if ($user->isEmployerVerified()) {
            $actions = [
                [
                    'title' => 'Post New Job',
                    'description' => 'Create a new job posting',
                    'icon' => 'fas fa-briefcase',
                    'url' => route('employer.job-postings.create'),
                    'color' => 'primary'
                ],
                [
                    'title' => 'View Applications',
                    'description' => 'Manage job applications',
                    'icon' => 'fas fa-users',
                    'url' => route('employer.applications.index'),
                    'color' => 'success'
                ],
                [
                    'title' => 'Analytics',
                    'description' => 'View performance metrics',
                    'icon' => 'fas fa-chart-bar',
                    'url' => route('employer.job-postings.statistics'),
                    'color' => 'info'
                ]
            ];
        } else {
            $actions = [
                [
                    'title' => 'Complete Profile',
                    'description' => 'Finish setting up your employer profile',
                    'icon' => 'fas fa-user-edit',
                    'url' => route('employer.profile.edit'),
                    'color' => 'warning'
                ],
                [
                    'title' => 'Apply for Verification',
                    'description' => 'Get verified to post jobs',
                    'icon' => 'fas fa-check-circle',
                    'url' => $this->canApplyForVerification($user)
                        ? route('employer.verification.apply')
                        : route('employer.profile.edit'),
                    'color' => 'primary',
                    'disabled' => !$this->canApplyForVerification($user)
                ],
                [
                    'title' => 'View Requirements',
                    'description' => 'See verification requirements',
                    'icon' => 'fas fa-list-alt',
                    'url' => route('employer.verification.requirements'),
                    'color' => 'info'
                ]
            ];
        }

        return $actions;
    }

    /**
     * Get dashboard alerts and notifications
     */
    public function getDashboardAlerts($user): array
    {
        $alerts = [];

        if ($user->isEmployerVerified()) {
            // Check for expiring job postings
            $expiringJobs = $user->jobPostings()
                ->where('is_active', true)
                ->where('application_deadline', '<=', now()->addDays(3))
                ->where('application_deadline', '>=', now())
                ->count();

            if ($expiringJobs > 0) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "You have {$expiringJobs} job posting(s) expiring soon.",
                    'action' => route('employer.job-postings.index')
                ];
            }

            // Check for pending applications
            $pendingApplications = JobApplication::whereIn('job_posting_id', function($query) use ($user) {
                $query->select('id')
                      ->from('job_postings')
                      ->where('created_by', $user->id);
            })->where('status', 'pending')->count();

            if ($pendingApplications > 0) {
                $alerts[] = [
                    'type' => 'info',
                    'message' => "You have {$pendingApplications} pending application(s) to review.",
                    'action' => route('employer.applications.index')
                ];
            }

            // Check verification expiration
            if ($user->verification_expires_at && $user->verification_expires_at->diffInDays(now()) <= 30) {
                $daysLeft = $user->getDaysUntilVerificationExpires();
                $alerts[] = [
                    'type' => 'warning',
                    'message' => "Your employer verification expires in {$daysLeft} days.",
                    'action' => route('employer.verification.status')
                ];
            }
        } else {
            // Alerts for unverified employers
            if ($user->isEmployerRejected() && $user->canResubmitVerification()) {
                $alerts[] = [
                    'type' => 'info',
                    'message' => 'You can now resubmit your verification application.',
                    'action' => route('employer.verification.apply')
                ];
            }

            if ($user->getEmployerProfileCompletion() < 70 && !$user->isEmployerPendingVerification()) {
                $alerts[] = [
                    'type' => 'warning',
                    'message' => 'Complete your profile to apply for verification.',
                    'action' => route('employer.profile.edit')
                ];
            }
        }

        return $alerts;
    }
}
