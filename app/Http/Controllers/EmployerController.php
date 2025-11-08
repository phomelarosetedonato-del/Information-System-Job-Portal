<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\JobApplication;

class EmployerController extends Controller
{
    /**
     * Show employer settings page
     */
    public function settings()
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        return view('employer.settings', compact('user'));
    }

    /**
     * Show employer profile
     */
    public function profile()
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        $stats = $user->getEmployerStats();
        $verificationStatus = $user->getEmployerVerificationStatus();

        return view('employer.profile.show', compact('user', 'stats', 'verificationStatus'));
    }

    /**
     * Show edit employer profile form
     */
    public function editProfile()
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        return view('employer.profile.edit', compact('user'));
    }

    /**
     * Update employer profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'company_name' => 'required|string|max:255',
            'company_size' => 'required|string|in:1-10,11-50,51-200,201-500,501-1000,1000+',
            'company_type' => 'required|string|in:private,public,government,nonprofit,educational',
            'website' => 'required|url|max:255',
            'description' => 'required|string|min:100|max:2000',
        ]);

        try {
            $user->update($validated);

            return redirect()->route('employer.profile.show')
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile. Please try again.')
                ->withInput();
        }
    }

    /**
     * Upload employer resume
     */
    public function uploadResume(Request $request)
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
        ]);

        try {
            // Delete old resume if exists
            if ($user->resume) {
                Storage::disk('public')->delete($user->resume);
            }

            // Store new resume
            $path = $request->file('resume')->store('resumes', 'public');
            $user->update(['resume' => $path]);

            return back()->with('success', 'Resume uploaded successfully!');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to upload resume. Please try again.');
        }
    }

    /**
     * Delete employer resume
     */
    public function deleteResume()
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            abort(403, 'Access denied. Employer account required.');
        }

        try {
            if ($user->resume) {
                Storage::disk('public')->delete($user->resume);
                $user->update(['resume' => null]);

                return back()->with('success', 'Resume deleted successfully!');
            }

            return back()->with('info', 'No resume found to delete.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete resume. Please try again.');
        }
    }

    /**
     * Get employer statistics
     */
    public function getStats()
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $stats = $user->getEmployerStats();

        return response()->json($stats);
    }

    /**
     * Show analytics overview
     */
    public function analyticsOverview()
    {
        $user = Auth::user();

        if (!$user->isEmployer() || !$user->isEmployerVerified()) {
            abort(403, 'Access denied. Verified employer account required.');
        }

        $stats = $user->getEmployerStats();
        $performanceMetrics = $this->getPerformanceMetricsData($user);
        $applicationTrends = $this->getApplicationTrendsData($user);

        return view('employer.analytics.overview', compact(
            'user', 'stats', 'performanceMetrics', 'applicationTrends'
        ));
    }

    /**
     * Show performance metrics
     */
    public function performanceMetrics()
    {
        $user = Auth::user();

        if (!$user->isEmployer() || !$user->isEmployerVerified()) {
            abort(403, 'Access denied. Verified employer account required.');
        }

        $metrics = $this->getDetailedPerformanceMetrics($user);
        $comparisonData = $this->getPlatformComparisonData($user);

        return view('employer.analytics.performance', compact(
            'user', 'metrics', 'comparisonData'
        ));
    }

    /**
     * Show application trends
     */
    public function applicationTrends()
    {
        $user = Auth::user();

        if (!$user->isEmployer() || !$user->isEmployerVerified()) {
            abort(403, 'Access denied. Verified employer account required.');
        }

        $trends = $this->getApplicationTrendsData($user);
        $categoryData = $this->getApplicationByCategoryData($user);
        $sourceData = $this->getApplicationSourceData($user);

        return view('employer.analytics.application-trends', compact(
            'user', 'trends', 'categoryData', 'sourceData'
        ));
    }

    /**
     * Show jobs performance
     */
    public function jobsPerformance()
    {
        $user = Auth::user();

        if (!$user->isEmployer() || !$user->isEmployerVerified()) {
            abort(403, 'Access denied. Verified employer account required.');
        }

        $jobsPerformance = $this->getJobsPerformanceData($user);
        $topPerformingJobs = $this->getTopPerformingJobs($user);
        $underperformingJobs = $this->getUnderperformingJobs($user);

        return view('employer.analytics.jobs-performance', compact(
            'user', 'jobsPerformance', 'topPerformingJobs', 'underperformingJobs'
        ));
    }

    /**
     * Get performance metrics data
     */
    private function getPerformanceMetricsData($user): array
    {
        $jobPostings = $user->jobPostings();
        $totalApplications = $user->getTotalApplicationsReceived();

        return [
            'average_application_time' => $this->calculateAverageApplicationTime($user),
            'conversion_rate' => $this->calculateConversionRate($user),
            'completion_rate' => $this->calculateJobCompletionRate($user),
            'response_time' => $this->calculateAverageResponseTime($user),
            'application_quality_score' => $this->calculateApplicationQualityScore($user),
            'employer_rating' => $this->calculateEmployerRating($user),
        ];
    }

    /**
     * Get detailed performance metrics
     */
    private function getDetailedPerformanceMetrics($user): array
    {
        $metrics = $this->getPerformanceMetricsData($user);

        // Add additional metrics
        $metrics['total_job_views'] = $user->jobPostings()->sum('views');
        $metrics['average_views_per_job'] = $user->jobPostings()->avg('views') ?? 0;
        $metrics['application_to_view_ratio'] = $this->calculateApplicationToViewRatio($user);
        $metrics['time_to_fill'] = $this->calculateAverageTimeToFill($user);
        $metrics['candidate_satisfaction'] = $this->calculateCandidateSatisfaction($user);

        return $metrics;
    }

    /**
     * Get application trends data
     */
    public function getApplicationTrends()
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $trends = $this->getApplicationTrendsData($user);

        return response()->json($trends);
    }

    /**
     * Get application trends data for charts
     */
    private function getApplicationTrendsData($user): array
    {
        $trends = [
            'labels' => [],
            'applications' => [],
            'views' => [],
        ];

        $startDate = now()->subDays(30);

        for ($i = 0; $i <= 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');

            $applicationCount = JobApplication::whereIn('job_posting_id', function($query) use ($user) {
                $query->select('id')
                      ->from('job_postings')
                      ->where('created_by', $user->id);
            })
            ->whereDate('created_at', $date)
            ->count();

            $viewCount = JobPosting::where('created_by', $user->id)
                ->whereDate('created_at', '<=', $date)
                ->sum('views');

            $trends['labels'][] = $date;
            $trends['applications'][] = $applicationCount;
            $trends['views'][] = $viewCount;
        }

        return $trends;
    }

    /**
     * Get application by category data
     */
    private function getApplicationByCategoryData($user): array
    {
        return $user->jobPostings()
            ->select('category', \DB::raw('count(*) as total_applications'))
            ->join('job_applications', 'job_postings.id', '=', 'job_applications.job_posting_id')
            ->groupBy('category')
            ->orderBy('total_applications', 'desc')
            ->get()
            ->pluck('total_applications', 'category')
            ->toArray();
    }

    /**
     * Get application source data
     */
    private function getApplicationSourceData($user): array
    {
        // This would typically come from tracking data
        // For now, we'll return mock data
        return [
            'Direct' => 45,
            'Job Portal' => 30,
            'Social Media' => 15,
            'Referral' => 10,
        ];
    }

    /**
     * Get jobs performance data
     */
    private function getJobsPerformanceData($user): array
    {
        $jobs = $user->jobPostings()
            ->withCount('applications')
            ->get();

        $performanceData = [];

        foreach ($jobs as $job) {
            $completionRate = $job->application_deadline < now() ? 100 :
                (($job->created_at->diffInDays(now()) / $job->created_at->diffInDays($job->application_deadline)) * 100);

            $performanceData[] = [
                'job_title' => $job->title,
                'views' => $job->views,
                'applications' => $job->applications_count,
                'completion_rate' => min(100, $completionRate),
                'application_ratio' => $job->views > 0 ? round(($job->applications_count / $job->views) * 100, 2) : 0,
            ];
        }

        return $performanceData;
    }

    /**
     * Get top performing jobs
     */
    private function getTopPerformingJobs($user, $limit = 5)
    {
        return $user->jobPostings()
            ->withCount('applications')
            ->orderBy('applications_count', 'desc')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get underperforming jobs
     */
    private function getUnderperformingJobs($user, $limit = 5)
    {
        return $user->jobPostings()
            ->withCount('applications')
            ->having('applications_count', '<', 5)
            ->orderBy('applications_count')
            ->orderBy('views')
            ->limit($limit)
            ->get();
    }

    /**
     * Get platform comparison data
     */
    private function getPlatformComparisonData($user): array
    {
        // This would typically come from platform-wide averages
        // For now, we'll return mock data
        $userMetrics = $this->getPerformanceMetricsData($user);

        return [
            'conversion_rate' => [
                'user' => $userMetrics['conversion_rate'],
                'platform_average' => 12.5,
            ],
            'response_time' => [
                'user' => $userMetrics['response_time'] ? floatval(str_replace([' hours', ' days'], '', $userMetrics['response_time'])) : 0,
                'platform_average' => 2.5,
            ],
            'application_quality' => [
                'user' => $userMetrics['application_quality_score'],
                'platform_average' => 7.2,
            ],
        ];
    }

    /**
     * Calculate application to view ratio
     */
    private function calculateApplicationToViewRatio($user): float
    {
        $totalViews = $user->jobPostings()->sum('views');
        $totalApplications = $user->getTotalApplicationsReceived();

        return $totalViews > 0 ? round(($totalApplications / $totalViews) * 100, 2) : 0;
    }

    /**
     * Calculate average time to fill positions
     */
    private function calculateAverageTimeToFill($user): ?string
    {
        $filledJobs = JobPosting::where('created_by', $user->id)
            ->whereHas('applications', function($query) {
                $query->where('status', 'approved');
            })
            ->with(['applications' => function($query) {
                $query->where('status', 'approved')->orderBy('created_at');
            }])
            ->get();

        if ($filledJobs->isEmpty()) {
            return null;
        }

        $totalDays = 0;
        $count = 0;

        foreach ($filledJobs as $job) {
            $firstApplication = $job->applications->first();
            if ($firstApplication) {
                $daysToFill = $job->created_at->diffInDays($firstApplication->created_at);
                $totalDays += $daysToFill;
                $count++;
            }
        }

        return $count > 0 ? round($totalDays / $count, 1) . ' days' : null;
    }

    /**
     * Calculate candidate satisfaction (mock implementation)
     */
    private function calculateCandidateSatisfaction($user): float
    {
        // This would typically come from candidate feedback and ratings
        // For now, we'll return a mock value
        return 4.2; // out of 5
    }

    /**
     * Calculate application quality score
     */
    private function calculateApplicationQualityScore($user): float
    {
        $totalApplications = $user->getTotalApplicationsReceived();

        if ($totalApplications === 0) {
            return 0.0;
        }

        $qualityApplications = JobApplication::whereIn('job_posting_id', function($query) use ($user) {
            $query->select('id')
                  ->from('job_postings')
                  ->where('created_by', $user->id);
        })
        ->whereHas('user', function($query) {
            $query->where('profile_completion_percentage', '>=', 80);
        })
        ->count();

        return round(($qualityApplications / $totalApplications) * 10, 1); // Scale 0-10
    }

    /**
     * Calculate employer rating (mock implementation)
     */
    private function calculateEmployerRating($user): float
    {
        // This would typically come from candidate ratings
        // For now, we'll calculate based on response rate and time
        $responseRate = $user->calculateResponseRate();
        $responseTime = $this->calculateAverageResponseTime($user);

        $responseTimeScore = $responseTime ?
            (str_contains($responseTime, 'hours') ? 5 :
             (str_contains($responseTime, 'days') && floatval($responseTime) < 3 ? 4 : 3)) : 2;

        return min(5, ($responseRate / 20) + ($responseTimeScore / 2)); // Scale 1-5
    }

    /**
     * Calculate average application time
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
     * Calculate conversion rate
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
     * Calculate job completion rate
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
     * Calculate average response time
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
     * Get performance metrics for API
     */
    public function getPerformanceMetrics()
    {
        $user = Auth::user();

        if (!$user->isEmployer()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        $metrics = $this->getPerformanceMetricsData($user);

        return response()->json($metrics);
    }
}
