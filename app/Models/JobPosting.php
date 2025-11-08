<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str; // Fixed: Changed from App\Models\Str to Illuminate\Support\Str

class JobPosting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'requirements', 'location', 'company',
        'salary_min', 'salary_max', 'employment_type', 'application_deadline',
        'is_active', 'created_by', 'contact_email', 'contact_phone',
        'job_category', 'experience_level', 'views',
        // Accessibility fields
        'is_remote', 'provides_accommodations', 'accessibility_features', 'assistive_technology'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'application_deadline' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    /**
     * Additional casts for accessibility fields (if present)
     */
    protected $attributes = [
        'is_remote' => false,
        'provides_accommodations' => false,
    ];

    /**
     * The disability types that are suitable for this job
     */
    public function suitableDisabilityTypes(): BelongsToMany
    {
        return $this->belongsToMany(DisabilityType::class, 'job_posting_disability_types');
    }

    /**
     * Scope to filter by disability types
     */
    public function scopeForDisabilityTypes($query, $disabilityTypeIds)
    {
        return $query->whereHas('suitableDisabilityTypes', function ($q) use ($disabilityTypeIds) {
            $q->whereIn('disability_types.id', $disabilityTypeIds);
        });
    }

    /**
     * Scope to include jobs with no specific disability requirements
     */
    public function scopeOrNoDisabilityRequirements($query)
    {
        return $query->orWhereDoesntHave('suitableDisabilityTypes');
    }

    /**
     * Accessor for formatted salary display
     */
    public function getFormattedSalaryAttribute()
    {
        if ($this->salary_min && $this->salary_max) {
            return '₱' . number_format((float) $this->salary_min) . ' - ₱' . number_format((float) $this->salary_max);
        } elseif ($this->salary_min) {
            return '₱' . number_format((float) $this->salary_min) . ' and above';
        } elseif ($this->salary_max) {
            return 'Up to ₱' . number_format((float) $this->salary_max);
        }

        return 'Not specified';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }



    /**
 * Alias for creator relationship (for compatibility)
 */
public function employer()
{
    return $this->creator();
}
    /**
     * Scope for active job postings
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for job postings with future deadline
     */
    public function scopeOpen($query)
    {
        return $query->where(function($q) {
            $q->where('application_deadline', '>=', now())
              ->orWhereNull('application_deadline');
        });
    }

    /**
     * Scope for featured jobs (most viewed)
     */
    public function scopeFeatured($query, $limit = 5)
    {
        return $query->orderBy('views', 'desc')->take($limit);
    }

    /**
     * Scope for recent jobs
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderBy('created_at', 'desc')->take($limit);
    }

    /**
     * Check if job posting is still open for applications
     */
    public function getIsOpenAttribute()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->application_deadline) {
            return $this->application_deadline->isFuture();
        }

        return true;
    }

    /**
     * Get formatted application deadline - SAFE VERSION
     */
    public function getFormattedDeadlineAttribute()
    {
        if (!$this->application_deadline) {
            return 'No deadline';
        }

        // Ensure it's a Carbon instance before formatting
        $deadline = $this->application_deadline;
        if ($deadline instanceof \Carbon\Carbon) {
            return $deadline->format('F j, Y');
        }

        // If it's a string, convert to Carbon first
        return Carbon::parse($deadline)->format('F j, Y');
    }

    /**
     * Check if deadline is past - SAFE VERSION
     */
    public function getIsDeadlinePastAttribute()
    {
        if (!$this->application_deadline) {
            return false;
        }

        $deadline = $this->application_deadline;
        if ($deadline instanceof \Carbon\Carbon) {
            return $deadline->isPast();
        }

        return Carbon::parse($deadline)->isPast();
    }

    /**
     * Check if deadline is future - SAFE VERSION
     */
    public function getIsDeadlineFutureAttribute()
    {
        if (!$this->application_deadline) {
            return true; // No deadline means it's always "future"
        }

        $deadline = $this->application_deadline;
        if ($deadline instanceof \Carbon\Carbon) {
            return $deadline->isFuture();
        }

        return Carbon::parse($deadline)->isFuture();
    }

    /**
     * Get days until deadline
     */
    public function getDaysUntilDeadlineAttribute()
    {
        if (!$this->application_deadline) {
            return null;
        }

        $deadline = $this->application_deadline;
        if (!$deadline instanceof \Carbon\Carbon) {
            $deadline = Carbon::parse($deadline);
        }

        return $deadline->diffInDays(now());
    }

    /**
     * Get application status badge
     */
    public function getStatusBadgeAttribute()
    {
        if (!$this->is_active) {
            return '<span class="badge bg-secondary">Inactive</span>';
        }

        if (!$this->is_open) {
            return '<span class="badge bg-danger">Closed</span>';
        }

        return '<span class="badge bg-success">Open</span>';
    }

    /**
     * Get human readable deadline
     */
    public function getDeadlineHumanAttribute()
    {
        if (!$this->application_deadline) {
            return 'No deadline';
        }

        $deadline = $this->application_deadline;
        if (!$deadline instanceof \Carbon\Carbon) {
            $deadline = Carbon::parse($deadline);
        }

        return $deadline->diffForHumans();
    }

    /**
     * Get short description (truncated)
     */
    public function getShortDescriptionAttribute()
    {
        return Str::limit(strip_tags($this->description), 150);
    }

    /**
     * Get short requirements (truncated)
     */
    public function getShortRequirementsAttribute()
    {
        return Str::limit(strip_tags($this->requirements), 150);
    }

    /**
     * Check if job is urgent (deadline within 3 days)
     */
    public function getIsUrgentAttribute()
    {
        if (!$this->application_deadline) {
            return false;
        }

        $deadline = $this->application_deadline;
        if (!$deadline instanceof \Carbon\Carbon) {
            $deadline = Carbon::parse($deadline);
        }

        return $deadline->isFuture() && $deadline->diffInDays(now()) <= 3;
    }

    /**
     * Increment views count
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Get related jobs (same company or category)
     */
    public function getRelatedJobsAttribute()
    {
        return self::where('is_active', true)
            ->where('id', '!=', $this->id)
            ->where(function($query) {
                $query->where('company', $this->company)
                      ->orWhere('job_category', $this->job_category)
                      ->orWhere('employment_type', $this->employment_type);
            })
            ->take(4)
            ->get();
    }
}
