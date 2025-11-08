<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use App\Notifications\JobApplicationStatusUpdated;
use App\Notifications\NewJobApplicationReceived;
use App\Notifications\ApplicationApproved;
use App\Notifications\ApplicationRejected;
use App\Notifications\ApplicationShortlisted;
use App\Notifications\ApplicationInterviewScheduled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * @method \App\Models\User user()
 * @property int $id
 */
class JobApplicationController extends Controller
{
    /**
     * Apply for a job posting with enhanced validation
     */
    public function apply(JobPosting $job, Request $request)
    {
        $user = auth()->user();

        // Enhanced validation checks
        $validation = $this->validateApplicationEligibility($user, $job);
        if (!$validation['can_apply']) {
            return $this->handleApplicationError($validation, $job);
        }

        // Validate custom cover letter if provided
        $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'custom_resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048'
        ]);

        // Handle custom resume upload
        $resumePath = $user->resume_path;
        if ($request->hasFile('custom_resume')) {
            $resumePath = $request->file('custom_resume')->store('resumes', 'public');
        }

        // Create application with enhanced data
        $application = JobApplication::create([
            'user_id' => $user->id,
            'job_posting_id' => $job->id,
            'status' => 'pending',
            'cover_letter' => $request->cover_letter ?: $this->generateDefaultCoverLetter($user, $job),
            'resume_path' => $resumePath,
            'applied_via' => 'web',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Track application in user stats
        $user->increment('total_applications');

        // Notify admins and employer
        $this->sendApplicationNotifications($application);

        // Log application activity
        activity()
            ->causedBy($user)
            ->performedOn($application)
            ->withProperties(['job_id' => $job->id, 'job_title' => $job->title])
            ->log('applied for job');

        return redirect()->route('applications.index')
            ->with('success', 'Application submitted successfully! The employer will review your application.');
    }

    /**
     * Enhanced eligibility validation
     */
    private function validateApplicationEligibility($user, $job)
    {
        $reasons = [];
        $canApply = true;

        // Job availability checks
        if (!$job->is_active) {
            $canApply = false;
            $reasons[] = 'This job posting is no longer available.';
        }

        if ($job->application_deadline && $job->application_deadline->isPast()) {
            $canApply = false;
            $reasons[] = 'The application deadline for this job has passed.';
        }

        // User eligibility checks
        if (!$user->hasResume()) {
            $canApply = false;
            $reasons[] = 'Please upload your resume before applying.';
        }

        if (!$user->pwdProfile || empty($user->pwdProfile->disability_type)) {
            $canApply = false;
            $reasons[] = 'Please complete your PWD profile before applying.';
        }

        // Check if already applied
        $existingApplication = JobApplication::where('user_id', $user->id)
            ->where('job_posting_id', $job->id)
            ->first();

        if ($existingApplication) {
            $canApply = false;
            $reasons[] = 'You have already applied for this job.';
        }

        return [
            'can_apply' => $canApply,
            'reasons' => $reasons,
            'missing_resume' => !$user->hasResume(),
            'incomplete_profile' => !$user->pwdProfile || empty($user->pwdProfile->disability_type),
        ];
    }

    /**
     * Handle application errors appropriately
     */
    private function handleApplicationError($validation, $job)
    {
        if ($validation['missing_resume']) {
            return redirect()->back()
                ->with('error', 'Please upload your resume before applying for jobs.')
                ->with('show_resume_modal', true);
        }

        if ($validation['incomplete_profile']) {
            return redirect()->route('profile.pwd-complete-form')
                ->with('warning', 'Please complete your PWD profile before applying for jobs.');
        }

        return redirect()->back()
            ->with('error', implode(' ', $validation['reasons']));
    }

    /**
     * Generate default cover letter
     */
    private function generateDefaultCoverLetter($user, $job)
    {
        return "Dear Hiring Manager,\n\n" .
               "I am excited to apply for the {$job->title} position at {$job->company}. " .
               "With my skills and experience, I believe I would be a valuable asset to your team.\n\n" .
               "Thank you for considering my application.\n\n" .
               "Sincerely,\n" .
               "{$user->name}";
    }

    /**
     * Send application notifications
     */
    private function sendApplicationNotifications($application)
    {
        // Notify admins
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new NewJobApplicationReceived($application));
        }

        // Notify employer if job belongs to specific employer
        if ($application->jobPosting->created_by) {
            $employer = User::find($application->jobPosting->created_by);
            if ($employer) {
                $employer->notify(new NewJobApplicationReceived($application));
            }
        }
    }

    /**
     * Enhanced application index with filters
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = $user->jobApplications()
            ->with(['jobPosting' => function($query) {
                $query->withTrashed();
            }]);

        // Apply filters
        $this->applyApplicationFilters($query, $request);

        $applications = $query->latest()
            ->paginate(12)
            ->withQueryString();

        // Enhanced statistics
        $stats = $this->getUserApplicationStats($user);

        return view('applications.index', compact(
            'applications',
            'stats'
        ));
    }

    /**
     * Apply advanced filters to applications query
     */
    private function applyApplicationFilters($query, $request)
    {
        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        // Company filter
        if ($request->has('company') && $request->company) {
            $query->whereHas('jobPosting', function($q) use ($request) {
                $q->where('company', 'like', '%' . $request->company . '%');
            });
        }

        // Search in job titles
        if ($request->has('search') && $request->search) {
            $query->whereHas('jobPosting', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%');
            });
        }
    }

    /**
     * Get enhanced user application statistics
     */
    private function getUserApplicationStats($user)
    {
        $total = $user->jobApplications()->count();
        $pending = $user->jobApplications()->where('status', 'pending')->count();
        $shortlisted = $user->jobApplications()->where('status', 'shortlisted')->count();
        $approved = $user->jobApplications()->where('status', 'approved')->count();
        $rejected = $user->jobApplications()->where('status', 'rejected')->count();
        $withdrawn = $user->jobApplications()->where('status', 'withdrawn')->count();
        $hired = $user->jobApplications()->where('status', 'hired')->count();

        // Calculate success rate
        $successRate = $total > 0 ? (($approved + $hired) / $total) * 100 : 0;

        return [
            'total' => $total,
            'pending' => $pending,
            'shortlisted' => $shortlisted,
            'approved' => $approved,
            'rejected' => $rejected,
            'withdrawn' => $withdrawn,
            'hired' => $hired,
            'success_rate' => round($successRate, 1),
        ];
    }

    /**
     * Display specific application details
     */
    public function show(JobApplication $application)
    {
        // Ensure the application belongs to the current user or user is admin
        if ($application->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Load job posting with trashed records
        $application->load(['jobPosting' => function($query) {
            $query->withTrashed();
        }, 'user.pwdProfile']);

        return view('applications.show', compact('application'));
    }

    /**
     * Enhanced application status update with notification system
     */
    public function updateStatus(JobApplication $application, Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,shortlisted,approved,rejected,hired',
            'admin_notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $oldStatus = $application->status;

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'status_updated_at' => now(),
        ]);

        // Send notification if status changed
        if ($oldStatus !== $request->status) {
            $this->sendStatusNotification($application, $request->status, $request->rejection_reason);
        }

        return redirect()->back()->with('success', 'Application status updated successfully and user has been notified.');
    }

    /**
     * Send status notification with enhanced logging
     */
    /**
 * Send status notification with enhanced logging
 */
protected function sendStatusNotification(JobApplication $application, string $status, ?string $reason = null): void
{
    $user = $application->user;

    \Log::info("🚀 === SENDING STATUS NOTIFICATION ===");
    \Log::info("📋 Application ID: " . $application->id);
    \Log::info("👤 User ID: " . $user->id);
    \Log::info("📧 User Email: " . $user->email);
    \Log::info("🔄 Status: " . $status);
    \Log::info("📝 Reason: " . ($reason ?? 'None'));
    \Log::info("💼 Job: " . ($application->jobPosting->title ?? 'No job'));
    \Log::info("⚙️ Queue Driver: " . config('queue.default'));

    try {
        // Test basic email first
        \Mail::raw('Test email before notification', function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Test Email Before Notification');
        });
        \Log::info("✅ Basic email test passed");

        switch ($status) {
            case 'approved':
                $user->notify(new ApplicationApproved($application));
                \Log::info("✅ APPROVED notification sent to: " . $user->email);
                break;

            case 'rejected':
                $user->notify(new ApplicationRejected($application, $reason));
                \Log::info("✅ REJECTED notification sent to: " . $user->email);
                break;

            case 'shortlisted':
                $user->notify(new ApplicationShortlisted($application));
                \Log::info("✅ SHORTLISTED notification sent to: " . $user->email);
                break;

            case 'hired':
                $user->notify(new ApplicationApproved($application));
                \Log::info("✅ HIRED notification sent to: " . $user->email);
                break;
        }

        \Log::info("🎉 Notification process completed successfully");

    } catch (\Exception $e) {
        \Log::error('💥 NOTIFICATION FAILED: ' . $e->getMessage());
        \Log::error('📄 File: ' . $e->getFile() . ':' . $e->getLine());
        \Log::error('🔍 Trace: ' . $e->getTraceAsString());
    }
}

    /**
     * Shortlist application
     */
    public function shortlist(JobApplication $application, Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized action.');
        }

        $oldStatus = $application->status;

        $application->update([
            'status' => 'shortlisted',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'status_updated_at' => now(),
        ]);

        // Send notification if status changed
        if ($oldStatus !== 'shortlisted') {
            $this->sendStatusNotification($application, 'shortlisted');
        }

        return redirect()->back()->with('success', 'Application shortlisted successfully and user has been notified.');
    }

    /**
     * Reject application
     */
    public function reject(JobApplication $application, Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $oldStatus = $application->status;

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'status_updated_at' => now(),
        ]);

        // Send notification if status changed
        if ($oldStatus !== 'rejected') {
            $this->sendStatusNotification($application, 'rejected', $request->rejection_reason);
        }

        return redirect()->back()->with('success', 'Application rejected successfully and user has been notified.');
    }

    /**
     * Schedule interview for application
     */
    public function scheduleInterview(JobApplication $application, Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'interview_date' => 'required|date|after:now',
            'interview_time' => 'required',
            'interview_location' => 'required|string|max:500',
            'interview_notes' => 'nullable|string|max:1000'
        ]);

        $application->update([
            'interview_scheduled_at' => $request->interview_date . ' ' . $request->interview_time,
            'interview_location' => $request->interview_location,
            'interview_notes' => $request->interview_notes,
        ]);

        // Send interview scheduled notification
        try {
            $application->user->notify(new ApplicationInterviewScheduled($application));
            Log::info("Interview scheduled notification sent for application {$application->id}");
        } catch (\Exception $e) {
            Log::error('Failed to send interview notification: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Interview scheduled successfully and user has been notified.');
    }

    /**
     * Bulk update applications
     */
    public function bulkUpdate(Request $request)
    {
        if (!auth()->user()->isAdmin() && !auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
            'status' => 'required|in:pending,shortlisted,approved,rejected,hired',
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $updatedCount = 0;
        $applicationIds = $request->application_ids;

        foreach ($applicationIds as $applicationId) {
            $application = JobApplication::find($applicationId);
            $oldStatus = $application->status;

            $application->update([
                'status' => $request->status,
                'rejection_reason' => $request->rejection_reason,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'status_updated_at' => now(),
            ]);

            // Send notification if status changed
            if ($oldStatus !== $request->status) {
                $this->sendStatusNotification($application, $request->status, $request->rejection_reason);
            }

            $updatedCount++;
        }

        return redirect()->back()->with('success', "{$updatedCount} applications updated successfully.");
    }

    /**
     * Withdraw application
     */
    public function withdraw(JobApplication $application)
    {
        // Ensure the application belongs to the current user
        if ($application->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Only allow withdrawal if status is pending or shortlisted
        if (!in_array($application->status, ['pending', 'shortlisted'])) {
            return redirect()->back()->with('error', 'You can only withdraw applications that are pending or shortlisted.');
        }

        $application->update([
            'status' => 'withdrawn',
            'withdrawn_at' => now(),
            'status_updated_at' => now(),
        ]);

        return redirect()->route('applications.index')
            ->with('success', 'Application withdrawn successfully.');
    }

    /**
     * Cancel application (similar to withdraw but different terminology)
     */
    public function cancel(JobApplication $application)
    {
        return $this->withdraw($application);
    }

    /**
     * Admin index for managing all applications
     */
    public function adminIndex(Request $request)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $query = JobApplication::with(['user', 'user.pwdProfile', 'jobPosting' => function($query) {
            $query->withTrashed();
        }]);

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                             ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhereHas('jobPosting', function($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', '%' . $search . '%')
                            ->orWhere('company', 'like', '%' . $search . '%');
                });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to . ' 23:59:59');
        }

        $applications = $query->latest()->paginate(20);

        $stats = [
            'total' => JobApplication::count(),
            'pending' => JobApplication::where('status', 'pending')->count(),
            'shortlisted' => JobApplication::where('status', 'shortlisted')->count(),
            'approved' => JobApplication::where('status', 'approved')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
            'withdrawn' => JobApplication::where('status', 'withdrawn')->count(),
            'hired' => JobApplication::where('status', 'hired')->count(),
        ];

        return view('applications.admin-index', compact('applications', 'stats'));
    }

    /**
     * Employer index for managing applications to their job postings
     */
    public function employerIndex(Request $request)
    {
        if (!auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized action.');
        }

        $employerId = auth()->id();

        $query = JobApplication::with(['user', 'user.pwdProfile', 'jobPosting'])
            ->whereHas('jobPosting', function($q) use ($employerId) {
                $q->where('created_by', $employerId);
            });

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                             ->orWhere('email', 'like', '%' . $search . '%');
                })->orWhereHas('jobPosting', function($jobQuery) use ($search) {
                    $jobQuery->where('title', 'like', '%' . $search . '%');
                });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Job posting filter
        if ($request->has('job_posting_id') && $request->job_posting_id) {
            $query->where('job_posting_id', $request->job_posting_id);
        }

        $applications = $query->latest()->paginate(20);

        // Get employer's job postings for filter dropdown
        $jobPostings = JobPosting::where('created_by', $employerId)
            ->where('is_active', true)
            ->get();

        $stats = [
            'total' => $query->count(),
            'pending' => $query->clone()->where('status', 'pending')->count(),
            'shortlisted' => $query->clone()->where('status', 'shortlisted')->count(),
            'approved' => $query->clone()->where('status', 'approved')->count(),
            'rejected' => $query->clone()->where('status', 'rejected')->count(),
        ];

        return view('applications.employer-index', compact('applications', 'stats', 'jobPostings'));
    }

    /**
     * Application statistics for admin
     */
    public function statistics()
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $totalApplications = JobApplication::count();
        $applicationsByStatus = JobApplication::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $applicationsByMonth = JobApplication::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $topJobs = JobApplication::select('job_posting_id', DB::raw('count(*) as applications'))
            ->with('jobPosting')
            ->groupBy('job_posting_id')
            ->orderBy('applications', 'desc')
            ->limit(10)
            ->get();

        return view('applications.statistics', compact(
            'totalApplications',
            'applicationsByStatus',
            'applicationsByMonth',
            'topJobs'
        ));
    }

    /**
     * Employer application statistics
     */
    public function employerStatistics()
    {
        if (!auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized action.');
        }

        $employerId = auth()->id();

        $totalApplications = JobApplication::whereHas('jobPosting', function($q) use ($employerId) {
            $q->where('created_by', $employerId);
        })->count();

        $applicationsByStatus = JobApplication::whereHas('jobPosting', function($q) use ($employerId) {
            $q->where('created_by', $employerId);
        })
        ->select('status', DB::raw('count(*) as count'))
        ->groupBy('status')
        ->pluck('count', 'status');

        $applicationsByJob = JobApplication::whereHas('jobPosting', function($q) use ($employerId) {
            $q->where('created_by', $employerId);
        })
        ->select('job_posting_id', DB::raw('count(*) as applications'))
        ->with('jobPosting')
        ->groupBy('job_posting_id')
        ->orderBy('applications', 'desc')
        ->get();

        return view('applications.employer-statistics', compact(
            'totalApplications',
            'applicationsByStatus',
            'applicationsByJob'
        ));
    }

    /**
     * Check if user can apply for a job
     */
    public function canApply(JobPosting $job)
    {
        $user = auth()->user();
        $canApply = true;
        $reasons = [];

        // Check job availability
        if (!$job->is_active) {
            $canApply = false;
            $reasons[] = 'This job posting is no longer available.';
        }

        if ($job->application_deadline && $job->application_deadline->isPast()) {
            $canApply = false;
            $reasons[] = 'The application deadline for this job has passed.';
        }

        // Check user eligibility
        if (!$user->hasResume()) {
            $canApply = false;
            $reasons[] = 'Please upload your resume before applying.';
        }

        if (!$user->pwdProfile || empty($user->pwdProfile->disability_type)) {
            $canApply = false;
            $reasons[] = 'Please complete your PWD profile before applying.';
        }

        // Check if already applied
        $existingApplication = JobApplication::where('user_id', $user->id)
            ->where('job_posting_id', $job->id)
            ->first();

        if ($existingApplication) {
            $canApply = false;
            $reasons[] = 'You have already applied for this job.';
        }

        return [
            'can_apply' => $canApply,
            'reasons' => $reasons,
            'missing_resume' => !$user->hasResume(),
            'incomplete_profile' => !$user->pwdProfile || empty($user->pwdProfile->disability_type),
        ];
    }

    /**
     * Export applications for employer
     */
    public function exportEmployerApplications(Request $request)
    {
        if (!auth()->user()->isEmployer()) {
            abort(403, 'Unauthorized action.');
        }

        $employerId = auth()->id();

        $applications = JobApplication::with(['user', 'user.pwdProfile', 'jobPosting'])
            ->whereHas('jobPosting', function($q) use ($employerId) {
                $q->where('created_by', $employerId);
            })
            ->when($request->status, function($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->job_posting_id, function($query, $jobPostingId) {
                $query->where('job_posting_id', $jobPostingId);
            })
            ->latest()
            ->get();

        // In a real implementation, you would generate CSV or Excel file here
        // For now, we'll just return a success message

        return redirect()->back()->with('success', 'Export functionality will be implemented soon. ' . $applications->count() . ' applications found for export.');
    }
}
