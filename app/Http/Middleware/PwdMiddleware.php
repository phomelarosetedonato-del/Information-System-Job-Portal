<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class PwdMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('PwdMiddleware: Checking access', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? 'none',
            'isPwd' => auth()->user()->isPwd() ? 'true' : 'false',
            'path' => $request->path(),
            'ip' => $request->ip()
        ]);

        if (auth()->check() && auth()->user()->isPwd()) {
            Log::info('PwdMiddleware: Access granted', [
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name
            ]);

            // Record PWD activity (if you have this method)
            if (method_exists(auth()->user(), 'recordPwdAction')) {
                auth()->user()->recordPwdAction();
            }

            return $next($request);
        }

        Log::warning('PwdMiddleware: Access denied', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? 'none',
            'user_name' => auth()->user()->name ?? 'none',
            'path' => $request->path(),
            'ip' => $request->ip()
        ]);

        // Handle JSON requests
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized access. PWD privileges required.'], 403);
        }

        return redirect('/dashboard')->with('error', 'Access denied. PWD user privileges required.');
    }
}
