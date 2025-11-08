<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployerMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('warning', 'Please log in to access this page.');
        }

        $user = auth()->user();

        if (!$user->isEmployer()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Access denied. Employer account required.',
                    'redirect' => route('dashboard')
                ], 403);
            }
            abort(403, 'Access denied. Employer account required.');
        }

        // Check if employer account is active
        if (!$user->isActive()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Your employer account has been deactivated.',
                    'redirect' => route('contact')
                ], 403);
            }
            return redirect()->route('contact')
                ->with('error', 'Your employer account has been deactivated. Please contact support.');
        }

        return $next($request);
    }
}
