<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationInterview extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'scheduled_at',
        'interview_type',
        'location',
        'interviewers',
        'notes',
        'duration',
        'status',
        'feedback',
        'rating'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'interviewers' => 'array',
        'duration' => 'integer'
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }
}
