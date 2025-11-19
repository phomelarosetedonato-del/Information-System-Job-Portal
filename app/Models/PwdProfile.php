<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property int|null $disability_type_id
 * @property string|null $disability_type
 * @property string|null $disability_severity
 * @property array|null $assistive_devices
 * @property string|null $skills
 * @property string|null $qualifications
 * @property string|null $phone
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $birthdate
 * @property string|null $gender
 * @property string|null $special_needs
 * @property bool|null $is_employed
 * @property array|null $accessibility_needs
 * @property string|null $emergency_contact_name
 * @property string|null $emergency_contact_phone
 * @property string|null $emergency_contact_relationship
 * @property string|null $profile_photo
 * @property string|null $pwd_id_number
 * @property string|null $pwd_id_photo
 * @property bool $profile_completed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \App\Models\DisabilityType|null $disabilityType
 */
class PwdProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'disability_type_id',
        'disability_type',
        'disability_severity',
        'assistive_devices',
        'skills',
        'qualifications',
        'phone',
        'address',
        'birthdate',
        'gender',
        'special_needs',
        'is_employed',
        // New columns
        'accessibility_needs',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relationship',
        'profile_photo',
        'pwd_id_number',
        'pwd_id_photo',
        'profile_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'birthdate' => 'datetime',
        'is_employed' => 'boolean',
        'profile_completed' => 'boolean',
        'assistive_devices' => 'array',
        'accessibility_needs' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship with user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship to DisabilityType (if set)
     */
    public function disabilityType()
    {
        return $this->belongsTo(\App\Models\DisabilityType::class, 'disability_type_id');
    }

    /**
     * Get the profile photo URL
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo ? Storage::url($this->profile_photo) : null;
    }

    /**
     * Get the PWD ID photo URL
     */
    public function getPwdIdPhotoUrlAttribute()
    {
        return $this->pwd_id_photo ? Storage::url($this->pwd_id_photo) : null;
    }

    /**
     * Check if the profile has a profile photo
     */
    public function getHasProfilePhotoAttribute()
    {
        return !empty($this->profile_photo);
    }

    /**
     * Check if the profile has a PWD ID photo
     */
    public function getHasPwdIdPhotoAttribute()
    {
        return !empty($this->pwd_id_photo);
    }

    /**
     * Get the user's age from birthdate
     */
    public function getAgeAttribute()
    {
        return $this->birthdate ? $this->birthdate->diffInYears(now()) : null;
    }

    /**
     * Scope a query to only include completed profiles.
     */
    public function scopeCompleted($query)
    {
        return $query->where('profile_completed', true);
    }

    /**
     * Scope a query to only include employed PWDs.
     */
    public function scopeEmployed($query)
    {
        return $query->where('is_employed', true);
    }
}
