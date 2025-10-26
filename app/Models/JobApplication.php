<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Notifications\JobApplicationSubmitted;
use App\Notifications\ApplicationStatusUpdated;
use App\Notifications\NewApplicationAdminNotification;

class JobApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'job_posting_id', 'status', 'cover_letter', 'admin_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class);
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * When a new job application is created
         */
        static::created(function ($application) {
            try {
                // Notify the applicant
                $application->user->notify(new JobApplicationSubmitted($application));

                // Notify all admins about the new application
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new NewApplicationAdminNotification($application));
                }
            } catch (\Exception $e) {
                // Log error but don't break the application
                Log::error('Error sending job application notifications: ' . $e->getMessage());
            }
        });

        /**
         * When a job application is updated
         */
        static::updated(function ($application) {
            try {
                // Check if status was changed
                if ($application->isDirty('status')) {
                    $oldStatus = $application->getOriginal('status');
                    $newStatus = $application->status;

                    // Notify the applicant about status change
                    $application->user->notify(new ApplicationStatusUpdated($application, $oldStatus, $newStatus));
                }
            } catch (\Exception $e) {
                // Log error but don't break the application
                Log::error('Error sending application status update notification: ' . $e->getMessage());
            }
        });
    }

    /**
     * Scope for pending applications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for reviewed applications
     */
    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }

    /**
     * Scope for shortlisted applications
     */
    public function scopeShortlisted($query)
    {
        return $query->where('status', 'shortlisted');
    }

    /**
     * Scope for accepted applications
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope for rejected applications
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Get the status with badge color
     */
    public function getStatusBadgeAttribute()
    {
        $statusColors = [
            'pending' => 'secondary',
            'reviewed' => 'info',
            'shortlisted' => 'warning',
            'interview' => 'primary',
            'accepted' => 'success',
            'rejected' => 'danger'
        ];

        $color = $statusColors[$this->status] ?? 'secondary';

        return '<span class="badge badge-' . $color . '">' . ucfirst($this->status) . '</span>';
    }

    /**
     * Check if application is pending
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application is accepted
     */
    public function getIsAcceptedAttribute()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if application is rejected
     */
    public function getIsRejectedAttribute()
    {
        return $this->status === 'rejected';
    }

    /**
     * Get application date in readable format
     */
    public function getAppliedDateAttribute()
    {
        return $this->created_at->format('F j, Y');
    }

    /**
     * Get application date in relative format
     */
    public function getAppliedAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
