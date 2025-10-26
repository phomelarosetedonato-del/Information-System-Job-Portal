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
            'active_trainings' => TrainingEnrollment::where('status', 'enrolled')->count(),
            'total_jobs' => JobPosting::count(),
            'total_trainings' => SkillTraining::count(),
            'locked_users' => User::locked()->count(),
            'users_needing_security' => User::needsSecurityAttention()->count(),
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

        return view('admin.dashboard', compact(
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
}
