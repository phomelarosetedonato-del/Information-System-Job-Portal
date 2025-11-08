<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PhoneNumber implements Rule
{
    public function passes($attribute, $value)
    {
        // Clean the phone number - remove spaces, dashes, parentheses
        $cleanPhone = preg_replace('/[^0-9+]/', '', $value);

        // Accept multiple formats:
        // +639171234567, 639171234567, 09171234567, 9171234567
        return preg_match('/^(\+63|63|0)?[9][0-9]{9}$/', $cleanPhone);
    }

    public function message()
    {
        return 'Please provide a valid Philippine mobile number (e.g., +63 912 345 6789, 0912 345 6789, or 912 345 6789).';
    }
}
