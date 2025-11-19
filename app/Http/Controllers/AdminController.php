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
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function dashboard()
    {
        // Record admin activity
        if (method_exists(Auth::user(), 'recordAdminAction')) {
            Auth::user()->recordAdminAction();
        }

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
        Auth::user()->recordAdminAction();

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

    public function createUser()
    {
        Auth::user()->recordAdminAction();

        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        Auth::user()->recordAdminAction();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:pwd,employer,admin',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['email_verified_at'] = now(); // Auto-verify admin-created accounts

        $user = User::create($validated);

        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "User account '{$user->name}' has been created successfully.");
    }

    public function userShow(User $user)
    {
        Auth::user()->recordAdminAction();

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

    public function deleteUser(User $user)
    {
        Auth::user()->recordAdminAction();

        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Prevent deleting the last admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->back()->with('error', 'Cannot delete the last administrator account.');
            }
        }

        $userName = $user->name;
        $userEmail = $user->email;

        try {
            // Delete related data first
            $user->pwdProfile()->delete();
            $user->jobApplications()->delete();
            $user->trainingEnrollments()->delete();
            $user->notifications()->delete();
            
            // Delete the user
            $user->delete();

            return redirect()->route('admin.users.index')
                ->with('success', "User account '{$userName}' ({$userEmail}) has been permanently deleted.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user account: ' . $e->getMessage());
        }
    }

    public function userSecurityReport()
    {
        Auth::user()->recordAdminAction();

        // Comprehensive security statistics
        $totalUsers = User::count();
        $pwdUsers = User::where('role', 'pwd')->count();
        $employerUsers = User::where('role', 'employer')->count();
        $adminUsers = User::where('role', 'admin')->count();

        $securityStats = [
            'total_users' => $totalUsers,
            'pwd_users' => $pwdUsers,
            'employer_users' => $employerUsers,
            'admin_users' => $adminUsers,
            'users_with_strong_passwords' => User::where('password_meets_current_standards', true)->count(),
            'users_with_2fa' => User::whereNotNull('two_factor_secret')->count(),
            'locked_accounts' => User::whereNotNull('account_locked_until')
                                    ->where('account_locked_until', '>', now())
                                    ->count(),
            'expired_passwords' => User::where(function($q) {
                $q->whereNull('last_password_changed_at')
                  ->orWhere('last_password_changed_at', '<', now()->subDays(90));
            })->count(),
            'high_risk_users' => User::where(function($q) {
                $q->where('password_meets_current_standards', false)
                  ->orWhere('failed_login_attempts', '>=', 3)
                  ->orWhereNotNull('account_locked_until');
            })->count(),
            'active_users' => User::where('is_active', true)->count(),
            'inactive_users' => User::where('is_active', false)->count(),
            'recent_logins_24h' => User::where('last_login_at', '>=', now()->subHours(24))->count(),
            'recent_logins_7d' => User::where('last_login_at', '>=', now()->subDays(7))->count(),
            'never_logged_in' => User::whereNull('last_login_at')->count(),
        ];

        // High-risk users with detailed information
        $riskUsers = User::where(function($query) {
                $query->where('password_meets_current_standards', false)
                      ->orWhere('failed_login_attempts', '>=', 3)
                      ->orWhereNotNull('account_locked_until')
                      ->orWhere(function($q) {
                          $q->whereNull('last_password_changed_at')
                            ->orWhere('last_password_changed_at', '<', now()->subDays(90));
                      });
            })
            ->with('pwdProfile')
            ->orderBy('failed_login_attempts', 'desc')
            ->orderBy('last_login_at', 'asc')
            ->get()
            ->map(function ($user) {
                $score = 100;
                $issues = [];

                // Calculate security score
                if (!$user->password_meets_current_standards) {
                    $score -= 30;
                    $issues[] = 'weak_password';
                }
                if (!$user->two_factor_secret) {
                    $score -= 20;
                    $issues[] = 'no_2fa';
                }
                if ($user->failed_login_attempts >= 3) {
                    $score -= 25;
                    $issues[] = 'failed_logins';
                }
                if ($user->account_locked_until && \Carbon\Carbon::parse($user->account_locked_until)->isFuture()) {
                    $score -= 20;
                    $issues[] = 'locked';
                }
                if ($user->last_password_changed_at && $user->last_password_changed_at->lt(now()->subDays(90))) {
                    $score -= 15;
                    $issues[] = 'expired_password';
                }
                if (!$user->last_login_at) {
                    $score -= 10;
                    $issues[] = 'never_logged_in';
                }

                // Add the calculated fields to the user object
                $user->security_score = max(0, $score);
                $user->security_issues = $issues;

                return $user;
            });

        // Login activity data for chart
        $loginActivity = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = User::whereDate('last_login_at', $date->format('Y-m-d'))->count();
            $loginActivity[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }

        // Role-based security breakdown
        $roleSecurityBreakdown = [
            'pwd' => [
                'total' => $pwdUsers,
                'strong_passwords' => User::where('role', 'pwd')->where('password_meets_current_standards', true)->count(),
                'with_2fa' => User::where('role', 'pwd')->whereNotNull('two_factor_secret')->count(),
                'at_risk' => User::where('role', 'pwd')->where('password_meets_current_standards', false)->count(),
            ],
            'employer' => [
                'total' => $employerUsers,
                'strong_passwords' => User::where('role', 'employer')->where('password_meets_current_standards', true)->count(),
                'with_2fa' => User::where('role', 'employer')->whereNotNull('two_factor_secret')->count(),
                'at_risk' => User::where('role', 'employer')->where('password_meets_current_standards', false)->count(),
            ],
            'admin' => [
                'total' => $adminUsers,
                'strong_passwords' => User::where('role', 'admin')->where('password_meets_current_standards', true)->count(),
                'with_2fa' => User::where('role', 'admin')->whereNotNull('two_factor_secret')->count(),
                'at_risk' => User::where('role', 'admin')->where('password_meets_current_standards', false)->count(),
            ],
        ];

        return view('admin.users.security-report', compact(
            'securityStats',
            'riskUsers',
            'loginActivity',
            'roleSecurityBreakdown'
        ));
    }

    public function systemStatistics()
    {
        Auth::user()->recordAdminAction();

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
        Auth::user()->recordAdminAction();

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
        Auth::user()->recordAdminAction();

        $employers = User::where('role', 'employer')
                         ->where('employer_verification_status', 'pending')
                         ->with('pwdProfile')
                         ->latest()
                         ->paginate(20);

        return view('admin.employers.pending', compact('employers'));
    }

    public function reviewEmployerVerification(User $employer)
    {
        Auth::user()->recordAdminAction();

        $employer->load('pwdProfile', 'documents');

        return view('admin.employers.review', compact('employer'));
    }

    public function approveEmployerVerification(Request $request, User $employer)
    {
        Auth::user()->recordAdminAction();

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
        Auth::user()->recordAdminAction();

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
        Auth::user()->recordAdminAction();

        $note = $request->input('note');

        // Keep current status but send note to employer
        $employer->update(['verification_notes' => $note]);
        $employer->notifyVerificationStatusChanged('kept', $note);

        return redirect()->back()->with('success', 'Requested more information from employer.');
    }

    public function viewEmployerDocuments(User $employer)
    {
        Auth::user()->recordAdminAction();

        $employer->load('documents');

        if (view()->exists('admin.employers.documents')) {
            return view('admin.employers.documents', compact('employer'));
        }

        // Fallback: return a simple JSON response when view is missing
        return response()->json(['documents' => $employer->documents]);
    }

    public function expiredEmployerVerifications()
    {
        Auth::user()->recordAdminAction();

        $employers = User::where('role', 'employer')
                         ->whereNotNull('verification_expires_at')
                         ->where('verification_expires_at', '<', now())
                         ->latest()
                         ->paginate(20);

        return view('admin.employers.expired', compact('employers'));
    }

    public function renewEmployerVerification(Request $request, User $employer)
    {
        Auth::user()->recordAdminAction();

        $employer->update([
            'verification_expires_at' => now()->addYear(),
            'employer_verification_status' => 'verified'
        ]);

        $employer->notifyVerificationStatusChanged('approved', 'Your verification has been renewed.');

        return redirect()->back()->with('success', 'Employer verification renewed and user notified.');
    }
}
