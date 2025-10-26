<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Captcha implements Rule
{
    public function passes($attribute, $value)
    {
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('app.recaptcha.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        $body = $response->json();

        if (config('app.recaptcha.version') === 'v3') {
            return $body['success'] && $body['score'] >= config('app.recaptcha.score_threshold');
        }

        return $body['success'];
    }

    public function message()
    {
        return 'The reCAPTCHA verification failed. Please try again.';
    }
}
