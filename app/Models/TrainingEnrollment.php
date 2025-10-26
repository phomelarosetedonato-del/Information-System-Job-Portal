<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Notifications\TrainingEnrollmentSubmitted;
use App\Notifications\TrainingEnrollmentStatusUpdated;
use App\Notifications\NewTrainingEnrollmentAdminNotification;

class TrainingEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'skill_training_id', 'status', 'notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skillTraining()
    {
        return $this->belongsTo(SkillTraining::class);
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * When a new training enrollment is created
         */
        static::created(function ($enrollment) {
            try {
                // Notify the user
                $enrollment->user->notify(new TrainingEnrollmentSubmitted($enrollment));

                // Notify all admins about the new enrollment
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new NewTrainingEnrollmentAdminNotification($enrollment));
                }
            } catch (\Exception $e) {
                // Log error but don't break the application
                Log::error('Error sending training enrollment notifications: ' . $e->getMessage());
            }
        });

        /**
         * When a training enrollment is updated
         */
        static::updated(function ($enrollment) {
            try {
                // Check if status was changed
                if ($enrollment->isDirty('status')) {
                    $oldStatus = $enrollment->getOriginal('status');
                    $newStatus = $enrollment->status;

                    // Notify the user about status change
                    $enrollment->user->notify(new TrainingEnrollmentStatusUpdated($enrollment, $oldStatus, $newStatus));
                }
            } catch (\Exception $e) {
                // Log error but don't break the application
                Log::error('Error sending training enrollment status update notification: ' . $e->getMessage());
            }
        });
    }

    /**
     * Scope for pending enrollments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved enrollments
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected enrollments
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for completed enrollments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Get the status with badge color
     */
    public function getStatusBadgeAttribute()
    {
        $statusColors = [
            'pending' => 'secondary',
            'approved' => 'success',
            'rejected' => 'danger',
            'completed' => 'info',
            'cancelled' => 'warning'
        ];

        $color = $statusColors[$this->status] ?? 'secondary';

        return '<span class="badge badge-' . $color . '">' . ucfirst($this->status) . '</span>';
    }

    /**
     * Check if enrollment is pending
     */
    public function getIsPendingAttribute()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if enrollment is approved
     */
    public function getIsApprovedAttribute()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if enrollment is rejected
     */
    public function getIsRejectedAttribute()
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if training has started
     */
    public function getHasTrainingStartedAttribute()
    {
        return $this->skillTraining && $this->skillTraining->start_date <= now();
    }

    /**
     * Check if training has ended
     */
    public function getHasTrainingEndedAttribute()
    {
        return $this->skillTraining && $this->skillTraining->end_date < now();
    }

    /**
     * Get enrollment date in readable format
     */
    public function getEnrolledDateAttribute()
    {
        return $this->created_at->format('F j, Y');
    }

    /**
     * Get enrollment date in relative format
     */
    public function getEnrolledAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Check if user can cancel enrollment
     */
    public function getCanCancelAttribute()
    {
        return $this->is_pending &&
               $this->skillTraining &&
               $this->skillTraining->start_date > now();
    }
}
