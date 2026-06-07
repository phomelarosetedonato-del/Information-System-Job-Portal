<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DisabilityType extends Model
{
    protected $fillable = [
        'type', 'name', 'is_active'
    ];

    public static function activeTypes()
    {
        return self::where('is_active', true)->get();
    }
}
