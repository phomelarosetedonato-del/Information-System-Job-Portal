<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'job_posting_id', 'status', 'cover_letter', 'admin_notes',
        'applied_at', 'status_updated_at', 'reviewed_by', 'reviewed_at',
        'rejection_reason', 'next_steps', 'rating', 'resume_path', 'applied_via',
        'ip_address', 'user_agent', 'interview_scheduled_at', 'interview_notes',
        'viewed_at', 'withdrawn_at'
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'status_updated_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'interview_scheduled_at' => 'datetime',
        'viewed_at' => 'datetime',
        'withdrawn_at' => 'datetime',
        'rating' => 'integer',
    ];

    /**
     * Relationships
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function statusHistory(): HasMany
    {
        // Use the actual model class directly - it should exist or be created
        return $this->hasMany(ApplicationStatusHistory::class, 'job_application_id');
    }

    public function notes(): HasMany
    {
        // Use the actual model class directly - it should exist or be created
        return $this->hasMany(ApplicationNote::class, 'job_application_id');
    }

    public function interviews(): HasMany
    {
        // Use the actual model class directly - it should exist or be created
        return $this->hasMany(ApplicationInterview::class, 'job_application_id');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeHired($query)
    {
        return $query->where('status', 'hired');
    }

    public function scopeInterviewScheduled($query)
    {
        return $query->where('status', 'interview_scheduled');
    }

    public function scopeWithdrawn($query)
    {
        return $query->where('status', 'withdrawn');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeNeedsReview($query)
    {
        return $query->where('status', 'pending')
                    ->where('created_at', '<=', now()->subDays(2));
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByJobPosting($query, $jobPostingId)
    {
        return $query->where('job_posting_id', $jobPostingId);
    }

    /**
     * Accessors
     */
    public function getStatusBadgeAttribute(): string
    {
        $statusColors = [
            'pending' => 'warning',
            'reviewed' => 'info',
            'shortlisted' => 'primary',
            'interview_scheduled' => 'info',
            'approved' => 'success',
            'hired' => 'success',
            'rejected' => 'danger',
            'withdrawn' => 'secondary'
        ];

        $color = $statusColors[$this->status] ?? 'secondary';
        $icon = $this->getStatusIcon();

        return '<span class="badge bg-' . $color . '">
                <i class="fas fa-' . $icon . ' me-1"></i>' .
                ucfirst($this->status) .
                '</span>';
    }

    public function getStatusIcon(): string
    {
        $icons = [
            'pending' => 'clock',
            'reviewed' => 'eye',
            'shortlisted' => 'list',
            'interview_scheduled' => 'calendar-alt',
            'approved' => 'check-circle',
            'hired' => 'trophy',
            'rejected' => 'times-circle',
            'withdrawn' => 'ban'
        ];

        return $icons[$this->status] ?? 'circle';
    }

    public function getResponseTimeAttribute(): ?int
    {
        if (!$this->reviewed_at) {
            return null;
        }

        return $this->created_at->diffInDays($this->reviewed_at);
    }

    public function getIsRespondedAttribute(): bool
    {
        return in_array($this->status, ['shortlisted', 'approved', 'rejected', 'hired']);
    }

    public function getDaysSinceAppliedAttribute(): int
    {
        return $this->created_at->diffInDays(now());
    }

    public function getAppliedDateFormattedAttribute(): string
    {
        return $this->created_at->format('M j, Y');
    }

    public function getAppliedTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    public function getCanWithdrawAttribute(): bool
    {
        return in_array($this->status, ['pending', 'shortlisted']);
    }

    public function getHasInterviewAttribute(): bool
    {
        return !is_null($this->interview_scheduled_at);
    }

    public function getApplicantNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'Unknown Applicant';
    }

    public function getJobTitleAttribute(): string
    {
        return $this->jobPosting ? $this->jobPosting->title : 'Unknown Job';
    }

    public function getCompanyNameAttribute(): string
    {
        return $this->jobPosting ? $this->jobPosting->company : 'Unknown Company';
    }

    /**
     * Business Logic Methods
     */
    public function canBeWithdrawn(): bool
    {
        return in_array($this->status, ['pending', 'shortlisted', 'interview_scheduled']);
    }

    public function canBeResubmitted(): bool
    {
        return in_array($this->status, ['rejected', 'withdrawn']) &&
               $this->created_at->diffInDays(now()) < 30;
    }

    public function markAsViewed(): bool
    {
        if (!$this->viewed_at) {
            return $this->update(['viewed_at' => now()]);
        }
        return false;
    }

    public function withdraw(): bool
    {
        if ($this->canBeWithdrawn()) {
            return $this->update([
                'status' => 'withdrawn',
                'withdrawn_at' => now(),
                'status_updated_at' => now(),
            ]);
        }
        return false;
    }

    public function scheduleInterview($interviewDate, $notes = null): bool
    {
        return $this->update([
            'status' => 'interview_scheduled',
            'interview_scheduled_at' => $interviewDate,
            'interview_notes' => $notes,
            'status_updated_at' => now(),
        ]);
    }

    public function addToCalendar(): ?array
    {
        // Generate calendar event for interview
        if ($this->interview_scheduled_at && $this->jobPosting) {
            return [
                'title' => "Interview: {$this->jobPosting->title}",
                'start' => $this->interview_scheduled_at,
                'end' => $this->interview_scheduled_at->copy()->addHour(),
                'description' => $this->interview_notes,
                'location' => $this->jobPosting->location ?? 'Remote'
            ];
        }
        return null;
    }

    /**
     * Status transition methods
     */
    public function markAsShortlisted($reviewedBy = null): bool
    {
        return $this->updateStatus('shortlisted', $reviewedBy);
    }

    public function markAsApproved($reviewedBy = null): bool
    {
        return $this->updateStatus('approved', $reviewedBy);
    }

    public function markAsRejected($reason = null, $reviewedBy = null): bool
    {
        return $this->updateStatus('rejected', $reviewedBy, ['rejection_reason' => $reason]);
    }

    public function markAsHired($reviewedBy = null): bool
    {
        return $this->updateStatus('hired', $reviewedBy);
    }

    private function updateStatus(string $status, $reviewedBy = null, array $additionalData = []): bool
    {
        $data = array_merge([
            'status' => $status,
            'status_updated_at' => now(),
            'reviewed_at' => now(),
            'reviewed_by' => $reviewedBy ?? (Auth::check() ? Auth::id() : null),
        ], $additionalData);

        return $this->update($data);
    }

    /**
     * Check if application is in specific status
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isHired(): bool
    {
        return $this->status === 'hired';
    }

    public function isShortlisted(): bool
    {
        return $this->status === 'shortlisted';
    }

    public function isWithdrawn(): bool
    {
        return $this->status === 'withdrawn';
    }

    /**
     * Notification methods
     */
    public function sendStatusNotification(): bool
    {
        try {
            // Check if notification classes exist
            if (!class_exists('App\Notifications\ApplicationStatusUpdated')) {
                return false;
            }

            $oldStatus = $this->getOriginal('status') ?? 'pending';
            $newStatus = $this->status;

            $this->user->notify(new \App\Notifications\ApplicationStatusUpdated(
                $this,
                $oldStatus,
                $newStatus
            ));

            return true;
        } catch (\Exception $e) {
            Log::error('Error sending status notification: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Model Events with error handling
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($application) {
            if (empty($application->applied_at)) {
                $application->applied_at = now();
            }
            if (empty($application->status)) {
                $application->status = 'pending';
            }
        });

        static::created(function ($application) {
            try {
                // Check if notification classes exist
                if (!class_exists('App\Notifications\JobApplicationSubmitted')) {
                    return;
                }

                // Notify the applicant
                $application->user->notify(new \App\Notifications\JobApplicationSubmitted($application));

                // Notify admins if notification class exists
                if (class_exists('App\Notifications\NewApplicationAdminNotification')) {
                    $admins = User::where('role', 'admin')->get();
                    foreach ($admins as $admin) {
                        $admin->notify(new \App\Notifications\NewApplicationAdminNotification($application));
                    }
                }

                // Update job posting application count
                if ($application->jobPosting) {
                    $application->jobPosting->increment('application_count');
                }

            } catch (\Exception $e) {
                Log::error('Error in job application created event: ' . $e->getMessage());
            }
        });

        static::updated(function ($application) {
            try {
                if ($application->isDirty('status')) {
                    $oldStatus = $application->getOriginal('status');
                    $newStatus = $application->status;

                    // Create status history if we can
                    try {
                        $application->statusHistory()->create([
                            'from_status' => $oldStatus,
                            'to_status' => $newStatus,
                            'changed_by' => Auth::id() ?? $application->reviewed_by,
                            'notes' => $application->rejection_reason,
                        ]);
                    } catch (\Exception $e) {
                        // Silently fail if status history can't be created
                        Log::debug('Could not create status history: ' . $e->getMessage());
                    }

                    // Send status notification
                    $application->sendStatusNotification();
                }
            } catch (\Exception $e) {
                Log::error('Error in job application updated event: ' . $e->getMessage());
            }
        });
    }

    /**
     * Utility methods
     */
    public static function getStatusOptions(): array
    {
        return [
            'pending' => 'Pending',
            'shortlisted' => 'Shortlisted',
            'interview_scheduled' => 'Interview Scheduled',
            'approved' => 'Approved',
            'hired' => 'Hired',
            'rejected' => 'Rejected',
            'withdrawn' => 'Withdrawn'
        ];
    }

    public static function getStatusColors(): array
    {
        return [
            'pending' => 'warning',
            'shortlisted' => 'primary',
            'interview_scheduled' => 'info',
            'approved' => 'success',
            'hired' => 'success',
            'rejected' => 'danger',
            'withdrawn' => 'secondary'
        ];
    }

    public function getStatusText(): string
    {
        $options = self::getStatusOptions();
        return $options[$this->status] ?? ucfirst($this->status);
    }

    /**
     * Statistics methods
     */
    public static function getStatistics($userId = null): array
    {
        $query = $userId ? self::where('user_id', $userId) : self::query();

        $total = $query->count();
        $pending = $query->where('status', 'pending')->count();
        $approved = $query->where('status', 'approved')->count();
        $rejected = $query->where('status', 'rejected')->count();
        $hired = $query->where('status', 'hired')->count();

        return [
            'total' => $total,
            'pending' => $pending,
            'approved' => $approved,
            'rejected' => $rejected,
            'hired' => $hired,
            'success_rate' => $total > 0 ? round((($approved + $hired) / $total) * 100, 1) : 0,
        ];
    }
}
