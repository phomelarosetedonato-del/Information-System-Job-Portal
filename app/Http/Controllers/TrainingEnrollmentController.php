<?php

namespace App\Http\Controllers;

use App\Models\TrainingEnrollment;
use App\Models\SkillTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainingEnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Auth::user()->trainingEnrollments()
            ->with('skillTraining')
            ->latest()
            ->paginate(10);

        return view('enrollments.index', compact('enrollments'));
    }

    public function show(TrainingEnrollment $enrollment)
    {
        // Ensure user can only view their own enrollments unless admin
        if (Auth::user()->role !== 'admin' && $enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        $enrollment->load('skillTraining');

        return view('enrollments.show', compact('enrollment'));
    }

    /**
     * Store a new enrollment (used by the enrollments.store route)
     */
    public function store(Request $request)
    {
        $request->validate([
            'skill_training_id' => 'required|exists:skill_trainings,id',
            'notes' => 'nullable|string|max:500'
        ]);

        $training = SkillTraining::where('is_active', true)->findOrFail($request->skill_training_id);

        // Check if user is PWD
        if (!Auth::user()->isPwd()) {
            return redirect()->back()->with('error', 'Only PWD users can enroll in trainings.');
        }

        // Check if training is active
        if (!$training->is_active) {
            return redirect()->back()->with('error', 'This training is no longer available for enrollment.');
        }

        // Check if training has reached maximum participants
        $currentEnrollments = $training->enrollments()->whereIn('status', ['pending', 'approved'])->count();
        if ($currentEnrollments >= $training->max_participants) {
            return redirect()->back()->with('error', 'This training has reached maximum participants.');
        }

        // Check if training hasn't ended
        if ($training->end_date->isPast()) {
            return redirect()->back()->with('error', 'This training has already ended.');
        }

        // Check if training hasn't started
        if ($training->start_date->isPast()) {
            return redirect()->back()->with('error', 'This training has already started.');
        }

        // Check if user already enrolled
        $existingEnrollment = TrainingEnrollment::where('user_id', Auth::id())
            ->where('skill_training_id', $training->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You have already enrolled in this training.');
        }

        // Create enrollment - REMOVED enrolled_at field
        $enrollment = TrainingEnrollment::create([
            'user_id' => Auth::id(),
            'skill_training_id' => $training->id,
            'status' => 'pending',
            'notes' => $request->notes,
            // REMOVED: 'enrolled_at' => now()
        ]);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment submitted successfully! You will be notified once it\'s reviewed.');
    }

    /**
     * Alternative enroll method (if you want to keep both)
     */
    public function enroll(SkillTraining $training)
    {
        // Check if user is PWD
        if (!Auth::user()->isPwd()) {
            return redirect()->back()->with('error', 'Only PWD users can enroll in trainings.');
        }

        // Check if training is active
        if (!$training->is_active) {
            return redirect()->back()->with('error', 'This training is no longer available for enrollment.');
        }

        // Check if training has reached maximum participants
        $currentEnrollments = $training->enrollments()->whereIn('status', ['pending', 'approved'])->count();
        if ($currentEnrollments >= $training->max_participants) {
            return redirect()->back()->with('error', 'This training has reached maximum participants.');
        }

        // Check if training hasn't ended
        if ($training->end_date->isPast()) {
            return redirect()->back()->with('error', 'This training has already ended.');
        }

        // Check if training hasn't started
        if ($training->start_date->isPast()) {
            return redirect()->back()->with('error', 'This training has already started.');
        }

        // Check if user already enrolled
        $existingEnrollment = TrainingEnrollment::where('user_id', Auth::id())
            ->where('skill_training_id', $training->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You have already enrolled in this training.');
        }

        // Create enrollment - REMOVED enrolled_at field
        $enrollment = TrainingEnrollment::create([
            'user_id' => Auth::id(),
            'skill_training_id' => $training->id,
            'status' => 'pending',
            'notes' => request('notes', ''),
            // REMOVED: 'enrolled_at' => now()
        ]);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment submitted successfully! You will be notified once it\'s reviewed.');
    }

    /**
     * Cancel enrollment (withdraw)
     */
    public function cancel(TrainingEnrollment $enrollment)
    {
        // Ensure user can only cancel their own enrollments
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if enrollment can be cancelled (only pending enrollments can be cancelled)
        if ($enrollment->status !== 'pending') {
            return redirect()->back()->with('error', 'You cannot cancel this enrollment. The training may have already started or your enrollment status cannot be changed.');
        }

        // Check if training hasn't started yet
        if ($enrollment->skillTraining->start_date->isPast()) {
            return redirect()->back()->with('error', 'You cannot cancel this enrollment because the training has already started.');
        }

        // Update enrollment status to cancelled
        $enrollment->update(['status' => 'cancelled']);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment cancelled successfully.');
    }

    /**
     * Destroy enrollment (permanent delete)
     */
    public function destroy(TrainingEnrollment $enrollment)
    {
        // Ensure user can only delete their own enrollments unless admin
        if (Auth::user()->role !== 'admin' && $enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deletion of cancelled or rejected enrollments
        if (!in_array($enrollment->status, ['cancelled', 'rejected'])) {
            return redirect()->back()->with('error', 'You can only delete cancelled or rejected enrollments.');
        }

        $enrollment->delete();

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }

    /**
     * Admin: Update enrollment status
     */
    public function updateStatus(Request $request, TrainingEnrollment $enrollment)
    {
        // Only admin can update status
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $oldStatus = $enrollment->status;

        $enrollment->update([
            'status' => $request->status,
            'notes' => $request->notes ?: $enrollment->notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Enrollment status updated successfully.');
    }

    /**
     * Admin: Display all enrollments
     */
    public function adminIndex(Request $request)
    {
        // Only admin can access
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $query = TrainingEnrollment::with(['user', 'skillTraining']);

        // Add filters if needed
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->latest()->paginate(20);

        return view('admin.enrollments.index', compact('enrollments'));
    }
}
