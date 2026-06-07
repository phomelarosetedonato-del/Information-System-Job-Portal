<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class LogAnnouncementRequests
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Log announcement-related requests
        if (strpos($request->path(), 'admin/announcements') !== false) {
            Log::info('╔════════════════════════════════════════════════════════╗');
            Log::info('║     MIDDLEWARE: ANNOUNCEMENT REQUEST DETECTED           ║');
            Log::info('╚════════════════════════════════════════════════════════╝', [
                'method' => $request->method(),
                'path' => $request->path(),
                'full_url' => $request->fullUrl(),
                'user_id' => Auth::id(),
                'is_admin' => Auth::check() && Auth::user()->isAdmin() ? 'YES' : 'NO',
                'timestamp' => now()->toDateTimeString(),
            ]);
        }

        $response = $next($request);

        // Log announcement response
        if (strpos($request->path(), 'admin/announcements') !== false && $request->method() === 'POST') {
            Log::info('MIDDLEWARE: ANNOUNCEMENT RESPONSE', [
                'status' => $response->status(),
                'location' => $response->headers->get('Location'),
                'timestamp' => now()->toDateTimeString(),
            ]);
            Log::info('║════════════════════════════════════════════════════════║');
        }

        return $response;
    }
}
