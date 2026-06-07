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
use App\Models\CommunityPwdStat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class AdminDashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Security check
        if (!$user->isAdmin()) {
            Log::error('SECURITY: Non-admin user accessed admin dashboard!', [
                'user_id' => $user->id,
                'role' => $user->role
            ]);
            abort(403, 'Access denied.');
        }

        Log::info('Loading admin dashboard data', ['user_id' => $user->id]);

        // Get community statistics (current year)
        $currentYear = date('Y');
        $communityStats = CommunityPwdStat::where('year', $currentYear)->get();
        $communityTotals = [
            'total_unemployed' => $communityStats->sum('unemployed_count'),
            'total_employed' => $communityStats->sum('employed_count'),
            'total_pwd' => $communityStats->sum(function($stat) {
                return $stat->unemployed_count + $stat->employed_count;
            }),
            'employment_rate' => 0,
        ];
        if ($communityTotals['total_pwd'] > 0) {
            $communityTotals['employment_rate'] = round(($communityTotals['total_employed'] / $communityTotals['total_pwd']) * 100, 2);
        }

        // Get comprehensive statistics
        $stats = [
            'job_postings' => JobPosting::count(),
            'skill_trainings' => SkillTraining::count(),
            'active_announcements' => Announcement::where('is_active', true)->count(),
            'total_users' => User::count(),
            'pwd_users' => User::where('role', 'pwd')->count(),
            'admin_users' => User::where('role', 'admin')->count(),
            'employer_users' => User::where('role', 'employer')->count(),
            'pending_applications' => JobApplication::where('status', 'pending')->count(),
            'approved_applications' => JobApplication::where('status', 'approved')->count(),
            'rejected_applications' => JobApplication::where('status', 'rejected')->count(),
            'active_trainings' => TrainingEnrollment::where('status', 'enrolled')->count(),
            'completed_trainings' => TrainingEnrollment::where('status', 'completed')->count(),
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
            'inactive_jobs' => JobPosting::where('is_active', false)->count(),
            'expired_jobs' => JobPosting::where('application_deadline', '<', now())->count(),
            'active_trainings_count' => SkillTraining::where('is_active', true)->count(),
            'qualified_applicants' => User::where('role', 'pwd')->where('is_qualified', true)->count(),
            'available_qualified' => User::where('role', 'pwd')->where('is_qualified', true)->where('available_for_jobs', true)->count(),
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
            'securityAlerts',
            'communityTotals',
            'currentYear'
        ));
    }
}
