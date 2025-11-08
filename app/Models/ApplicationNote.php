<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'note',
        'type',
        'added_by',
        'is_private'
    ];

    protected $casts = [
        'is_private' => 'boolean'
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'added_by');
    }
}
