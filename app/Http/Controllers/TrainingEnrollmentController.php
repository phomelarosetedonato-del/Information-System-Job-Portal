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

        return view('enrollments.show', compact('enrollment'));
    }

    public function enroll(SkillTraining $training)
    {
        // Check if training is active
        if (!$training->is_active) {
            return redirect()->back()->with('error', 'This training is no longer available for enrollment.');
        }

        // Check if training has reached maximum participants
        $currentEnrollments = $training->enrollments()->whereIn('status', ['pending', 'approved'])->count();
        if ($currentEnrollments >= $training->max_participants) {
            return redirect()->back()->with('error', 'This training has reached maximum participants.');
        }

        // Check if user already enrolled
        $existingEnrollment = TrainingEnrollment::where('user_id', Auth::id())
            ->where('skill_training_id', $training->id)
            ->first();

        if ($existingEnrollment) {
            return redirect()->back()->with('error', 'You have already enrolled in this training.');
        }

        // Create enrollment
        $enrollment = TrainingEnrollment::create([
            'user_id' => Auth::id(),
            'skill_training_id' => $training->id,
            'status' => 'pending',
            'notes' => request('notes', '')
        ]);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment submitted successfully! You will receive a confirmation email.');
    }

    // ADD THIS CANCEL METHOD:
    public function cancel(TrainingEnrollment $enrollment)
    {
        // Ensure user can only cancel their own enrollments
        if ($enrollment->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if enrollment can be cancelled
        if (!$enrollment->can_cancel) {
            return redirect()->back()->with('error', 'You cannot cancel this enrollment. The training may have already started or your enrollment status cannot be changed.');
        }

        // Update enrollment status to cancelled
        $enrollment->update(['status' => 'cancelled']);

        return redirect()->route('enrollments.index')
            ->with('success', 'Enrollment cancelled successfully.');
    }

    public function updateStatus(Request $request, TrainingEnrollment $enrollment)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed,cancelled',
            'notes' => 'nullable|string'
        ]);

        $oldStatus = $enrollment->status;

        $enrollment->update([
            'status' => $request->status,
            'notes' => $request->notes
        ]);

        return redirect()->back()->with('success', 'Enrollment status updated successfully.');
    }
}
