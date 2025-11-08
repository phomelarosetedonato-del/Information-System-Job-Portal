<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use App\Traits\HasPasswordHistory;
use Illuminate\Support\Facades\Log;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasPasswordHistory;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'phone', 'address',
        'registration_ip', 'is_active', 'email_verified_at',
        'last_login_at', 'last_login_ip', 'login_count',
        'failed_login_attempts', 'account_locked_until',
        'password_changed_at', 'last_password_changed_at',
        'password_meets_current_standards', 'two_factor_secret',
        'two_factor_recovery_codes', 'two_factor_confirmed_at',
        'security_questions_set', 'last_security_activity',
        'last_admin_action_at', 'resume', 'employer_verified_at',
        'employer_verification_status', 'company_name',
        'company_size', 'company_type', 'website', 'description',
        'verification_documents', 'verification_submitted_at',
        'can_resubmit_verification_at', 'verification_rejected_reason',
        'verification_notes', 'verification_expires_at'
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'
    ];

    protected $appends = [
        'last_login_formatted',
        'role_badge_class',
        'security_score',
        'activity_status',
        'resume_url',
        'resume_file_name',
        'resume_file_size',
        'resume_file_type',
        'resume_status',
        'can_apply_for_jobs',
        'password_expiry_days',
        'requires_password_change' // ADDED THIS
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
            'employer_verified_at' => 'datetime',
            'employer_verification_status' => 'string',
            'verification_submitted_at' => 'datetime',
            'can_resubmit_verification_at' => 'datetime',
            'verification_expires_at' => 'datetime',
            'verification_documents' => 'array',
        ];
    }

    /**
 * Boot the model with password history tracking
 */
protected static function booted()
{
    // Add initial password to history when user is created
    static::created(function ($user) {
        $user->addToPasswordHistory();
    });

    // Track password changes for security standards
    static::updated(function ($user) {
        if ($user->isDirty('password')) {
            $user->last_password_changed_at = now();
            $user->password_changed_at = now();
            $user->last_security_activity = now();

            // Check if password meets current security standards
            $user->password_meets_current_standards = $user->checkPasswordMeetsStandards();

            $user->saveQuietly(); // Use saveQuietly to avoid recursion
        }
    });
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
    // JOB APPLICATION METHODS - ADDED THESE
    // ======================

    /**
     * Check if user can apply for jobs
     */
    public function canApplyForJobs(): bool
    {
        // Only PWD users can apply for jobs
        if (!$this->isPwd()) {
            return false;
        }

        return $this->hasResume() &&
               $this->hasCompletePwdProfile();
    }

    /**
     * Get can_apply_for_jobs attribute for easy access in views
     */
    public function getCanApplyForJobsAttribute(): bool
    {
        return $this->canApplyForJobs();
    }

    /**
     * Check if user has complete PWD profile
     */
    public function hasCompletePwdProfile(): bool
    {
        if (!$this->isPwd()) {
            return false;
        }

        return $this->pwdProfile &&
               !empty($this->pwdProfile->disability_type) &&
               !empty($this->phone) &&
               !empty($this->address);
    }

    /**
     * Get job application eligibility details
     */
    public function getJobApplicationEligibility(): array
    {
        $eligibility = [
            'can_apply' => true,
            'reasons' => [],
            'missing_resume' => false,
            'incomplete_profile' => false,
        ];

        // Check if user is PWD
        if (!$this->isPwd()) {
            $eligibility['can_apply'] = false;
            $eligibility['reasons'][] = 'Only PWD users can apply for jobs.';
            return $eligibility;
        }

        // Check resume
        if (!$this->hasResume()) {
            $eligibility['can_apply'] = false;
            $eligibility['reasons'][] = 'Please upload your resume before applying for jobs.';
            $eligibility['missing_resume'] = true;
        }

        // Check PWD profile completion
        if (!$this->hasCompletePwdProfile()) {
            $eligibility['can_apply'] = false;
            $eligibility['reasons'][] = 'Please complete your PWD profile before applying for jobs.';
            $eligibility['incomplete_profile'] = true;
        }

        return $eligibility;
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
    // EMPLOYER VERIFICATION METHODS
    // ======================

    /**
     * Employer verification methods
     */
    public function isEmployerVerified(): bool
    {
        return $this->employer_verification_status === 'verified' &&
               !is_null($this->employer_verified_at) &&
               (!$this->verification_expires_at || $this->verification_expires_at->isFuture());
    }

    public function isEmployerPendingVerification(): bool
    {
        return $this->employer_verification_status === 'pending' &&
               $this->isEmployer();
    }

    public function isEmployerRejected(): bool
    {
        return $this->employer_verification_status === 'rejected';
    }

    public function getEmployerVerificationStatus(): string
    {
        if ($this->isEmployerVerified()) {
            if ($this->verification_expires_at && $this->verification_expires_at->isPast()) {
                return 'Verification Expired';
            }
            return 'Verified';
        } elseif ($this->isEmployerPendingVerification()) {
            return 'Pending Verification';
        } elseif ($this->isEmployerRejected()) {
            return 'Verification Rejected';
        } else {
            return 'Not Applied';
        }
    }

    /**
     * Check if employer can resubmit verification after rejection
     */
    public function canResubmitVerification(): bool
    {
        if (!$this->isEmployerRejected()) {
            return false;
        }

        // Allow resubmission after 7 days if no specific date is set
        if (empty($this->can_resubmit_verification_at)) {
            return $this->updated_at->lessThan(now()->subDays(7));
        }

        return now()->greaterThanOrEqualTo($this->can_resubmit_verification_at);
    }

    /**
     * Get employer verification documents
     */
    public function getVerificationDocuments(): array
    {
        return $this->verification_documents ?: [];
    }

    /**
     * Check if employer has submitted verification documents
     */
    public function hasSubmittedVerification(): bool
    {
        return !empty($this->verification_documents) &&
               $this->isEmployerPendingVerification();
    }

    /**
     * Check if employer can post jobs
     */
    public function canPostJobs(): bool
    {
        if (!$this->isEmployer()) {
            return false;
        }

        // For PWD portal, require verification to post jobs
        return $this->isEmployerVerified();
    }

    /**
     * Check if employer can access dashboard features
     */
    public function canAccessEmployerFeatures(): bool
    {
        if (!$this->isEmployer()) {
            return false;
        }

        // Allow basic dashboard access but limit job posting
        return true;
    }

    /**
     * Check if employer verification is expired
     */
    public function isVerificationExpired(): bool
    {
        return $this->verification_expires_at &&
               $this->verification_expires_at->isPast();
    }

    /**
     * Get days until verification expires
     */
    public function getDaysUntilVerificationExpires(): ?int
    {
        if (!$this->verification_expires_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->verification_expires_at, false));
    }

    // ======================
    // EMPLOYER STATISTICS METHODS
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

        $stats = [
            'total_jobs' => $jobPostings->count(),
            'active_jobs' => $jobPostings->where('is_active', true)->count(),
            'draft_jobs' => $jobPostings->where('is_active', false)->count(),
            'expired_jobs' => $jobPostings->where('application_deadline', '<', now())->count(),
            'total_views' => $jobPostings->sum('views'),
            'total_applications' => $this->getTotalApplicationsReceived(),
            'response_rate' => $this->calculateResponseRate(),
            'profile_completion' => $this->getEmployerProfileCompletion(),
            'verification_status' => $this->getEmployerVerificationStatus(),
        ];

        // Add recent activity for verified employers
        if ($this->isEmployerVerified()) {
            $stats['recent_applications'] = $this->getRecentApplications(5);
            $stats['popular_jobs'] = $this->getMostPopularJobs(3);
        }

        // Add resume stats if employer has resume
        if ($this->hasResume()) {
            $stats['has_resume'] = true;
            $stats['resume_uploaded_at'] = $this->updated_at->format('M j, Y');
        } else {
            $stats['has_resume'] = false;
        }

        return $stats;
    }

    /**
     * Get recent applications for employer's jobs
     */
    private function getRecentApplications($limit = 5)
    {
        return JobApplication::whereIn('job_posting_id', function($query) {
                $query->select('id')
                      ->from('job_postings')
                      ->where('created_by', $this->id);
            })
            ->with(['jobPosting', 'user'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * Get most popular jobs by views
     */
    private function getMostPopularJobs($limit = 3)
    {
        return $this->jobPostings()
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Calculate employer response rate to applications
     */
    public function calculateResponseRate(): float
    {
        $totalApplications = $this->getTotalApplicationsReceived();

        if ($totalApplications === 0) {
            return 0.0;
        }

        $respondedApplications = JobApplication::whereIn('job_posting_id', function($query) {
                $query->select('id')
                      ->from('job_postings')
                      ->where('created_by', $this->id);
            })
            ->whereIn('status', ['approved', 'rejected', 'shortlisted'])
            ->count();

        return round(($respondedApplications / $totalApplications) * 100, 2);
    }

    /**
     * Get total applications received
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

        $requiredFields = [
            'name', 'email', 'phone', 'address',
            'company_name', 'company_size', 'company_type', 'website', 'description'
        ];

        foreach ($requiredFields as $field) {
            if (empty($this->$field)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get employer profile completion percentage
     */
    public function getEmployerProfileCompletion(): int
    {
        if (!$this->isEmployer()) {
            return 0;
        }

        $fields = [
            'name', 'email', 'phone', 'address',
            'company_name', 'company_size', 'company_type', 'website', 'description'
        ];

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
           $this->isPasswordExpired() ||
           $this->requiresPasswordChange;
}

/**
 * Get security setup progress (0-100)
 */
public function getSecuritySetupProgress(): int
{
    $progress = 0;
    $totalSteps = 4; // Increased to 4 steps

    if ($this->hasStrongPassword()) $progress++;
    if ($this->hasTwoFactorEnabled()) $progress++;
    if (!$this->isPasswordExpired()) $progress++;
    if ($this->password_meets_current_standards) $progress++;

    return (int) (($progress / $totalSteps) * 100);
}

/**
 * Get password expiry information
 */
public function getPasswordExpiryDaysAttribute(): ?int
{
    return $this->getDaysUntilPasswordExpires();
}

/**
 * Check if user requires password change
 */
public function getRequiresPasswordChangeAttribute(): bool
{
    return $this->isPasswordExpired() ||
           !$this->password_meets_current_standards ||
           $this->getDaysUntilPasswordExpires() <= 7;
}

/**
 * Enhanced security score calculation with password history
 */
public function calculateSecurityScore(): int
{
    $score = 100;

    // Password strength (-30 if weak)
    if (!$this->hasStrongPassword()) $score -= 30;

    // Two-factor authentication (-20 if not enabled)
    if (!$this->hasTwoFactorEnabled()) $score -= 20;

    // Password expiry (-15 if expired, -5 if expiring soon)
    if ($this->isPasswordExpired()) {
        $score -= 15;
    } elseif ($this->getDaysUntilPasswordExpires() <= 7) {
        $score -= 5;
    }

    // Password standards compliance (-10 if doesn't meet current standards)
    if (!$this->password_meets_current_standards) $score -= 10;

    // Failed login attempts (-3 per attempt, max -15)
    $failedPenalty = min($this->failed_login_attempts * 3, 15);
    $score -= $failedPenalty;

    // Account lock status (-15 if locked)
    if ($this->isAccountLocked()) $score -= 15;

    // Suspicious registration (-10 if suspicious)
    if ($this->isSuspiciousRegistration()) $score -= 10;

    // Password history compliance (+10 if using history feature with multiple records)
    if (method_exists($this, 'passwordHistories') && $this->passwordHistories()->count() > 0) {
        $score += 10;
    }

    // Additional points for resume completeness
    if (($this->isPwd() || $this->isEmployer()) && $this->hasResume()) {
        $score += 5;
    }

    // Additional points for employer verification
    if ($this->isEmployer() && $this->isEmployerVerified()) {
        $score += 10;
    }

    // Email verification (+10 if verified)
    if ($this->hasVerifiedEmail()) {
        $score += 10;
    }

    // Recent security activity (+5 if active within 30 days)
    if ($this->last_security_activity && $this->last_security_activity->gte(now()->subDays(30))) {
        $score += 5;
    }

    return max(0, min(100, $score));
}

/**
 * Get security recommendations with prioritization
 */
public function getSecurityRecommendations(): array
{
    $recommendations = [];

    // High priority recommendations
    if (!$this->hasStrongPassword()) {
        $recommendations[] = [
            'type' => 'password_strength',
            'priority' => 'high',
            'message' => 'Upgrade to a stronger password that meets security standards',
            'action' => 'change_password',
            'icon' => 'fas fa-shield-alt'
        ];
    }

    if ($this->isPasswordExpired()) {
        $recommendations[] = [
            'type' => 'password_expiry',
            'priority' => 'high',
            'message' => 'Your password has expired. Please change it immediately.',
            'action' => 'change_password',
            'icon' => 'fas fa-clock'
        ];
    }

    if (!$this->password_meets_current_standards) {
        $recommendations[] = [
            'type' => 'password_standards',
            'priority' => 'high',
            'message' => 'Your password does not meet current security standards',
            'action' => 'change_password',
            'icon' => 'fas fa-exclamation-triangle'
        ];
    }

    // Medium priority recommendations
    if (!$this->hasTwoFactorEnabled()) {
        $recommendations[] = [
            'type' => 'two_factor',
            'priority' => 'medium',
            'message' => 'Enable two-factor authentication for enhanced security',
            'action' => 'enable_2fa',
            'icon' => 'fas fa-mobile-alt'
        ];
    }

    if ($this->getDaysUntilPasswordExpires() <= 7) {
        $recommendations[] = [
            'type' => 'password_expiry_soon',
            'priority' => 'medium',
            'message' => 'Your password will expire in ' . $this->getDaysUntilPasswordExpires() . ' days',
            'action' => 'change_password',
            'icon' => 'fas fa-hourglass-half'
        ];
    }

    if ($this->failed_login_attempts > 0) {
        $recommendations[] = [
            'type' => 'failed_attempts',
            'priority' => 'medium',
            'message' => 'Review ' . $this->failed_login_attempts . ' failed login attempts',
            'action' => 'review_security',
            'icon' => 'fas fa-user-shield'
        ];
    }

    // Low priority recommendations
    if (($this->isPwd() || $this->isEmployer()) && !$this->hasResume()) {
        $recommendations[] = [
            'type' => 'resume_missing',
            'priority' => 'low',
            'message' => 'Upload your resume to improve job applications',
            'action' => 'upload_resume',
            'icon' => 'fas fa-file-alt'
        ];
    }

    if ($this->isEmployer() && !$this->isEmployerVerified()) {
        $recommendations[] = [
            'type' => 'employer_verification',
            'priority' => 'low',
            'message' => 'Complete employer verification to post jobs',
            'action' => 'verify_employer',
            'icon' => 'fas fa-badge-check'
        ];
    }

    // Password history recommendation
    if (method_exists($this, 'passwordHistories') && $this->passwordHistories()->count() < 2) {
        $recommendations[] = [
            'type' => 'password_history',
            'priority' => 'low',
            'message' => 'Password history tracking is active',
            'action' => 'view_security',
            'icon' => 'fas fa-history'
        ];
    }

    // Sort by priority (high, medium, low)
    usort($recommendations, function($a, $b) {
        $priorityOrder = ['high' => 0, 'medium' => 1, 'low' => 2];
        return $priorityOrder[$a['priority']] - $priorityOrder[$b['priority']];
    });

    return $recommendations;
}

/**
 * Get security level based on score
 */
public function getSecurityLevel(): string
{
    $score = $this->security_score;

    if ($score >= 90) return 'Excellent';
    if ($score >= 75) return 'Good';
    if ($score >= 60) return 'Fair';
    if ($score >= 40) return 'Poor';
    return 'Critical';
}

/**
 * Get security level color
 */
public function getSecurityLevelColor(): string
{
    $score = $this->security_score;

    if ($score >= 90) return 'success';
    if ($score >= 75) return 'info';
    if ($score >= 60) return 'warning';
    if ($score >= 40) return 'orange';
    return 'danger';
}

/**
 * Check if security meets minimum requirements
 */
public function meetsMinimumSecurityRequirements(): bool
{
    return $this->security_score >= 60 &&
           $this->hasStrongPassword() &&
           !$this->isPasswordExpired() &&
           $this->password_meets_current_standards;
}

/**
 * Get security overview with enhanced information
 */
public function getSecurityOverview(): array
{
    $passwordStatus = $this->getPasswordSecurityStatus();

    return [
        'account_status' => $this->isActive() ? 'Active' : 'Inactive',
        'security_level' => $this->getSecurityLevel(),
        'security_level_color' => $this->getSecurityLevelColor(),
        'meets_requirements' => $this->meetsMinimumSecurityRequirements(),
        'last_login' => $this->last_login_formatted,
        'login_count' => $this->login_count,
        'registration_ip' => $this->registration_ip,
        'is_suspicious' => $this->isSuspiciousRegistration(),
        'has_strong_password' => $this->hasStrongPassword(),
        'two_factor_enabled' => $this->hasTwoFactorEnabled(),
        'password_expired' => $passwordStatus['is_expired'],
        'password_expiry_days' => $passwordStatus['days_until_expiry'],
        'password_meets_standards' => $this->password_meets_current_standards,
        'account_locked' => $this->isAccountLocked(),
        'failed_attempts' => $this->failed_login_attempts,
        'suspicious_activity' => $this->hasSuspiciousActivity(),
        'security_score' => $this->calculateSecurityScore(),
        'password_history_count' => $this->passwordHistories()->count(),
        'last_password_change' => $this->last_password_changed_at?->diffForHumans(),
        'last_security_activity' => $this->last_security_activity?->diffForHumans(),
        'setup_progress' => $this->getSecuritySetupProgress(),
        'requires_setup' => $this->requiresSecuritySetup(),
        'recommendations' => $this->getSecurityRecommendations(),
    ];
}

/**
 * Get quick security status for dashboard
 */
public function getQuickSecurityStatus(): array
{
    $overview = $this->getSecurityOverview();

    return [
        'score' => $overview['security_score'],
        'level' => $overview['security_level'],
        'color' => $overview['security_level_color'],
        'meets_requirements' => $overview['meets_requirements'],
        'critical_issues' => array_filter($overview['recommendations'], function($rec) {
            return $rec['priority'] === 'high';
        }),
        'setup_progress' => $overview['setup_progress'],
        'requires_attention' => $overview['requires_setup'],
    ];
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
     * Scope for verified employers
     */
    public function scopeVerifiedEmployers($query)
    {
        return $query->where('employer_verification_status', 'verified')
                    ->whereNotNull('employer_verified_at')
                    ->where(function($q) {
                        $q->whereNull('verification_expires_at')
                          ->orWhere('verification_expires_at', '>', now());
                    });
    }

    /**
     * Scope for pending verification employers
     */
    public function scopePendingVerificationEmployers($query)
    {
        return $query->where('employer_verification_status', 'pending')
                    ->whereNotNull('verification_documents');
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
        $verifiedEmployers = self::verifiedEmployers()->count();
        $pendingEmployers = self::pendingVerificationEmployers()->count();
        $activeUsers = self::active()->count();
        $lockedUsers = self::locked()->count();

        return [
            'total_users' => $totalUsers,
            'pwd_users' => $pwdUsers,
            'admin_users' => $adminUsers,
            'employer_users' => $employerUsers,
            'verified_employers' => $verifiedEmployers,
            'pending_employers' => $pendingEmployers,
            'regular_users' => $totalUsers - $pwdUsers - $adminUsers - $employerUsers,
            'active_users' => $activeUsers,
            'inactive_users' => $totalUsers - $activeUsers,
            'locked_users' => $lockedUsers,
            'pwd_percentage' => $totalUsers > 0 ? round(($pwdUsers / $totalUsers) * 100, 2) : 0,
            'admin_percentage' => $totalUsers > 0 ? round(($adminUsers / $totalUsers) * 100, 2) : 0,
            'employer_percentage' => $totalUsers > 0 ? round(($employerUsers / $totalUsers) * 100, 2) : 0,
            'verified_employer_percentage' => $employerUsers > 0 ? round(($verifiedEmployers / $employerUsers) * 100, 2) : 0,
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
    // Removed duplicated/simple security helper implementations here.
    // The file already contains the more comprehensive implementations
    // of `getSecurityOverview`, `calculateSecurityScore` and
    // `getSecurityRecommendations` earlier in the class. Keeping
    // those and deleting these duplicates avoids redeclaration errors.

    // ======================
    // RESUME METHODS
    // ======================

    /**
     * Get the resume URL
     */
    public function getResumeUrlAttribute()
    {
        return $this->resume ? Storage::url($this->resume) : null;
    }

    /**
     * Check if user has a resume
     */
    public function hasResume(): bool
    {
        return !empty($this->resume) && Storage::disk('public')->exists($this->resume);
    }

    /**
     * Get the file name of the resume
     */
    public function getResumeFileNameAttribute(): ?string
    {
        return $this->resume ? basename($this->resume) : null;
    }

    /**
     * Get the resume file size in human readable format
     */
    public function getResumeFileSizeAttribute(): ?string
    {
        if (!$this->resume) {
            return null;
        }

        try {
            $size = Storage::disk('public')->size($this->resume);
            $units = ['B', 'KB', 'MB', 'GB'];

            for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
                $size /= 1024;
            }

            return round($size, 2) . ' ' . $units[$i];
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Get resume file type
     */
    public function getResumeFileTypeAttribute(): ?string
    {
        if (!$this->resume) {
            return null;
        }

        $extension = pathinfo($this->resume, PATHINFO_EXTENSION);
        return strtoupper($extension);
    }

    /**
     * Check if user can upload resume (PWD users and employers)
     */
    public function canUploadResume(): bool
    {
        return $this->isPwd() || $this->isEmployer();
    }

    /**
     * Get resume status for display
     */
    public function getResumeStatusAttribute(): array
    {
        if (!$this->hasResume()) {
            return [
                'status' => 'not_uploaded',
                'text' => 'No Resume',
                'class' => 'text-muted',
                'icon' => 'fas fa-times-circle'
            ];
        }

        return [
            'status' => 'uploaded',
            'text' => 'Resume Available',
            'class' => 'text-success',
            'icon' => 'fas fa-check-circle'
        ];
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
        if ($this->isPwd()) {
            if (!$this->pwdProfile) {
                return false;
            }
            $basicComplete = !empty($this->pwdProfile->disability_type) &&
                            !empty($this->pwdProfile->skills) &&
                            !empty($this->phone) &&
                            !empty($this->address);

            // For PWD users, resume is important but not mandatory for basic completion
            return $basicComplete;
        }

        if ($this->isEmployer()) {
            // For employers, basic profile completion
            return !empty($this->name) &&
                   !empty($this->email) &&
                   !empty($this->phone) &&
                   !empty($this->address);
        }

        // For regular users
        return !empty($this->name) && !empty($this->email);
    }

    /**
     * Get profile completion percentage including resume
     */
    public function getProfileCompletionPercentage(): int
    {
        // Required checks for PWD users
        $requiredChecks = [
            'name' => !empty($this->name),
            'email' => !empty($this->email),
            'phone' => !empty($this->phone),
            'address' => !empty($this->address),
        ];

        if ($this->isPwd()) {
            if ($this->pwdProfile) {
                $requiredChecks['pwd_disability_type'] = !empty($this->pwdProfile->disability_type_id) || !empty($this->pwdProfile->disability_type);
                $requiredChecks['pwd_skills'] = !empty($this->pwdProfile->skills);
                $requiredChecks['pwd_phone'] = !empty($this->pwdProfile->phone) || !empty($this->phone);
                $requiredChecks['pwd_address'] = !empty($this->pwdProfile->address) || !empty($this->address);
            } else {
                $requiredChecks['pwd_disability_type'] = false;
                $requiredChecks['pwd_skills'] = false;
                $requiredChecks['pwd_phone'] = false;
                $requiredChecks['pwd_address'] = false;
            }

            // Resume is required for applying
            $requiredChecks['resume'] = $this->hasResume();

            $optionalChecks = [
                'documents' => $this->documents()->count() > 0,
                'experience_or_skills' => !empty($this->experience) || !empty($this->skills),
                'profile_photo' => (!empty($this->pwdProfile) && !empty($this->pwdProfile->profile_photo)),
            ];

            $requiredTotal = count($requiredChecks);
            $requiredCompleted = collect($requiredChecks)->filter()->count();

            $optionalTotal = count($optionalChecks);
            $optionalCompleted = collect($optionalChecks)->filter()->count();

            $requiredScore = $requiredTotal ? ($requiredCompleted / $requiredTotal) * 80 : 80;
            $optionalScore = $optionalTotal ? ($optionalCompleted / $optionalTotal) * 20 : 0;

            $percentage = $requiredScore + $optionalScore;

            return (int) round(min(100, $percentage));
        }

        // Fallback for non-PWD users: basic fields
        $basicTotal = count($requiredChecks);
        $basicCompleted = collect($requiredChecks)->filter()->count();

        return $basicTotal > 0 ? (int) round(($basicCompleted / $basicTotal) * 100) : 0;
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

    /**
     * Send an email notification to the user when their employer verification status changes.
     *
     * @param string $status  One of: 'approved', 'rejected', 'kept' (case-insensitive)
     * @param string|null $reason Optional admin-provided reason or note
     * @return void
     */
    public function notifyVerificationStatusChanged(string $status, ?string $reason = null): void
    {
        try {
            $s = strtolower(trim($status));
            switch ($s) {
                case 'approved':
                case 'verified':
                    $this->notify(new \App\Notifications\EmployerVerificationApproved($reason));
                    break;
                case 'rejected':
                    $this->notify(new \App\Notifications\EmployerVerificationRejected($reason));
                    break;
                case 'kept':
                case 'keep':
                case 'nochange':
                    $this->notify(new \App\Notifications\EmployerVerificationKept($reason));
                    break;
                default:
                    // Unknown status — log for debugging
                    Log::info("Employer verification notification: unknown status {$status} for user {$this->id}");
            }
        } catch (\Exception $e) {
            Log::error('Failed to send employer verification notification', ['user_id' => $this->id, 'status' => $status, 'error' => $e->getMessage()]);
        }
    }

}
