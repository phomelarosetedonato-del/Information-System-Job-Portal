<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\JobApplication;
use App\Models\TrainingEnrollment;
use App\Models\PwdProfile;
use App\Models\JobPosting;
use App\Models\SkillTraining;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Record admin activity
        auth()->user()->recordAdminAction();

        $stats = [
            'total_users' => User::count(),
            'pwd_users' => User::pwdUsers()->count(),
            'admin_users' => User::admins()->count(),
            'regular_users' => User::regularUsers()->count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'approved_applications' => JobApplication::where('status', 'approved')->count(),
            'active_trainings' => TrainingEnrollment::where('status', 'enrolled')->count(),
            'total_jobs' => JobPosting::count(),
            'total_trainings' => SkillTraining::count(),
            'locked_users' => User::locked()->count(),
            'users_needing_security' => User::needsSecurityAttention()->count(),

            // Fixed the typos in these lines:
            'job_postings' => JobPosting::count(),
            'active_jobs' => JobPosting::where('status', 'active')->count(),
            'skill_trainings' => SkillTraining::count(),
            'active_trainings_count' => SkillTraining::where('status', 'active')->count(),
            'total_documents' => 0, // You can add document count logic if needed
        ];

        // Get recent activity
        $recentApplications = JobApplication::with(['user', 'jobPosting'])
            ->latest()
            ->take(5)
            ->get();

        $recentEnrollments = TrainingEnrollment::with(['user', 'skillTraining'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::with('pwdProfile')
            ->latest()
            ->take(5)
            ->get();

        // Security alerts
        $securityAlerts = User::needsSecurityAttention()
            ->withCount(['jobApplications', 'trainingEnrollments'])
            ->take(5)
            ->get();

        return view('dashboard.admin', compact(
            'stats',
            'recentApplications',
            'recentEnrollments',
            'recentUsers',
            'securityAlerts'
        ));
    }

    public function users(Request $request)
    {
        auth()->user()->recordAdminAction();

        $query = User::with('pwdProfile');

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->byRole($request->role);
        }

        // Filter by activity
        if ($request->has('activity') && $request->activity) {
            if ($request->activity === 'active') {
                $query->active();
            } elseif ($request->activity === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->activity === 'locked') {
                $query->locked();
            }
        }

        // Filter by security status
        if ($request->has('security') && $request->security) {
            if ($request->security === 'needs_attention') {
                $query->needsSecurityAttention();
            } elseif ($request->security === 'strong') {
                $query->where('password_meets_current_standards', true)
                      ->whereNotNull('two_factor_secret');
            }
        }

        $users = $query->latest()->paginate(10);
        $roles = User::getAvailableRoles();

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function userShow(User $user)
    {
        auth()->user()->recordAdminAction();

        $user->load('pwdProfile', 'jobApplications.jobPosting', 'trainingEnrollments.skillTraining', 'documents');

        $loginStats = $user->getLoginStats();
        $securityOverview = $user->getSecurityOverview();
        $applicationStats = $user->getApplicationStats();
        $trainingStats = $user->getTrainingStats();

        return view('admin.users.show', compact(
            'user',
            'loginStats',
            'securityOverview',
            'applicationStats',
            'trainingStats'
        ));
    }

    public function activateUser(User $user)
    {
        $user->activate();

        return redirect()->back()->with('success', 'User account activated successfully.');
    }

    public function deactivateUser(User $user)
    {
        $user->deactivate();

        return redirect()->back()->with('success', 'User account deactivated successfully.');
    }

    public function unlockUser(User $user)
    {
        $user->unlockAccount();

        return redirect()->back()->with('success', 'User account unlocked successfully.');
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,pwd,admin'
        ]);

        $user->update(['role' => $request->role]);

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function userSecurityReport()
    {
        auth()->user()->recordAdminAction();

        $securityStats = [
            'total_users' => User::count(),
            'users_with_strong_passwords' => User::where('password_meets_current_standards', true)->count(),
            'users_with_2fa' => User::whereNotNull('two_factor_secret')->count(),
            'locked_accounts' => User::locked()->count(),
            'expired_passwords' => User::withExpiredPassword()->count(),
            'high_risk_users' => User::needsSecurityAttention()->count(),
        ];

        $riskUsers = User::needsSecurityAttention()
            ->with('pwdProfile')
            ->get()
            ->map(function ($user) {
                return [
                    'user' => $user,
                    'security_score' => $user->security_score,
                    'recommendations' => $user->getSecurityRecommendations(),
                ];
            });

        return view('admin.users.security-report', compact('securityStats', 'riskUsers'));
    }

    public function systemStatistics()
    {
        auth()->user()->recordAdminAction();

        $userStats = User::getDashboardStatistics();
        $registrationTrends = User::getRegistrationTrends(30);

        // Application statistics
        $applicationStats = [
            'total' => JobApplication::count(),
            'pending' => JobApplication::where('status', 'pending')->count(),
            'approved' => JobApplication::where('status', 'approved')->count(),
            'rejected' => JobApplication::where('status', 'rejected')->count(),
        ];

        // Training statistics
        $trainingStats = [
            'total_enrollments' => TrainingEnrollment::count(),
            'enrolled' => TrainingEnrollment::where('status', 'enrolled')->count(),
            'completed' => TrainingEnrollment::where('status', 'completed')->count(),
            'cancelled' => TrainingEnrollment::where('status', 'cancelled')->count(),
        ];

        return view('admin.statistics', compact(
            'userStats',
            'registrationTrends',
            'applicationStats',
            'trainingStats'
        ));
    }

    // ----------------------------
    // Employer verification management
    // ----------------------------

    public function employerVerifications(Request $request)
    {
        auth()->user()->recordAdminAction();

        $query = User::where('role', 'employer')
                     ->with('pwdProfile');

        if ($request->has('status') && $request->status) {
            $query->where('employer_verification_status', $request->status);
        }

        $employers = $query->latest()->paginate(20);

        return view('admin.employers.index', compact('employers'));
    }

    public function pendingEmployerVerifications()
    {
        auth()->user()->recordAdminAction();

        $employers = User::where('role', 'employer')
                         ->where('employer_verification_status', 'pending')
                         ->with('pwdProfile')
                         ->latest()
                         ->paginate(20);

        return view('admin.employers.pending', compact('employers'));
    }

    public function reviewEmployerVerification(User $employer)
    {
        auth()->user()->recordAdminAction();

        $employer->load('pwdProfile', 'documents');

        return view('admin.employers.review', compact('employer'));
    }

    public function approveEmployerVerification(Request $request, User $employer)
    {
        auth()->user()->recordAdminAction();

        $note = $request->input('admin_note');

        $employer->update([
            'employer_verification_status' => 'verified',
            'employer_verified_at' => now(),
            'verification_rejected_reason' => null,
            'verification_expires_at' => now()->addYear(),
        ]);

        // Notify employer
        $employer->notifyVerificationStatusChanged('approved', $note);

        return redirect()->back()->with('success', 'Employer verification approved and user notified.');
    }

    public function rejectEmployerVerification(Request $request, User $employer)
    {
        auth()->user()->recordAdminAction();

        $request->validate(['rejection_reason' => 'nullable|string|max:1000']);

        $reason = $request->input('rejection_reason');

        $employer->update([
            'employer_verification_status' => 'rejected',
            'verification_rejected_reason' => $reason,
            'can_resubmit_verification_at' => now()->addDays(7),
        ]);

        $employer->notifyVerificationStatusChanged('rejected', $reason);

        return redirect()->back()->with('success', 'Employer verification rejected and user notified.');
    }

    public function requestMoreInfo(Request $request, User $employer)
    {
        auth()->user()->recordAdminAction();

        $note = $request->input('note');

        // Keep current status but send note to employer
        $employer->update(['verification_notes' => $note]);
        $employer->notifyVerificationStatusChanged('kept', $note);

        return redirect()->back()->with('success', 'Requested more information from employer.');
    }

    public function viewEmployerDocuments(User $employer)
    {
        auth()->user()->recordAdminAction();

        $employer->load('documents');

        if (view()->exists('admin.employers.documents')) {
            return view('admin.employers.documents', compact('employer'));
        }

        // Fallback: return a simple JSON response when view is missing
        return response()->json(['documents' => $employer->documents]);
    }

    public function expiredEmployerVerifications()
    {
        auth()->user()->recordAdminAction();

        $employers = User::where('role', 'employer')
                         ->whereNotNull('verification_expires_at')
                         ->where('verification_expires_at', '<', now())
                         ->latest()
                         ->paginate(20);

        return view('admin.employers.expired', compact('employers'));
    }

    public function renewEmployerVerification(Request $request, User $employer)
    {
        auth()->user()->recordAdminAction();

        $employer->update([
            'verification_expires_at' => now()->addYear(),
            'employer_verification_status' => 'verified'
        ]);

        $employer->notifyVerificationStatusChanged('approved', 'Your verification has been renewed.');

        return redirect()->back()->with('success', 'Employer verification renewed and user notified.');
    }
}
