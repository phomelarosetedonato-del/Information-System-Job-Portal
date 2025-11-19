<?php

namespace App\Http\Controllers;

use App\Models\JobPosting;
use App\Models\DisabilityType;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class JobPostingController extends Controller
{
    /**
     * Display a listing of job postings (Admin view)
     */
    public function index(Request $request)
    {
        // Authorization check
        if (!Gate::allows('view-admin-panel')) {
            abort(403, 'Unauthorized action.');
        }

        $query = JobPosting::with(['creator', 'applications']);

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('company', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('application_deadline', '<', now());
            }
        }

        $jobPostings = $query->latest()->paginate(15);

        return view('admin.job-postings.index', compact('jobPostings'));
    }

    /**
     * Display a listing of job postings for public view with simplified filtration
     */
    public function publicIndex(Request $request)
    {
        $query = JobPosting::with(['suitableDisabilityTypes'])
            ->where('is_active', true)
            ->where(function($query) {
                $query->where('application_deadline', '>=', now())
                      ->orWhereNull('application_deadline');
            });

        // ========== SIMPLIFIED FILTERS ==========
        $filters = $request->validate([
            'q' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'employment_type' => 'nullable|string|max:255',
            'disability_type_id' => 'nullable|integer|exists:disability_types,id',
            'sort_by' => 'nullable|string|in:newest,deadline',
        ]);

        // ========== SEARCH FILTER ==========
        if (!empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('company', 'like', '%' . $search . '%')
                  ->orWhere('location', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // ========== LOCATION FILTER ==========
        if (!empty($filters['location'])) {
            $query->where('location', 'like', '%' . $filters['location'] . '%');
        }

        // ========== EMPLOYMENT TYPE FILTER ==========
        if (!empty($filters['employment_type'])) {
            $query->where('employment_type', $filters['employment_type']);
        }

        // ========== FILTER BY SUITABLE DISABILITY TYPES ==========
        if (!empty($filters['disability_type_id'])) {
            $query->whereHas('suitableDisabilityTypes', function($q) use ($filters) {
                $q->where('disability_types.id', $filters['disability_type_id']);
            });
        }

        // ========== SIMPLIFIED SORTING ==========
        switch ($filters['sort_by'] ?? 'newest') {
            case 'deadline':
                $query->orderByRaw('application_deadline IS NULL')
                      ->orderBy('application_deadline', 'asc')
                      ->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Get paginated results
        $jobPostings = $query->paginate(12)->withQueryString();

        $disabilityTypes = DisabilityType::orderBy('type')->get();

        // If request is AJAX, return only the job list partial (HTML fragment)
        if ($request->ajax()) {
            return view('job-postings.partials.list', compact('jobPostings'));
        }

        return view('job-postings.public-index', compact('jobPostings', 'disabilityTypes', 'filters'));
    }

    /**
     * Show the form for creating a new job posting
     */
    public function create()
    {
        if (!Gate::allows('create-job-postings')) {
            abort(403, 'Unauthorized action.');
        }

        $employmentTypes = [
            'Full-time' => 'Full-time',
            'Part-time' => 'Part-time',
            'Contract' => 'Contract',
            'Temporary' => 'Temporary',
            'Internship' => 'Internship',
            'Freelance' => 'Freelance'
        ];

        $experienceLevels = [
            'Entry Level' => 'Entry Level',
            'Mid Level' => 'Mid Level',
            'Senior Level' => 'Senior Level',
            'Executive' => 'Executive',
            'Not Specified' => 'Not Specified'
        ];

        $jobCategories = [
            'IT & Software' => 'IT & Software',
            'Healthcare' => 'Healthcare',
            'Education' => 'Education',
            'Sales & Marketing' => 'Sales & Marketing',
            'Administrative' => 'Administrative',
            'Customer Service' => 'Customer Service',
            'Manufacturing' => 'Manufacturing',
            'Retail' => 'Retail',
            'Hospitality' => 'Hospitality',
            'General' => 'General'
        ];

        $disabilityTypes = DisabilityType::orderBy('type')->get();

        return view('admin.job-postings.create', compact('employmentTypes', 'experienceLevels', 'jobCategories', 'disabilityTypes'));
    }

    /**
     * Store a newly created job posting
     */
    public function store(Request $request)
    {
        if (!Gate::allows('create-job-postings')) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $this->validateJobPosting($request);

        // Convert empty deadline to null
        if (empty($validated['application_deadline'])) {
            $validated['application_deadline'] = null;
        }
        // Safely get authenticated user's ID; use optional() to avoid calling methods on unexpected types
        $validated['created_by'] = optional(Auth::user())->id;
        $validated['is_active'] = $request->has('is_active');
        $validated['is_active'] = $request->has('is_active');

        // Set default values if not provided
        $validated['views'] = 0;
        $validated['job_category'] = $validated['job_category'] ?? 'General';
        $validated['experience_level'] = $validated['experience_level'] ?? 'Not Specified';

        $job = JobPosting::create($validated);

        // Sync suitable disability types pivot table
        $job->suitableDisabilityTypes()->sync($request->input('disability_type_ids', []));

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'Job posting created successfully.');
    }

    /**
     * Display the specified job posting (Admin view)
     */
    public function show(JobPosting $jobPosting)
    {
        if (!Gate::allows('view-job-posting', $jobPosting)) {
            abort(403, 'Unauthorized action.');
        }

        $jobPosting->load(['creator', 'applications.user']);

        $applicationsByStatus = $jobPosting->applications()
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.job-postings.show', compact('jobPosting', 'applicationsByStatus'));
    }

    /**
     * Display the specified job posting for public view
     */
    public function publicShow(JobPosting $jobPosting)
    {
        // Check if job posting is active and deadline hasn't passed
        if (!$jobPosting->is_active) {
            abort(404, 'This job posting is no longer available.');
        }

        if ($jobPosting->application_deadline && $jobPosting->application_deadline->isPast()) {
            abort(404, 'The application deadline for this job has passed.');
        }

        // Increment views count
        $jobPosting->increment('views');

        // Get related jobs
        $relatedJobs = JobPosting::where('is_active', true)
            ->where('id', '!=', $jobPosting->id)
            ->where(function($query) use ($jobPosting) {
                $query->where('company', $jobPosting->company)
                      ->orWhere('employment_type', $jobPosting->employment_type)
                      ->orWhere('location', 'like', '%' . $jobPosting->location . '%')
                      ->orWhere('job_category', $jobPosting->job_category);
            })
            ->where(function($query) {
                $query->where('application_deadline', '>=', now())
                      ->orWhereNull('application_deadline');
            })
            ->limit(4)
            ->get();

        return view('job-postings.public-show', compact('jobPosting', 'relatedJobs'));
    }

    /**
     * Show the form for editing the specified job posting
     */
    public function edit(JobPosting $jobPosting)
    {
        if (!Gate::allows('update-job-posting', $jobPosting)) {
            abort(403, 'Unauthorized action.');
        }

        $employmentTypes = [
            'Full-time' => 'Full-time',
            'Part-time' => 'Part-time',
            'Contract' => 'Contract',
            'Temporary' => 'Temporary',
            'Internship' => 'Internship',
            'Freelance' => 'Freelance'
        ];

        $experienceLevels = [
            'Entry Level' => 'Entry Level',
            'Mid Level' => 'Mid Level',
            'Senior Level' => 'Senior Level',
            'Executive' => 'Executive',
            'Not Specified' => 'Not Specified'
        ];

        $jobCategories = [
            'IT & Software' => 'IT & Software',
            'Healthcare' => 'Healthcare',
            'Education' => 'Education',
            'Sales & Marketing' => 'Sales & Marketing',
            'Administrative' => 'Administrative',
            'Customer Service' => 'Customer Service',
            'Manufacturing' => 'Manufacturing',
            'Retail' => 'Retail',
            'Hospitality' => 'Hospitality',
            'General' => 'General'
        ];

        $disabilityTypes = DisabilityType::orderBy('type')->get();

        return view('admin.job-postings.edit', compact('jobPosting', 'employmentTypes', 'experienceLevels', 'jobCategories', 'disabilityTypes'));
    }

    /**
     * Update the specified job posting
     */
    public function update(Request $request, JobPosting $jobPosting)
    {
        if (!Gate::allows('update-job-posting', $jobPosting)) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $this->validateJobPosting($request, $jobPosting);

        // Convert empty deadline to null
        if (empty($validated['application_deadline'])) {
            $validated['application_deadline'] = null;
        }

        $validated['is_active'] = $request->has('is_active');

        $jobPosting->update($validated);

        // Sync disability types
        $jobPosting->suitableDisabilityTypes()->sync($request->input('disability_type_ids', []));

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'Job posting updated successfully.');
    }

    /**
     * Remove the specified job posting
     */
    public function destroy(JobPosting $jobPosting)
    {
        if (!Gate::allows('delete-job-posting', $jobPosting)) {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated applications first
        $jobPosting->applications()->delete();

        $jobPosting->delete();

        return redirect()->route('admin.job-postings.index')
            ->with('success', 'Job posting deleted successfully.');
    }

    /**
     * Validate job posting data
     */
    private function validateJobPosting(Request $request, $jobPosting = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50',
            'requirements' => 'required|string|min:50',
            'location' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'employment_type' => 'required|string|max:255',
            'application_deadline' => 'nullable|date|after_or_equal:today',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'job_category' => 'nullable|string|max:255',
            'experience_level' => 'nullable|string|max:255',
            'disability_type_ids' => 'nullable|array',
            'disability_type_ids.*' => 'integer|exists:disability_types,id',
        ];

        return $request->validate($rules);
    }

    /**
     * Toggle job posting active status
     */
    public function toggleStatus(JobPosting $jobPosting)
    {
        if (!Gate::allows('update-job-posting', $jobPosting)) {
            abort(403, 'Unauthorized action.');
        }

        $jobPosting->update([
            'is_active' => !$jobPosting->is_active
        ]);

        $status = $jobPosting->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Job posting {$status} successfully.");
    }

    /**
 * Display job posting analytics
 */
public function analytics()  // Changed from statistics() to analytics()
{
    if (!Gate::allows('view-admin-panel')) {
        abort(403, 'Unauthorized action.');
    }

    $totalJobs = JobPosting::count();
    $activeJobs = JobPosting::where('is_active', true)->count();
    $expiredJobs = JobPosting::where('application_deadline', '<', now())->count();
    $totalApplications = JobApplication::count();

    // Monthly job creation stats
    $monthlyStats = JobPosting::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('COUNT(*) as count')
        )
        ->where('created_at', '>=', now()->subYear())
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();

    // Top companies by job count
    $topCompanies = JobPosting::select('company', DB::raw('COUNT(*) as job_count'))
        ->groupBy('company')
        ->orderBy('job_count', 'desc')
        ->limit(10)
        ->get();

    // Job categories distribution
    $categoryDistribution = JobPosting::select('job_category', DB::raw('COUNT(*) as count'))
        ->whereNotNull('job_category')
        ->groupBy('job_category')
        ->orderBy('count', 'desc')
        ->get();

    return view('admin.job-postings.analytics', compact(
        'totalJobs',
        'activeJobs',
        'expiredJobs',
        'totalApplications',
        'monthlyStats',
        'topCompanies',
        'categoryDistribution'
    ));
}

    /**
     * Bulk actions for job postings
     */
    public function bulkAction(Request $request)
    {
        if (!Gate::allows('view-admin-panel')) {
            abort(403, 'Unauthorized action.');
        }

        $action = $request->input('action');
        $selectedIds = $request->input('selected_ids', []);

        if (empty($selectedIds)) {
            return redirect()->back()->with('error', 'No job postings selected.');
        }

        switch ($action) {
            case 'activate':
                JobPosting::whereIn('id', $selectedIds)->update(['is_active' => true]);
                $message = 'Selected job postings activated successfully.';
                break;

            case 'deactivate':
                JobPosting::whereIn('id', $selectedIds)->update(['is_active' => false]);
                $message = 'Selected job postings deactivated successfully.';
                break;

            case 'delete':
                // Delete associated applications first
                JobApplication::whereIn('job_posting_id', $selectedIds)->delete();
                JobPosting::whereIn('id', $selectedIds)->delete();
                $message = 'Selected job postings deleted successfully.';
                break;

            case 'extend_deadline':
                $newDeadline = $request->input('new_deadline');
                if ($newDeadline) {
                    JobPosting::whereIn('id', $selectedIds)->update([
                        'application_deadline' => $newDeadline
                    ]);
                    $message = 'Deadline extended for selected job postings.';
                } else {
                    return redirect()->back()->with('error', 'Please provide a new deadline date.');
                }
                break;

            default:
                return redirect()->back()->with('error', 'Invalid action selected.');
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Duplicate a job posting
     */
    public function duplicate(JobPosting $jobPosting)
    {
        if (!Gate::allows('create-job-postings')) {
            abort(403, 'Unauthorized action.');
        }

        $newJob = $jobPosting->replicate();
        $newJob->title = $jobPosting->title . ' (Copy)';
        $newJob->is_active = false;
        $newJob->views = 0;
        $newJob->created_at = now();
        $newJob->save();

        return redirect()->route('admin.job-postings.edit', $newJob->id)
            ->with('success', 'Job posting duplicated successfully. Please review and update the details.');
    }

    /**
     * Extend deadline for a job posting
     */
    public function extendDeadline(Request $request, JobPosting $jobPosting)
    {
        if (!Gate::allows('update-job-posting', $jobPosting)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'new_deadline' => 'required|date|after_or_equal:today'
        ]);

        $jobPosting->update([
            'application_deadline' => $request->new_deadline
        ]);

        return redirect()->back()->with('success', 'Application deadline extended successfully.');
    }

    /**
     * Export job postings
     */
    public function export(Request $request)
    {
        if (!Gate::allows('view-admin-panel')) {
            abort(403, 'Unauthorized action.');
        }

        $jobPostings = JobPosting::with(['creator', 'applications'])->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="job_postings_' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($jobPostings) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'ID', 'Title', 'Company', 'Location', 'Employment Type',
                'Application Deadline', 'Status', 'Views',
                'Applications', 'Created By', 'Created At'
            ]);

            // Add data rows
            foreach ($jobPostings as $job) {
                fputcsv($file, [
                    $job->id,
                    $job->title,
                    $job->company,
                    $job->location,
                    $job->employment_type,
                    $job->application_deadline ? $job->application_deadline->format('Y-m-d') : 'No deadline',
                    $job->is_active ? 'Active' : 'Inactive',
                    $job->views,
                    $job->applications->count(),
                    $job->creator->name,
                    $job->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get filter counts for AJAX requests
     */
    public function getFilterCounts(Request $request)
    {
        if (!Gate::allows('view-admin-panel')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $total = JobPosting::count();
        $active = JobPosting::where('is_active', true)->count();
        $expired = JobPosting::where('application_deadline', '<', now())->count();
        $withoutDeadline = JobPosting::whereNull('application_deadline')->count();

        return response()->json([
            'total' => $total,
            'active' => $active,
            'expired' => $expired,
            'without_deadline' => $withoutDeadline
        ]);
    }
}
