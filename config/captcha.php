<?php

return [
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'options' => [
        'timeout' => 10, // Reduced timeout to fail faster
        'verify-url' => 'https://www.google.com/recaptcha/api/siteverify',
    ],
];
