<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    public function passes($attribute, $value)
    {
        // Philippine phone number format +63XXXXXXXXX or 09XXXXXXXXX
        $cleanPhone = preg_replace('/[^0-9+]/', '', $value);
        return preg_match('/^(\+63|0)[9][0-9]{9}$/', $cleanPhone);
    }

    public function message()
    {
        return 'Please provide a valid Philippine phone number (e.g., +639171234567 or 09171234567).';
    }
}
