<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\User;
use App\Notifications\JobApplicationStatusUpdated;
use App\Notifications\NewJobApplicationReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobApplicationController extends Controller
{
    /**
     * Apply for a job posting
     */
    public function apply(JobPosting $job)
    {
        // Check if job is active and open
        if (!$job->is_active) {
            return redirect()->back()->with('error', 'This job posting is no longer available.');
        }

        if ($job->application_deadline && $job->application_deadline->isPast()) {
            return redirect()->back()->with('error', 'The application deadline for this job has passed.');
        }

        // Check if user already applied
        $existingApplication = JobApplication::where('user_id', auth()->id())
            ->where('job_posting_id', $job->id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }

        // Check if user has completed profile
        $user = auth()->user();
        if (!$user->pwdProfile || empty($user->pwdProfile->disability_type)) {
            return redirect()->route('profile.pwd-complete-form')
                ->with('warning', 'Please complete your PWD profile before applying for jobs.');
        }

        // Create application
        $application = JobApplication::create([
            'user_id' => auth()->id(),
            'job_posting_id' => $job->id,
            'status' => 'pending',
            'cover_letter' => request('cover_letter', ''),
        ]);

        // 🔔 NOTIFY ADMINS ABOUT NEW APPLICATION
        $adminUsers = User::where('role', 'admin')->get();
        foreach ($adminUsers as $admin) {
            $admin->notify(new NewJobApplicationReceived($application));
        }

        return redirect()->route('applications.index')
            ->with('success', 'Job application submitted successfully! The employer will review your application.');
    }

    /**
     * Display user's job applications
     */
    public function index(Request $request)
    {
        // FIXED: Use the correct approach for including soft-deleted job postings
        $query = auth()->user()->jobApplications()
            ->with(['jobPosting' => function($query) {
                // Use the query builder directly to include soft-deleted records
                if (method_exists($query->getRelated(), 'bootSoftDeletes')) {
                    $query->withTrashed();
                }
            }]);

        // Alternative approach if the above doesn't work:
        // $query = JobApplication::where('user_id', auth()->id())
        //     ->with(['jobPosting' => function($query) {
        //         $query->withTrashed();
        //     }]);

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(10);

        $stats = [
            'total' => auth()->user()->jobApplications()->count(),
            'pending' => auth()->user()->jobApplications()->where('status', 'pending')->count(),
            'shortlisted' => auth()->user()->jobApplications()->where('status', 'shortlisted')->count(),
            'approved' => auth()->user()->jobApplications()->where('status', 'approved')->count(),
            'rejected' => auth()->user()->jobApplications()->where('status', 'rejected')->count(),
        ];

        return view('applications.index', compact('applications', 'stats'));
    }

    /**
     * Display specific application details
     */
    public function show(JobApplication $application)
    {
        // Ensure the application belongs to the current user or user is admin
        if ($application->user_id !== auth()->id() && auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // FIXED: Load job posting with trashed records
        $application->load(['jobPosting' => function($query) {
            if (method_exists($query->getRelated(), 'bootSoftDeletes')) {
                $query->withTrashed();
            }
        }, 'user.pwdProfile']);

        return view('applications.show', compact('application'));
    }

    /**
     * Update application status (Admin only)
     */
    public function updateStatus(JobApplication $application, Request $request)
    {
        // Only admin can update status
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:pending,shortlisted,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $oldStatus = $application->status;

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // 🔔 NOTIFY USER ABOUT STATUS CHANGE
        if ($oldStatus !== $request->status) {
            $application->user->notify(new JobApplicationStatusUpdated($application, $request->status));
        }

        return redirect()->back()->with('success', 'Application status updated successfully.');
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

        // Only allow withdrawal if status is pending
        if ($application->status !== 'pending') {
            return redirect()->back()->with('error', 'You can only withdraw applications that are pending.');
        }

        $application->update([
            'status' => 'withdrawn',
            'withdrawn_at' => now()
        ]);

        return redirect()->route('applications.index')
            ->with('success', 'Application withdrawn successfully.');
    }

    /**
     * Admin index for managing all applications
     */
    public function adminIndex(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // FIXED: Include soft-deleted job postings in admin view
        $query = JobApplication::with(['user', 'user.pwdProfile', 'jobPosting' => function($query) {
            if (method_exists($query->getRelated(), 'bootSoftDeletes')) {
                $query->withTrashed();
            }
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
        ];

        return view('applications.admin-index', compact('applications', 'stats'));
    }

    /**
     * Bulk update application statuses
     */
    public function bulkUpdate(Request $request)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'action' => 'required|in:shortlist,approve,reject',
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id'
        ]);

        $applicationIds = $request->application_ids;
        $action = $request->action;

        switch ($action) {
            case 'shortlist':
                $status = 'shortlisted';
                $message = 'Applications shortlisted successfully.';
                break;
            case 'approve':
                $status = 'approved';
                $message = 'Applications approved successfully.';
                break;
            case 'reject':
                $status = 'rejected';
                $message = 'Applications rejected successfully.';
                break;
        }

        JobApplication::whereIn('id', $applicationIds)->update([
            'status' => $status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Notify users about status changes
        foreach (JobApplication::whereIn('id', $applicationIds)->get() as $application) {
            $application->user->notify(new JobApplicationStatusUpdated($application, $status));
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Application statistics for admin
     */
    public function statistics()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        $totalApplications = JobApplication::count();
        $applicationsThisMonth = JobApplication::whereMonth('created_at', now()->month)->count();
        $approvalRate = $totalApplications > 0 ?
            (JobApplication::where('status', 'approved')->count() / $totalApplications) * 100 : 0;

        $monthlyApplications = JobApplication::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subYear())
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

        $applicationsByStatus = JobApplication::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // FIXED: Include soft-deleted job postings in statistics
        $topJobs = JobPosting::withCount(['applications' => function($query) {
            if (method_exists($query->getRelated(), 'bootSoftDeletes')) {
                $query->withTrashed();
            }
        }])
        ->orderBy('applications_count', 'desc')
        ->limit(10)
        ->get();

        return view('applications.statistics', compact(
            'totalApplications',
            'applicationsThisMonth',
            'approvalRate',
            'monthlyApplications',
            'applicationsByStatus',
            'topJobs'
        ));
    }
}
