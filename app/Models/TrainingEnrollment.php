<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Notifications\TrainingEnrollmentSubmitted;
use App\Notifications\TrainingEnrollmentStatusUpdated;
use App\Notifications\NewTrainingEnrollmentAdminNotification;

/**
 * @property int $id
 * @property int $user_id
 * @property int $skill_training_id
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $enrolled_at
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property int|null $reviewed_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\SkillTraining $skillTraining
 * @property-read \App\Models\User|null $reviewer
 */
class TrainingEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skill_training_id',
        'status',
        'notes',
        'enrolled_at',  // ADDED
        'reviewed_at',  // ADDED
        'reviewed_by'   // ADDED
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',  // ADDED
        'reviewed_at' => 'datetime'   // ADDED
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skillTraining()
    {
        return $this->belongsTo(SkillTraining::class);
    }

    // ADDED: Relationship for reviewer
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
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
                    $enrollment->user->notify(new TrainingEnrollmentStatusUpdated($enrollment, $newStatus));
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
     * Scope for cancelled enrollments - ADDED
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for active enrollments (pending or approved) - ADDED
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'approved']);
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
     * Check if enrollment is cancelled - ADDED
     */
    public function getIsCancelledAttribute()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if enrollment is completed - ADDED
     */
    public function getIsCompletedAttribute()
    {
        return $this->status === 'completed';
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
     * Check if training is ongoing - ADDED
     */
    public function getIsTrainingOngoingAttribute()
    {
        return $this->skillTraining &&
               $this->skillTraining->start_date <= now() &&
               $this->skillTraining->end_date >= now();
    }

    /**
     * Get enrollment date in readable format
     * UPDATED: Use enrolled_at instead of created_at
     */
    public function getEnrolledDateAttribute()
    {
        return $this->enrolled_at ? $this->enrolled_at->format('F j, Y') : $this->created_at->format('F j, Y');
    }

    /**
     * Get enrollment date in relative format
     * UPDATED: Use enrolled_at instead of created_at
     */
    public function getEnrolledAgoAttribute()
    {
        return $this->enrolled_at ? $this->enrolled_at->diffForHumans() : $this->created_at->diffForHumans();
    }

    /**
     * Get reviewed date in readable format - ADDED
     */
    public function getReviewedDateAttribute()
    {
        return $this->reviewed_at ? $this->reviewed_at->format('F j, Y') : 'Not reviewed';
    }

    /**
     * Get reviewed date in relative format - ADDED
     */
    public function getReviewedAgoAttribute()
    {
        return $this->reviewed_at ? $this->reviewed_at->diffForHumans() : null;
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

    /**
     * Check if enrollment can be deleted - ADDED
     */
    public function getCanDeleteAttribute()
    {
        return in_array($this->status, ['cancelled', 'rejected']);
    }

    /**
     * Check if enrollment has been reviewed - ADDED
     */
    public function getIsReviewedAttribute()
    {
        return !is_null($this->reviewed_at);
    }

    /**
     * Get reviewer name - ADDED
     */
    public function getReviewerNameAttribute()
    {
        return $this->reviewer ? $this->reviewer->name : 'Not reviewed';
    }

    /**
     * Get training title with fallback - ADDED
     */
    public function getTrainingTitleAttribute()
    {
        return $this->skillTraining ? $this->skillTraining->title : 'Training Not Found';
    }

    /**
     * Get user name with fallback - ADDED
     */
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : 'User Not Found';
    }

    /**
     * Get training dates - ADDED
     */
    public function getTrainingDatesAttribute()
    {
        if (!$this->skillTraining) {
            return 'N/A';
        }

        return $this->skillTraining->start_date->format('M d, Y') . ' - ' . $this->skillTraining->end_date->format('M d, Y');
    }

    /**
     * Get training location - ADDED
     */
    public function getTrainingLocationAttribute()
    {
        return $this->skillTraining ? $this->skillTraining->location : 'N/A';
    }
}
