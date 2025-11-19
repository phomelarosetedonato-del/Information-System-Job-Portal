<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $name
 * @property string $file_path
 * @property string|null $mime_type
 * @property int|null $size
 * @property string|null $description
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 */
class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'name', 'file_path', 'mime_type', 'size', 'description', 'is_verified'
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
