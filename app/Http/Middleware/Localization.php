<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Check cookie first (from accessibility preferences)
            $preferences = $request->cookie('accessibility_preferences');
            if ($preferences) {
                $preferences = json_decode($preferences, true);
                if (isset($preferences['language']) && in_array($preferences['language'], config('app.available_locales', ['en']))) {
                    App::setLocale($preferences['language']);
                    return $next($request);
                }
            }

            // Check session
            if ($request->session()->has('locale')) {
                $sessionLocale = $request->session()->get('locale');
                if (in_array($sessionLocale, config('app.available_locales', ['en']))) {
                    App::setLocale($sessionLocale);
                }
            }

            // Fallback to config default
            App::setLocale(config('app.locale', 'en'));

            return $next($request);

        } catch (\Exception $e) {
            // If there's any error in the middleware, just continue with default locale
            App::setLocale(config('app.locale', 'en'));
            return $next($request);
        }
    }
}
