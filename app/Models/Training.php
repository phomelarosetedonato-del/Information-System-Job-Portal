<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'duration',
        'mode',
        'fee',
        'is_free'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'is_free' => 'boolean',
    ];
}
