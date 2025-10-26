<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\User;

class UniquePhone implements Rule
{
    public function passes($attribute, $value)
    {
        $cleanPhone = preg_replace('/[^0-9+]/', '', $value);

        // Normalize Philippine numbers
        if (str_starts_with($cleanPhone, '09') && strlen($cleanPhone) === 11) {
            $cleanPhone = '+63' . substr($cleanPhone, 1);
        } elseif (str_starts_with($cleanPhone, '63') && strlen($cleanPhone) === 11) {
            $cleanPhone = '+' . $cleanPhone;
        }

        return !User::where('phone', $cleanPhone)->exists();
    }

    public function message()
    {
        return 'This phone number is already registered.';
    }
}
