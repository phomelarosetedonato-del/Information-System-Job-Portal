<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property string $surname
 * @property string $first_name
 * @property string|null $middle_name
 * @property \Illuminate\Support\Carbon $date_of_birth
 * @property string $sex
 * @property string $mobile_number
 * @property string $email_address
 * @property string $province
 * @property string|null $complete_address
 * @property string|null $professional_summary
 * @property string|null $career_objective
 * @property string $educational_attainment
 * @property string|null $course
 * @property string|null $school_name
 * @property string|null $school_address
 * @property int|null $year_graduated
 * @property array|null $additional_education
 * @property array|null $eligibility
 * @property array|null $work_experience
 * @property array|null $trainings
 * @property array|null $skills
 * @property array|null $languages
 * @property string|null $profile_photo
 * @property array|null $personal_documents
 * @property array|null $supporting_documents
 * @property string|null $application_letter
 * @property bool $is_published
 * @property bool $is_searchable
 * @property string|null $visibility
 * @property string|null $template
 * @property array|null $customization
 * @property int $views_count
 * @property \Illuminate\Support\Carbon|null $last_updated_at
 * @property bool $is_complete
 * @property int $completion_percentage
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User $user
 */
class Resume extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'surname',
        'first_name',
        'middle_name',
        'date_of_birth',
        'sex',
        'mobile_number',
        'email_address',
        'province',
        'complete_address',
        'professional_summary',
        'career_objective',
        'educational_attainment',
        'course',
        'school_name',
        'school_address',
        'year_graduated',
        'additional_education',
        'eligibility',
        'work_experience',
        'trainings',
        'skills',
        'languages',
        'profile_photo',
        'personal_documents',
        'supporting_documents',
        'application_letter',
        'is_published',
        'is_searchable',
        'visibility',
        'template',
        'customization',
        'views_count',
        'last_updated_at',
        'is_complete',
        'completion_percentage',
    ];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'year_graduated' => 'integer',
        'additional_education' => 'array',
        'eligibility' => 'array',
        'work_experience' => 'array',
        'trainings' => 'array',
        'skills' => 'array',
        'languages' => 'array',
        'personal_documents' => 'array',
        'supporting_documents' => 'array',
        'customization' => 'array',
        'is_published' => 'boolean',
        'is_searchable' => 'boolean',
        'is_complete' => 'boolean',
        'views_count' => 'integer',
        'completion_percentage' => 'integer',
        'last_updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->first_name} {$this->middle_name} {$this->surname}");
    }

    /**
     * Get profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo && Storage::disk('public')->exists($this->profile_photo)) {
            return Storage::url($this->profile_photo);
        }
        return asset('images/default-avatar.png');
    }

    /**
     * Calculate completion percentage
     */
    public function calculateCompletionPercentage()
    {
        $fields = [
            'surname' => 5,
            'first_name' => 5,
            'date_of_birth' => 5,
            'sex' => 5,
            'mobile_number' => 5,
            'email_address' => 5,
            'province' => 5,
            'complete_address' => 5,
            'professional_summary' => 10,
            'educational_attainment' => 10,
            'course' => 5,
            'work_experience' => 15,
            'skills' => 10,
            'profile_photo' => 10,
            'application_letter' => 5,
        ];

        $earned = 0;
        $total = array_sum($fields);

        foreach ($fields as $field => $weight) {
            if (!empty($this->$field)) {
                if (is_array($this->$field)) {
                    if (count($this->$field) > 0) {
                        $earned += $weight;
                    }
                } else {
                    $earned += $weight;
                }
            }
        }

        return round(($earned / $total) * 100);
    }

    /**
     * Update completion status
     */
    public function updateCompletionStatus()
    {
        $percentage = $this->calculateCompletionPercentage();
        $this->completion_percentage = $percentage;
        $this->is_complete = $percentage >= 80;
        $this->last_updated_at = now();
        $this->save();
    }

    /**
     * Increment views
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Check if resume can be published
     */
    public function canBePublished()
    {
        return $this->completion_percentage >= 80;
    }

    /**
     * Publish resume
     */
    public function publish()
    {
        if ($this->canBePublished()) {
            $this->is_published = true;
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Unpublish resume
     */
    public function unpublish()
    {
        $this->is_published = false;
        $this->save();
    }

    /**
     * Get age from date of birth
     */
    public function getAgeAttribute()
    {
        return $this->date_of_birth ? $this->date_of_birth->diffInYears(now()) : null;
    }

    /**
     * Scope: Published resumes
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Complete resumes
     */
    public function scopeComplete($query)
    {
        return $query->where('is_complete', true);
    }

    /**
     * Scope: Searchable resumes
     */
    public function scopeSearchable($query)
    {
        return $query->where('is_searchable', true)
                     ->where('is_published', true);
    }
}
