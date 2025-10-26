<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address',
        'registration_ip', 'is_active', 'email_verified_at',
        'last_login_at', 'last_login_ip', 'login_count',
        'failed_login_attempts', 'account_locked_until',
        'password_changed_at', 'last_password_changed_at',
        'password_meets_current_standards', 'two_factor_secret',
        'two_factor_recovery_codes', 'two_factor_confirmed_at',
        'security_questions_set', 'last_security_activity',
        'last_admin_action_at',
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'
    ];

    protected $appends = [
        'last_login_formatted',
        'role_badge_class',
        'security_score',
        'activity_status'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'login_count' => 'integer',
            'password_changed_at' => 'datetime',
            'password_meets_current_standards' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'account_locked_until' => 'datetime',
            'failed_login_attempts' => 'integer',
            'last_password_changed_at' => 'datetime',
            'security_questions_set' => 'boolean',
            'last_security_activity' => 'datetime',
            'last_admin_action_at' => 'datetime',
        ];
    }

    // ======================
    // ROLE & PERMISSION METHODS
    // ======================

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is PWD
     */
    public function isPwd(): bool
    {
        return $this->role === 'pwd';
    }

    /**
     * Check if user is employer
     */
    public function isEmployer(): bool
    {
        return $this->role === 'employer';
    }

    /**
     * Check if user is admin or employer
     */
    public function isAdminOrEmployer(): bool
    {
        return $this->isAdmin() || $this->isEmployer();
    }

    /**
     * Check if user is regular user
     */
    public function isRegularUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'admin' => 'Administrator',
            'pwd' => 'PWD User',
            'user' => 'Regular User',
            'employer' => 'Employer',
            default => 'Unknown Role'
        };
    }

    /**
     * Get Bootstrap badge class for role
     */
    public function getRoleBadgeClassAttribute(): string
    {
        return match($this->role) {
            'admin' => 'badge bg-danger',
            'pwd' => 'badge bg-primary',
            'user' => 'badge bg-secondary',
            'employer' => 'badge bg-success',
            default => 'badge bg-dark'
        };
    }

    /**
     * Check if user can access admin features
     */
    public function canAccessAdmin(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can manage users
     */
    public function canManageUsers(): bool
    {
        return $this->isAdmin();
    }

    /**
     * Check if user can manage job postings
     */
    public function canManageJobPostings(): bool
    {
        return $this->isAdminOrEmployer();
    }

    /**
     * Check if user can manage their own job postings (employer) or all (admin)
     */
    public function canManageJobPosting(JobPosting $jobPosting): bool
    {
        return $this->isAdmin() ||
               ($this->isEmployer() && $jobPosting->created_by == $this->id);
    }

    /**
     * Check if user can manage trainings
     */
    public function canManageTrainings(): bool
    {
        return $this->isAdmin() || $this->isPwd();
    }

    /**
     * Get available roles for user management
     */
    public static function getAvailableRoles(): array
    {
        return [
            'user' => 'Regular User',
            'pwd' => 'PWD User',
            'employer' => 'Employer',
            'admin' => 'Administrator'
        ];
    }

    // ======================
    // ADMIN SPECIFIC METHODS
    // ======================

    /**
     * Record admin action timestamp
     */
    public function recordAdminAction(): void
    {
        if ($this->isAdmin()) {
            $this->update(['last_admin_action_at' => now()]);
        }
    }

    /**
     * Get admin activity status
     */
    public function getAdminActivityStatus(): string
    {
        if (!$this->last_admin_action_at) {
            return 'Never Active';
        }

        $daysSinceLastAction = $this->last_admin_action_at->diffInDays(now());

        if ($daysSinceLastAction === 0) {
            return 'Active Today';
        } elseif ($daysSinceLastAction <= 7) {
            return 'Active This Week';
        } else {
            return 'Inactive (' . $daysSinceLastAction . ' days)';
        }
    }

    /**
     * Check if admin is recently active (within 7 days)
     */
    public function isAdminRecentlyActive(int $days = 7): bool
    {
        return $this->last_admin_action_at &&
               $this->last_admin_action_at->gte(now()->subDays($days));
    }

    // ======================
    // EMPLOYER-SPECIFIC METHODS
    // ======================

    /**
     * Get employer-specific statistics
     */
    public function getEmployerStats(): array
    {
        if (!$this->isEmployer()) {
            return [];
        }

        $jobPostings = $this->jobPostings();

        return [
            'total_jobs' => $jobPostings->count(),
            'active_jobs' => $jobPostings->where('is_active', true)->count(),
            'draft_jobs' => $jobPostings->where('is_active', false)->count(),
            'expired_jobs' => $jobPostings->where('application_deadline', '<', now())->count(),
            'total_views' => $jobPostings->sum('views'),
            'total_applications' => $this->getTotalApplicationsReceived(),
            'response_rate' => $this->calculateResponseRate(),
        ];
    }

    /**
     * Get total applications received for employer's job postings
     */
    public function getTotalApplicationsReceived(): int
    {
        if (!$this->isEmployer()) {
            return 0;
        }

        return JobApplication::whereIn('job_posting_id', function($query) {
            $query->select('id')
                  ->from('job_postings')
                  ->where('created_by', $this->id);
        })->count();
    }

    /**
     * Calculate employer response rate
     */
    public function calculateResponseRate(): float
    {
        if (!$this->isEmployer()) {
            return 0.0;
        }

        $totalApplications = $this->getTotalApplicationsReceived();
        $respondedApplications = JobApplication::whereIn('job_posting_id', function($query) {
            $query->select('id')
                  ->from('job_postings')
                  ->where('created_by', $this->id);
        })->whereIn('status', ['approved', 'rejected'])->count();

        return $totalApplications > 0 ? round(($respondedApplications / $totalApplications) * 100, 2) : 0.0;
    }

    /**
     * Get employer's recent job postings
     */
    public function getRecentJobPostings($limit = 5)
    {
        if (!$this->isEmployer()) {
            return collect();
        }

        return $this->jobPostings()
            ->withCount('applications')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get employer's job posting performance
     */
    public function getJobPostingPerformance(): array
    {
        if (!$this->isEmployer()) {
            return [];
        }

        $jobPostings = $this->jobPostings()
            ->withCount('applications')
            ->get();

        return [
            'most_viewed' => $jobPostings->sortByDesc('views')->first(),
            'most_applications' => $jobPostings->sortByDesc('applications_count')->first(),
            'average_views' => $jobPostings->avg('views'),
            'average_applications' => $jobPostings->avg('applications_count'),
        ];
    }

    /**
     * Check if employer profile is complete
     */
    public function isEmployerProfileComplete(): bool
    {
        if (!$this->isEmployer()) {
            return false;
        }

        return !empty($this->name) &&
               !empty($this->email) &&
               !empty($this->phone) &&
               !empty($this->address);
    }

    /**
     * Get employer profile completion percentage
     */
    public function getEmployerProfileCompletion(): int
    {
        if (!$this->isEmployer()) {
            return 0;
        }

        $fields = ['name', 'email', 'phone', 'address'];
        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }

        return (int) (($completed / count($fields)) * 100);
    }

    // ======================
    // SECURITY ENHANCEMENTS
    // ======================

    /**
     * Get security score attribute
     */
    public function getSecurityScoreAttribute(): int
    {
        return $this->calculateSecurityScore();
    }

    /**
     * Get activity status attribute
     */
    public function getActivityStatusAttribute(): string
    {
        return $this->getActivityStatus();
    }

    /**
     * Check if user requires security setup
     */
    public function requiresSecuritySetup(): bool
    {
        return !$this->hasStrongPassword() ||
               !$this->hasTwoFactorEnabled() ||
               $this->isPasswordExpired();
    }

    /**
     * Get security setup progress (0-100)
     */
    public function getSecuritySetupProgress(): int
    {
        $progress = 0;
        $totalSteps = 3;

        if ($this->hasStrongPassword()) $progress++;
        if ($this->hasTwoFactorEnabled()) $progress++;
        if (!$this->isPasswordExpired()) $progress++;

        return (int) (($progress / $totalSteps) * 100);
    }

    // ======================
    // QUERY SCOPES
    // ======================

    /**
     * Scope for admin users
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope for PWD users
     */
    public function scopePwdUsers($query)
    {
        return $query->where('role', 'pwd');
    }

    /**
     * Scope for employer users
     */
    public function scopeEmployers($query)
    {
        return $query->where('role', 'employer');
    }

    /**
     * Scope for regular users
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('role', 'user');
    }

    /**
     * Scope for users who can manage job postings
     */
    public function scopeCanManageJobs($query)
    {
        return $query->whereIn('role', ['admin', 'employer']);
    }

    /**
     * Scope for users by role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope for users requiring security attention
     */
    public function scopeNeedsSecurityAttention($query)
    {
        return $query->where(function($q) {
            $q->where('password_meets_current_standards', false)
              ->orWhereNull('two_factor_secret')
              ->orWhere('failed_login_attempts', '>=', 3)
              ->orWhereNotNull('account_locked_until');
        });
    }

    /**
     * Scope for active users
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for users who have logged in
     */
    public function scopeHasLoggedIn($query)
    {
        return $query->whereNotNull('last_login_at');
    }

    /**
     * Scope for recently active users
     */
    public function scopeRecentlyActive($query, $days = 30)
    {
        return $query->where('last_login_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for users registered between dates
     */
    public function scopeRegisteredBetween($query, $startDate, $endDate = null)
    {
        $endDate = $endDate ?? now();
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope for users with expired passwords
     */
    public function scopeWithExpiredPassword($query, $days = 90)
    {
        return $query->where(function($q) use ($days) {
            $q->whereNull('last_password_changed_at')
              ->orWhere('last_password_changed_at', '<', now()->subDays($days));
        });
    }

    /**
     * Scope for locked users
     */
    public function scopeLocked($query)
    {
        return $query->whereNotNull('account_locked_until')
                    ->where('account_locked_until', '>', now());
    }

    /**
     * Scope for users with weak passwords
     */
    public function scopeWithWeakPassword($query)
    {
        return $query->where('password_meets_current_standards', false);
    }

    // ======================
    // STATISTICS & REPORTING
    // ======================

    /**
     * Get user statistics for admin dashboard
     */
    public static function getDashboardStatistics(): array
    {
        $totalUsers = self::count();
        $pwdUsers = self::pwdUsers()->count();
        $adminUsers = self::admins()->count();
        $employerUsers = self::employers()->count();
        $activeUsers = self::active()->count();
        $lockedUsers = self::locked()->count();

        return [
            'total_users' => $totalUsers,
            'pwd_users' => $pwdUsers,
            'admin_users' => $adminUsers,
            'employer_users' => $employerUsers,
            'regular_users' => $totalUsers - $pwdUsers - $adminUsers - $employerUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $totalUsers - $activeUsers,
            'locked_users' => $lockedUsers,
            'pwd_percentage' => $totalUsers > 0 ? round(($pwdUsers / $totalUsers) * 100, 2) : 0,
            'admin_percentage' => $totalUsers > 0 ? round(($adminUsers / $totalUsers) * 100, 2) : 0,
            'employer_percentage' => $totalUsers > 0 ? round(($employerUsers / $totalUsers) * 100, 2) : 0,
        ];
    }

    /**
     * Get registration trends for chart
     */
    public static function getRegistrationTrends(int $days = 30): array
    {
        $trends = [];
        $startDate = now()->subDays($days);

        for ($i = 0; $i <= $days; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $count = self::whereDate('created_at', $date)->count();
            $trends['labels'][] = $date;
            $trends['data'][] = $count;
        }

        return $trends;
    }

    // ======================
    // LOGIN TRACKING METHODS
    // ======================

    public function updateLastLogin($ipAddress = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ipAddress ?? request()->ip(),
            'login_count' => $this->login_count + 1,
            'failed_login_attempts' => 0,
            'last_security_activity' => now(),
        ]);
    }

    public function recordFailedLoginAttempt()
    {
        $this->increment('failed_login_attempts');
        $this->update(['last_security_activity' => now()]);

        if ($this->failed_login_attempts >= 5) {
            $this->update([
                'account_locked_until' => now()->addMinutes(30)
            ]);
        }
    }

    public function isAccountLocked()
    {
        return $this->account_locked_until && $this->account_locked_until->isFuture();
    }

    public function getTimeUntilUnlock()
    {
        if (!$this->isAccountLocked()) {
            return null;
        }
        return $this->account_locked_until->diffForHumans();
    }

    public function unlockAccount()
    {
        $this->update([
            'account_locked_until' => null,
            'failed_login_attempts' => 0
        ]);
    }

    public function getLastLoginFormattedAttribute()
    {
        return $this->last_login_at
            ? $this->last_login_at->format('M j, Y \a\t g:i A')
            : 'Never logged in';
    }

    public function isActive()
    {
        return $this->is_active ?? true;
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    public function getLoginStats()
    {
        return [
            'total_logins' => $this->login_count ?? 0,
            'last_login' => $this->last_login_formatted,
            'last_login_ip' => $this->last_login_ip,
            'account_status' => $this->isActive() ? 'Active' : 'Inactive',
            'registration_ip' => $this->registration_ip,
            'failed_attempts' => $this->failed_login_attempts,
            'is_locked' => $this->isAccountLocked(),
            'time_until_unlock' => $this->getTimeUntilUnlock(),
        ];
    }

    public function getRegistrationInfo()
    {
        return [
            'registered_at' => $this->created_at->format('M j, Y \a\t g:i A'),
            'registration_ip' => $this->registration_ip,
            'account_age' => $this->created_at->diffForHumans(),
        ];
    }

    // ======================
    // PASSWORD SECURITY METHODS
    // ======================

    public function hasStrongPassword()
    {
        return $this->password_meets_current_standards ?? false;
    }

    public function isPasswordExpired($days = 90)
    {
        if (!$this->last_password_changed_at) {
            return true;
        }
        return $this->last_password_changed_at->lessThan(now()->subDays($days));
    }

    public function markPasswordChanged($meetsStandards = true)
    {
        $this->update([
            'password_changed_at' => now(),
            'last_password_changed_at' => now(),
            'password_meets_current_standards' => $meetsStandards,
            'last_security_activity' => now(),
        ]);
    }

    public function isPasswordCompromised()
    {
        return false;
    }

    public function getPasswordSecurityStatus()
    {
        return [
            'is_strong' => $this->hasStrongPassword(),
            'is_expired' => $this->isPasswordExpired(),
            'days_until_expiry' => $this->last_password_changed_at ?
                max(0, 90 - $this->last_password_changed_at->diffInDays(now())) : 90,
            'last_changed' => $this->last_password_changed_at?->format('M j, Y'),
            'is_compromised' => $this->isPasswordCompromised(),
        ];
    }

    // ======================
    // TWO-FACTOR AUTHENTICATION METHODS
    // ======================

    public function enableTwoFactorAuth($secret, $recoveryCodes)
    {
        $this->update([
            'two_factor_secret' => encrypt($secret),
            'two_factor_recovery_codes' => encrypt(json_encode($recoveryCodes)),
            'two_factor_confirmed_at' => now(),
            'last_security_activity' => now(),
        ]);
    }

    public function disableTwoFactorAuth()
    {
        $this->update([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
            'last_security_activity' => now(),
        ]);
    }

    public function hasTwoFactorEnabled()
    {
        return !is_null($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }

    public function getTwoFactorStatus()
    {
        return [
            'enabled' => $this->hasTwoFactorEnabled(),
            'confirmed_at' => $this->two_factor_confirmed_at?->format('M j, Y \a\t g:i A'),
            'recovery_codes_count' => $this->hasTwoFactorEnabled() ?
                count(json_decode(decrypt($this->two_factor_recovery_codes), true)) : 0,
        ];
    }

    // ======================
    // SECURITY AND ACTIVITY METHODS
    // ======================

    public function hasNeverLoggedIn()
    {
        return is_null($this->last_login_at);
    }

    public function getDaysSinceLastLogin()
    {
        return $this->last_login_at
            ? $this->last_login_at->diffInDays(now())
            : null;
    }

    public function isRecentlyActive($days = 7)
    {
        if (!$this->last_login_at) {
            return false;
        }
        return $this->last_login_at->gte(now()->subDays($days));
    }

    public function getActivityStatus()
    {
        if ($this->hasNeverLoggedIn()) {
            return 'Never Logged In';
        }

        $daysSinceLogin = $this->getDaysSinceLastLogin();

        if ($daysSinceLogin === 0) {
            return 'Active Today';
        } elseif ($daysSinceLogin <= 7) {
            return 'Active This Week';
        } elseif ($daysSinceLogin <= 30) {
            return 'Active This Month';
        } else {
            return 'Inactive (' . $daysSinceLogin . ' days)';
        }
    }

    public function isSuspiciousRegistration($maxAccounts = 3, $hours = 24)
    {
        $count = self::where('registration_ip', $this->registration_ip)
            ->where('created_at', '>=', now()->subHours($hours))
            ->count();
        return $count > $maxAccounts;
    }

    public function hasSuspiciousActivity()
    {
        return $this->isSuspiciousRegistration() ||
               $this->failed_login_attempts >= 3 ||
               $this->isAccountLocked() ||
               $this->isPasswordCompromised();
    }

    public function getSecurityOverview()
    {
        return [
            'account_status' => $this->isActive() ? 'Active' : 'Inactive',
            'last_login' => $this->last_login_formatted,
            'login_count' => $this->login_count,
            'registration_ip' => $this->registration_ip,
            'is_suspicious' => $this->isSuspiciousRegistration(),
            'has_strong_password' => $this->hasStrongPassword(),
            'two_factor_enabled' => $this->hasTwoFactorEnabled(),
            'password_expired' => $this->isPasswordExpired(),
            'account_locked' => $this->isAccountLocked(),
            'failed_attempts' => $this->failed_login_attempts,
            'suspicious_activity' => $this->hasSuspiciousActivity(),
            'security_score' => $this->calculateSecurityScore(),
        ];
    }

    public function calculateSecurityScore()
    {
        $score = 100;
        if (!$this->hasStrongPassword()) $score -= 30;
        if (!$this->hasTwoFactorEnabled()) $score -= 20;
        if ($this->isPasswordExpired()) $score -= 15;
        if ($this->failed_login_attempts > 0) $score -= 5;
        if ($this->isSuspiciousRegistration()) $score -= 10;
        if ($this->isAccountLocked()) $score -= 10;
        return max(0, $score);
    }

    public function getSecurityRecommendations()
    {
        $recommendations = [];
        if (!$this->hasStrongPassword()) {
            $recommendations[] = 'Upgrade to a stronger password';
        }
        if (!$this->hasTwoFactorEnabled()) {
            $recommendations[] = 'Enable two-factor authentication';
        }
        if ($this->isPasswordExpired()) {
            $recommendations[] = 'Change your expired password';
        }
        if ($this->failed_login_attempts > 0) {
            $recommendations[] = 'Review recent login attempts';
        }
        return $recommendations;
    }

    // ======================
    // RELATIONSHIPS
    // ======================

    public function pwdProfile()
    {
        return $this->hasOne(PwdProfile::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function trainingEnrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function jobPostings()
    {
        return $this->hasMany(JobPosting::class, 'created_by');
    }

    public function skillTrainings()
    {
        return $this->hasMany(SkillTraining::class, 'created_by');
    }

    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by');
    }

    public function notifications()
    {
        return $this->morphMany(\Illuminate\Notifications\DatabaseNotification::class, 'notifiable')
                    ->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function hasPwdProfile()
    {
        return $this->pwdProfile()->exists();
    }

    public function getUnreadNotificationsCount()
    {
        return $this->unreadNotifications()->count();
    }

    public function markNotificationsAsRead()
    {
        return $this->unreadNotifications()->update(['read_at' => now()]);
    }

    public function getLatestNotifications($limit = 5)
    {
        return $this->notifications()->take($limit)->get();
    }

    public function isProfileComplete()
    {
        if (!$this->pwdProfile) {
            return false;
        }
        return !empty($this->pwdProfile->disability_type) &&
               !empty($this->pwdProfile->skills) &&
               !empty($this->pwdProfile->phone) &&
               !empty($this->pwdProfile->address);
    }

    public function getApplicationStats()
    {
        return [
            'total' => $this->jobApplications()->count(),
            'pending' => $this->jobApplications()->where('status', 'pending')->count(),
            'approved' => $this->jobApplications()->where('status', 'approved')->count(),
            'rejected' => $this->jobApplications()->where('status', 'rejected')->count(),
        ];
    }

    public function getTrainingStats()
    {
        return [
            'total' => $this->trainingEnrollments()->count(),
            'pending' => $this->trainingEnrollments()->where('status', 'pending')->count(),
            'confirmed' => $this->trainingEnrollments()->where('status', 'confirmed')->count(),
            'completed' => $this->trainingEnrollments()->where('status', 'completed')->count(),
        ];
    }
}
