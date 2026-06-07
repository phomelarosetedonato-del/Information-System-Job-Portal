<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'is_active'];

    public static function activeLocations()
    {
        return self::where('is_active', true)->orderBy('name')->get();
    }
}
