<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
        'website',
        'is_partner',
        'is_active',
    ];

    protected $casts = [
        'is_partner' => 'boolean',
        'is_active' => 'boolean',
    ];
}
