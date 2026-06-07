<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkArrangementOption extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'active'];

    public $timestamps = false;

    public static function active()
    {
        return static::where('active', true);
    }
}
