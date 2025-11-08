<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\SkillTraining;
use App\Models\Announcement;
use App\Models\User;
use App\Models\JobApplication;
use App\Models\TrainingEnrollment;
use App\Models\Document;
use Illuminate\Support\Facades\Log;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Security check
        if (!$user->isAdmin()) {
            Log::error('SECURITY: Non-admin user accessed admin dashboard!', [
                'user_id' => $user->id,
                'role' => $user->role
            ]);
            abort(403, 'Access denied.');
        }

        Log::info('Loading admin dashboard data', ['user_id' => $user->id]);

        // Get comprehensive statistics
        $stats = [
            'job_postings' => JobPosting::count(),
            'skill_trainings' => SkillTraining::count(),
            'active_announcements' => Announcement::where('is_active', true)->count(),
            'total_users' => User::count(),
            'pwd_users' => User::where('role', 'pwd')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'approved_applications' => JobApplication::where('status', 'approved')->count(),
            'active_trainings' => TrainingEnrollment::where('status', 'enrolled')->count(),
            'locked_users' => User::whereNotNull('account_locked_until')
                            ->where('account_locked_until', '>', now())
                            ->count(),
            'users_needing_security' => User::where(function($query) {
                $query->where('password_meets_current_standards', false)
                      ->orWhereNull('two_factor_secret')
                      ->orWhere('failed_login_attempts', '>=', 3)
                      ->orWhereNotNull('account_locked_until');
            })->count(),
            'total_documents' => Document::count(),
            'active_jobs' => JobPosting::where('is_active', true)->count(),
            'active_trainings_count' => SkillTraining::where('is_active', true)->count(),
        ];

        // Get recent applications
        $recentApplications = JobApplication::with(['user', 'jobPosting'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent enrollments
        $recentEnrollments = TrainingEnrollment::with(['user', 'skillTraining'])
            ->latest()
            ->take(5)
            ->get();

        // Get recent users
        $recentUsers = User::with('pwdProfile')
            ->latest()
            ->take(5)
            ->get();

        // Get security alerts
        $securityAlerts = User::where(function($query) {
                $query->where('password_meets_current_standards', false)
                      ->orWhereNull('two_factor_secret')
                      ->orWhere('failed_login_attempts', '>=', 3)
                      ->orWhereNotNull('account_locked_until');
            })
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
}
