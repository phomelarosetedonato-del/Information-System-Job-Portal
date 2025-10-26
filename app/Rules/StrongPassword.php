<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class StrongPassword implements Rule
{
    public function passes($attribute, $value)
    {
        // Minimum 12 characters with complexity requirements
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$/', $value);
    }

    public function message()
    {
        return 'The password must be at least 12 characters long and include uppercase, lowercase, number, and special character.';
    }
}
