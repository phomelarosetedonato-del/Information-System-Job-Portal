<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_application_id',
        'from_status',
        'to_status',
        'changed_by',
        'notes',
        'reason'
    ];

    protected $casts = [
        'changed_at' => 'datetime'
    ];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'job_application_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($history) {
            $history->changed_at = now();
        });
    }
}
