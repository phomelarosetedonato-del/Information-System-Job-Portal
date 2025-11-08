<?php

namespace App\Traits;

use App\Models\PasswordHistory;
use Illuminate\Support\Facades\Hash;

trait HasPasswordHistory
{
    /**
     * Boot the trait and add event listeners.
     *
     * @return void
     */
    public static function bootHasPasswordHistory()
    {
        static::updated(function ($model) {
            if ($model->isDirty('password')) {
                $model->addToPasswordHistory();
            }
        });
    }

    /**
     * Get the password history records for the user.
     */
    public function passwordHistories()
    {
        return $this->hasMany(PasswordHistory::class);
    }

    /**
     * Check if the given password exists in the user's password history.
     *
     * @param  string  $password
     * @param  int  $keepLast  Number of recent passwords to check against
     * @return bool
     */
    public function isPasswordInHistory($password, $keepLast = 5)
    {
        $recentPasswords = $this->passwordHistories()
            ->orderBy('created_at', 'desc')
            ->take($keepLast)
            ->get();

        foreach ($recentPasswords as $passwordHistory) {
            if (Hash::check($password, $passwordHistory->password)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Add current password to password history.
     *
     * @return void
     */
    public function addToPasswordHistory()
    {
        $this->passwordHistories()->create([
            'password' => $this->password,
            'changed_at' => now(),
        ]);

        $this->cleanupOldPasswordHistory();
    }

    /**
     * Remove old password history records, keeping only the most recent ones.
     *
     * @param  int  $keepLast  Number of recent passwords to keep
     * @return void
     */
    public function cleanupOldPasswordHistory($keepLast = 5)
    {
        $recentIds = $this->passwordHistories()
            ->orderBy('created_at', 'desc')
            ->take($keepLast)
            ->pluck('id');

        if ($recentIds->isNotEmpty()) {
            $this->passwordHistories()
                ->whereNotIn('id', $recentIds)
                ->delete();
        }
    }

    /**
     * Get the number of days until the password expires.
     *
     * @param  int  $daysToExpire  Number of days after which password expires
     * @return int|null
     */
    public function getDaysUntilPasswordExpires($daysToExpire = 90)
    {
        $latestChange = $this->passwordHistories()
            ->latest('created_at')
            ->first();

        if (!$latestChange) {
            return null;
        }

        $expiryDate = $latestChange->created_at->addDays($daysToExpire);
        return now()->diffInDays($expiryDate, false);
    }

    /**
     * Check if the password has expired.
     *
     * @param  int  $daysToExpire  Number of days after which password expires
     * @return bool
     */
    public function isPasswordExpired($daysToExpire = 90)
    {
        $daysLeft = $this->getDaysUntilPasswordExpires($daysToExpire);
        return $daysLeft !== null && $daysLeft < 0;
    }

    /**
     * Get password change history for security reports.
     *
     * @param  int  $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPasswordChangeHistory($limit = 10)
    {
        return $this->passwordHistories()
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
}
