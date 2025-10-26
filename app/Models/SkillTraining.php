<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SkillTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'objectives', 'trainer', 'start_date',
        'end_date', 'location', 'max_participants', 'is_active', 'created_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function enrollments()
    {
        return $this->hasMany(TrainingEnrollment::class);
    }

    /**
     * Scope for active trainings
     */
    /**
 * Get training status badge for display
 */
public function getStatusBadgeAttribute()
{
    if (!$this->is_active) {
        return '<span class="badge bg-secondary">Inactive</span>';
    }

    if ($this->is_completed) {
        return '<span class="badge bg-info">Completed</span>';
    }

    if ($this->is_ongoing) {
        return '<span class="badge bg-success">Ongoing</span>';
    }

    if ($this->is_upcoming) {
        return '<span class="badge bg-primary">Upcoming</span>';
    }

    return '<span class="badge bg-secondary">Unknown</span>';
}
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for upcoming trainings
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }

    /**
     * Scope for ongoing trainings
     */
    public function scopeOngoing($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    /**
     * Scope for completed trainings
     */
    public function scopeCompleted($query)
    {
        return $query->where('end_date', '<', now());
    }

    /**
     * Check if training is upcoming
     */
    public function getIsUpcomingAttribute()
    {
        return $this->start_date > now();
    }

    /**
     * Check if training is ongoing
     */
    public function getIsOngoingAttribute()
    {
        return $this->start_date <= now() && $this->end_date >= now();
    }

    /**
     * Check if training is completed
     */
    public function getIsCompletedAttribute()
    {
        return $this->end_date < now();
    }

    /**
     * Get available slots
     */
    public function getAvailableSlotsAttribute()
    {
        $currentEnrollments = $this->enrollments()
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        return max(0, $this->max_participants - $currentEnrollments);
    }

    /**
     * Check if training is full
     */
    public function getIsFullAttribute()
    {
        return $this->available_slots === 0;
    }

    /**
     * Get training duration in days
     */
    public function getDurationDaysAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    /**
     * Get formatted date range
     */
    public function getDateRangeAttribute()
    {
        if ($this->start_date->format('F Y') === $this->end_date->format('F Y')) {
            return $this->start_date->format('F j') . ' - ' . $this->end_date->format('j, Y');
        }

        return $this->start_date->format('F j, Y') . ' - ' . $this->end_date->format('F j, Y');
    }

    /**
     * Get formatted start date
     */
    public function getFormattedStartDateAttribute()
    {
        return $this->start_date->format('F j, Y');
    }

    /**
     * Get formatted end date
     */
    public function getFormattedEndDateAttribute()
    {
        return $this->end_date->format('F j, Y');
    }

    /**
     * Check if training has started
     */
    public function getHasStartedAttribute()
    {
        return $this->start_date <= now();
    }

    /**
     * Check if training has ended
     */
    public function getHasEndedAttribute()
    {
        return $this->end_date < now();
    }
}
