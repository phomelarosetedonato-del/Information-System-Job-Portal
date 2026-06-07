<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $year
 * @property string $disability_type
 * @property int $unemployed_count
 * @property int $employed_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class CommunityPwdStat extends Model
{
    use HasFactory;

    protected $table = 'community_pwd_stats';

    protected $fillable = [
        'year',
        'disability_type',
        'unemployed_count',
        'employed_count',
    ];

    protected $casts = [
        'year' => 'integer',
        'disability_type' => 'string',
        'unemployed_count' => 'integer',
        'employed_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get total PWD count (unemployed + employed)
     */
    public function getTotalCount()
    {
        return $this->unemployed_count + $this->employed_count;
    }

    /**
     * Get employment rate percentage
     */
    public function getEmploymentRate()
    {
        $total = $this->getTotalCount();
        if ($total == 0) {
            return 0;
        }
        return round(($this->employed_count / $total) * 100, 2);
    }
}
