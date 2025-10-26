<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Log access attempt
        Log::info('AdminMiddleware: Checking access', [
            'user_id' => auth()->id(),
            'user_role' => auth()->user()->role ?? 'none',
            'isAdmin' => auth()->user()->isAdmin() ? 'true' : 'false',
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Check if user is authenticated
        if (!auth()->check()) {
            Log::warning('AdminMiddleware: Unauthenticated access attempt', [
                'ip' => $request->ip(),
                'path' => $request->path()
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Please log in to access this page.'], 401);
            }

            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = auth()->user();

        // Check if user is admin
        if ($user->isAdmin()) {
            Log::info('AdminMiddleware: Access granted', [
                'user_id' => $user->id,
                'user_name' => $user->name
            ]);

            // Record admin activity (if you have this method)
            if (method_exists($user, 'recordAdminAction')) {
                $user->recordAdminAction();
            }

            return $next($request);
        }

        // Log unauthorized access attempt
        Log::warning('AdminMiddleware: Unauthorized access attempt', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'user_name' => $user->name,
            'path' => $request->path(),
            'ip' => $request->ip()
        ]);

        // Handle JSON requests
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthorized access. Admin privileges required.'], 403);
        }

        // Redirect based on user type
        if ($user->isPwd()) {
            return redirect()->route('dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        return redirect('/')->with('error', 'Access denied. Admin privileges required.');
    }
}
