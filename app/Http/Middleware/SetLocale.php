<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Accessibility\AccessibilityController;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get language from accessibility preferences
        $language = AccessibilityController::getCurrentLanguage();
        
        // Set application locale
        app()->setLocale($language);
        
        // Set fallback locale
        app()->setFallbackLocale('en');
        
        return $next($request);
    }
}
