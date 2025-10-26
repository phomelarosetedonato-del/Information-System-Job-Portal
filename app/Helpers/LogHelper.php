<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class LogHelper
{
    public static function registration(string $message, array $context = [])
    {
        Log::channel('registration')->info($message, $context);
    }

    public static function security(string $message, array $context = [])
    {
        Log::channel('security')->warning($message, $context);
    }

    public static function admin(string $message, array $context = [])
    {
        Log::channel('admin')->info($message, $context);
    }

    public static function pwdActivity(string $message, array $context = [])
    {
        Log::channel('pwd_activity')->info($message, $context);
    }

    public static function audit(string $message, array $context = [])
    {
        Log::channel('audit')->info($message, $context);
    }

    public static function suspicious(string $message, array $context = [])
    {
        Log::channel('security')->error('SUSPICIOUS: ' . $message, $context);
        Log::error('SUSPICIOUS ACTIVITY: ' . $message, $context);
    }
}
