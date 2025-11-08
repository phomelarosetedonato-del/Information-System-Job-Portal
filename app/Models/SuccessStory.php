<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'job_title',
        'company',
        'story',
        'image',
        'salary_increase',
        'is_published',
        'featured',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'featured' => 'boolean',
    ];
}
