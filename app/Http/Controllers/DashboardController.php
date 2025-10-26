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

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if (!$user) {
            Log::warning('Dashboard accessed without authenticated user');
            return redirect()->route('login');
        }

        // Detailed debugging
        Log::info('=== DASHBOARD ROLE DETECTION ===', [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_role' => $user->role,
            'isAdmin()' => $user->isAdmin() ? 'true' : 'false',
            'isPwd()' => $user->isPwd() ? 'true' : 'false',
            'hasPwdProfile()' => $user->hasPwdProfile() ? 'true' : 'false'
        ]);

        // Check if user has no role
        if (!$user->role) {
            Log::error('User has no role assigned!', ['user_id' => $user->id]);
            return $this->defaultDashboard();
        }

        // Check PWD first (since your logic checks PWD first)
        if ($user->isPwd()) {
            Log::info('ROUTE: Redirecting to PWD dashboard', ['user_id' => $user->id]);
            return $this->pwdDashboard();
        }

        // Then check Admin
        if ($user->isAdmin()) {
            Log::info('ROUTE: Redirecting to Admin dashboard', ['user_id' => $user->id]);
            return $this->adminDashboard();
        }

        // If we get here, the role exists but doesn't match 'admin' or 'pwd'
        Log::error('User has unrecognized role', [
            'user_id' => $user->id,
            'role' => $user->role,
            'expected' => ['admin', 'pwd']
        ]);

        return $this->defaultDashboard();
    }

    private function adminDashboard()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Double-check we should be here
        if (!$user->isAdmin()) {
            Log::error('SECURITY: Non-admin user reached adminDashboard method!', [
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

    private function pwdDashboard()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Double-check we should be here
        if (!$user->isPwd()) {
            Log::error('SECURITY: Non-PWD user reached pwdDashboard method!', [
                'user_id' => $user->id,
                'role' => $user->role
            ]);
            abort(403, 'Access denied.');
        }

        Log::info('Loading PWD dashboard data', ['user_id' => $user->id]);

        // Get available job postings
        $jobPostings = JobPosting::where('is_active', true)
            ->where('application_deadline', '>=', now())
            ->latest()
            ->take(5)
            ->get();

        // Get available trainings
        $skillTrainings = SkillTraining::where('is_active', true)
            ->where('start_date', '>=', now())
            ->latest()
            ->take(5)
            ->get();

        // Get recent announcements
        $announcements = Announcement::where('is_active', true)
            ->latest()
            ->take(5)
            ->get();

        // Load relationships
        $user->load(['jobApplications', 'trainingEnrollments', 'documents']);

        $userJobApplications = $user->jobApplications->pluck('job_posting_id')->toArray();
        $userTrainingEnrollments = $user->trainingEnrollments->pluck('skill_training_id')->toArray();

        $applicationCount = $user->jobApplications->count();
        $enrollmentCount = $user->trainingEnrollments->count();
        $documentCount = $user->documents->count();

        return view('dashboard.pwd', compact(
            'jobPostings',
            'skillTrainings',
            'announcements',
            'userJobApplications',
            'userTrainingEnrollments',
            'applicationCount',
            'enrollmentCount',
            'documentCount'
        ));
    }

    private function defaultDashboard()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        Log::info('Loading default dashboard', [
            'user_id' => $user->id,
            'role' => $user->role
        ]);

        return view('dashboard.default', [
            'user' => $user,
            'role' => $user->role
        ]);
    }
}
