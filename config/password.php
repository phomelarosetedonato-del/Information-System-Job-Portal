<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Password Policy Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the password policy settings for your application.
    | These settings determine the requirements for user passwords.
    |
    */

    'policy' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => true,
        'max_attempts' => 5,
        'lockout_duration' => 15, // minutes
        'expiry_days' => 90,
        'history_size' => 5,
        'prevent_reuse' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Strength Validation Messages
    |--------------------------------------------------------------------------
    |
    | Custom validation messages for password strength requirements.
    |
    */
    'messages' => [
        'min_length' => 'Password must be at least :min characters long.',
        'uppercase' => 'Password must contain at least one uppercase letter.',
        'lowercase' => 'Password must contain at least one lowercase letter.',
        'numbers' => 'Password must contain at least one number.',
        'symbols' => 'Password must contain at least one special character (@$!%*#?&).',
        'reuse' => 'You cannot use a password that you have used in the last :count passwords.',
    ],
];
