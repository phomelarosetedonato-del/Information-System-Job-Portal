<?php

namespace App\Http\Controllers;

use App\Models\SkillTraining;
use App\Models\TrainingEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillTrainingController extends Controller
{
    // =========================================================================
    // ADMIN MANAGEMENT METHODS
    // =========================================================================

    /**
     * Display a listing of skill trainings (Admin Management View)
     */
    public function index()
    {
        // Check if user is admin
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('skill-trainings.public.index')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $skillTrainings = SkillTraining::with(['creator', 'enrollments'])->latest()->paginate(10);

        // Enhanced statistics for admin
        $totalTrainings = SkillTraining::count();
        $activeTrainings = SkillTraining::where('is_active', true)->count();
        $upcomingTrainings = SkillTraining::where('is_active', true)
            ->where('start_date', '>', now())
            ->count();
        $totalEnrollments = TrainingEnrollment::count();
        $pendingEnrollments = TrainingEnrollment::where('status', 'pending')->count();

        return view('skill-trainings.admin.index', compact(
            'skillTrainings',
            'totalTrainings',
            'activeTrainings',
            'upcomingTrainings',
            'totalEnrollments',
            'pendingEnrollments'
        ));
    }

    /**
     * Show the form for creating a new skill training (Admin only)
     */
    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('skill-trainings.public.index')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        return view('skill-trainings.admin.create');
    }

    /**
     * Store a newly created skill training (Admin only)
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('skill-trainings.public.index')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'objectives' => 'required|string',
            'trainer' => 'required|string|max:255',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        SkillTraining::create($validated);

        return redirect()->route('admin.skill-trainings.index')->with('success', 'Skill training created successfully.');
    }

    /**
     * Display the specified skill training (Admin detailed view)
     */
    public function show(SkillTraining $skillTraining)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('skill-trainings.public.show', $skillTraining)
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $skillTraining->load(['creator', 'enrollments.user.pwdProfile']);

        return view('skill-trainings.admin.show', compact('skillTraining'));
    }

    /**
     * Show the form for editing the specified skill training (Admin only)
     */
    public function edit(SkillTraining $skillTraining)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('skill-trainings.public.index')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        return view('skill-trainings.admin.edit', compact('skillTraining'));
    }

    /**
     * Update the specified skill training (Admin only)
     */
    public function update(Request $request, SkillTraining $skillTraining)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('skill-trainings.public.index')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'objectives' => 'required|string',
            'trainer' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $skillTraining->update($validated);

        return redirect()->route('admin.skill-trainings.index')->with('success', 'Skill training updated successfully.');
    }

    /**
     * Remove the specified skill training (Admin only)
     */
    public function destroy(SkillTraining $skillTraining)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('skill-trainings.public.index')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $skillTraining->delete();
        return redirect()->route('admin.skill-trainings.index')->with('success', 'Skill training deleted successfully.');
    }

    /**
     * Toggle skill training active status (Admin only)
     */
    public function toggleStatus(SkillTraining $skillTraining)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Access denied. Admin privileges required.');
        }

        $skillTraining->update([
            'is_active' => !$skillTraining->is_active
        ]);

        $status = $skillTraining->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Skill training {$status} successfully.");
    }

    /**
     * View enrollments for a specific training (Admin only)
     */
    public function enrollments(SkillTraining $skillTraining)
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('skill-trainings.public.index')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        // Load enrollments with user relationship and PWD profile, and paginate
        $enrollments = $skillTraining->enrollments()
            ->with(['user.pwdProfile']) // Load user and their PWD profile
            ->latest()
            ->paginate(10);

        // Get enrollment count and statistics for the training
        $skillTraining->loadCount('enrollments');

        // Calculate status counts for statistics
        $statusCounts = [
            'pending' => $skillTraining->enrollments()->where('status', 'pending')->count(),
            'approved' => $skillTraining->enrollments()->where('status', 'approved')->count(),
            'rejected' => $skillTraining->enrollments()->where('status', 'rejected')->count(),
        ];

        return view('skill-trainings.admin.enrollments', compact(
            'skillTraining',
            'enrollments',
            'statusCounts'
        ));
    }

    // =========================================================================
    // PUBLIC/PWD USER METHODS
    // =========================================================================

    /**
     * Display a listing of skill trainings for public view (PWD Users)
     */
    public function publicIndex(Request $request)
    {
        $query = SkillTraining::where('is_active', true)
            ->withCount('enrollments')
            ->with(['enrollments' => function($query) {
                // FIXED: Use auth()->user()->id instead of auth()->user->id
                $query->where('user_id', auth()->id());
            }]);

        // Apply filters
        if ($request->has('status') && $request->status) {
            if ($request->status == 'active') {
                $query->where('start_date', '<=', now())
                      ->where('end_date', '>=', now());
            } elseif ($request->status == 'upcoming') {
                $query->where('start_date', '>', now());
            } elseif ($request->status == 'completed') {
                $query->where('end_date', '<', now());
            }
        }

        if ($request->has('location') && $request->location) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        if ($request->has('trainer') && $request->trainer) {
            $query->where('trainer', 'like', '%' . $request->trainer . '%');
        }

        $skillTrainings = $query->orderBy('start_date')->paginate(9);

        // Statistics for public view - match the names used in your view
        $stats = [
            'totalTrainings' => SkillTraining::where('is_active', true)->count(),
            'activeTrainings' => SkillTraining::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
            'upcomingTrainings' => SkillTraining::where('is_active', true)
                ->where('start_date', '>', now())
                ->count(),
            // FIXED: Use auth()->check() instead of auth->check
            'userEnrollments' => auth()->check() ? auth()->user()->trainingEnrollments()->count() : 0,
        ];

        return view('skill-trainings.public.index', array_merge($stats, [
            'skillTrainings' => $skillTrainings
        ]));
    }

    /**
     * Display the specified skill training for public view
     */
    public function publicShow(SkillTraining $skillTraining)
    {
        // Check if skill training is active
        if (!$skillTraining->is_active && !Auth::user()->isAdmin()) {
            abort(404, 'This training is no longer available.');
        }

        // Load enrollment count and user's enrollment
        $skillTraining->loadCount('enrollments');

        $userEnrollment = null;
        // FIXED: Use auth()->check() instead of auth->check
        if (auth()->check()) {
            $userEnrollment = $skillTraining->enrollments()
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('skill-trainings.public.show', compact('skillTraining', 'userEnrollment'));
    }

    /**
     * Enroll in a training (PWD Users only)
     */
    public function enroll(Request $request, SkillTraining $skillTraining)
    {
        if (!Auth::user()->isPwd()) {
            return redirect()->back()->with('error', 'Only PWD users can enroll in trainings.');
        }

        // Check if user is already enrolled
        $existingEnrollment = TrainingEnrollment::where('user_id', Auth::id())
            ->where('skill_training_id', $skillTraining->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You are already enrolled in this training.');
        }

        // Check if training is active and has available slots
        if (!$skillTraining->is_active) {
            return redirect()->back()->with('error', 'This training is not active.');
        }

        // Check if training is full using actual count instead of is_full attribute
        $currentEnrollments = $skillTraining->enrollments()->whereIn('status', ['pending', 'approved'])->count();
        if ($currentEnrollments >= $skillTraining->max_participants) {
            return redirect()->back()->with('error', 'This training is full.');
        }

        // Check if training hasn't started
        if ($skillTraining->start_date < now()) {
            return redirect()->back()->with('error', 'This training has already started.');
        }

        // Create enrollment - REMOVED enrolled_at field
        TrainingEnrollment::create([
            'user_id' => Auth::id(),
            'skill_training_id' => $skillTraining->id,
            'status' => 'pending',
            'notes' => $request->notes,
            // REMOVED: 'enrolled_at' => now()
        ]);

        return redirect()->back()->with('success', 'Successfully enrolled in the training. Please wait for approval.');
    }

    /**
     * Get training status for display
     */
    public function getTrainingStatus(SkillTraining $skillTraining)
    {
        if ($skillTraining->start_date->isFuture()) {
            return 'upcoming';
        } elseif ($skillTraining->end_date->isPast()) {
            return 'completed';
        } else {
            return 'ongoing';
        }
    }

    /**
     * Check if training can accept enrollments
     */
    public function canEnroll(SkillTraining $skillTraining)
    {
        if (!$skillTraining->is_active) {
            return false;
        }

        if ($skillTraining->start_date->isPast()) {
            return false;
        }

        $currentEnrollments = $skillTraining->enrollments()->whereIn('status', ['pending', 'approved'])->count();
        if ($currentEnrollments >= $skillTraining->max_participants) {
            return false;
        }

        return true;
    }
}
