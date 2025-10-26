<?php

namespace App\Http\Controllers;

use App\Models\SkillTraining;
use App\Models\TrainingEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillTrainingController extends Controller
{
    /**
     * Display a listing of skill trainings (Admin view)
     */
    public function index()
    {
        $skillTrainings = SkillTraining::latest()->get();
        return view('skill-trainings.index', compact('skillTrainings'));
    }

    /**
     * Display a listing of skill trainings for public view with filters and statistics
     */
    public function publicIndex(Request $request)
    {
        $query = SkillTraining::where('is_active', true)
            ->withCount('enrollments')
            ->with(['enrollments' => function($query) {
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

        // Statistics
        $stats = [
            'totalTrainings' => SkillTraining::where('is_active', true)->count(),
            'activeTrainings' => SkillTraining::where('is_active', true)
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->count(),
            'upcomingTrainings' => SkillTraining::where('is_active', true)
                ->where('start_date', '>', now())
                ->count(),
            'userEnrollments' => auth()->user()->trainingEnrollments()->count(),
        ];

        return view('skill-trainings.public-index', array_merge($stats, [
            'skillTrainings' => $skillTrainings
        ]));
    }

    /**
     * Show the form for creating a new skill training
     */
    public function create()
    {
        return view('skill-trainings.create');
    }

    /**
     * Store a newly created skill training
     */
    public function store(Request $request)
    {
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

        // Use Auth::id() instead of auth()->id() for better IDE support
        $validated['created_by'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        SkillTraining::create($validated);

        return redirect()->route('skill-trainings.index')->with('success', 'Skill training created successfully.');
    }

    /**
     * Display the specified skill training (Admin view)
     */
    public function show(SkillTraining $skillTraining)
    {
        return view('skill-trainings.show', compact('skillTraining'));
    }

    /**
     * Display the specified skill training for public view
     */
    public function publicShow(SkillTraining $skillTraining)
    {
        // Check if skill training is active
        if (!$skillTraining->is_active) {
            abort(404, 'This training is no longer available.');
        }

        // Load enrollment count and user's enrollment
        $skillTraining->loadCount('enrollments');
        $userEnrollment = $skillTraining->enrollments()
            ->where('user_id', auth()->id())
            ->first();

        return view('skill-trainings.public-show', compact('skillTraining', 'userEnrollment'));
    }

    /**
     * Show the form for editing the specified skill training
     */
    public function edit(SkillTraining $skillTraining)
    {
        return view('skill-trainings.edit', compact('skillTraining'));
    }

    /**
     * Update the specified skill training
     */
    public function update(Request $request, SkillTraining $skillTraining)
    {
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

        return redirect()->route('skill-trainings.index')->with('success', 'Skill training updated successfully.');
    }

    /**
     * Remove the specified skill training
     */
    public function destroy(SkillTraining $skillTraining)
    {
        $skillTraining->delete();
        return redirect()->route('skill-trainings.index')->with('success', 'Skill training deleted successfully.');
    }

    /**
     * Toggle skill training active status
     */
    public function toggleStatus(SkillTraining $skillTraining)
    {
        $skillTraining->update([
            'is_active' => !$skillTraining->is_active
        ]);

        $status = $skillTraining->is_active ? 'activated' : 'deactivated';

        return redirect()->back()->with('success', "Skill training {$status} successfully.");
    }
}
