<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPwdProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Check if user is authenticated and is a PWD user
        if (!$user) {
            return redirect()->route('login');
        }

        // Check profile completion percentage
        $completion = 0;
        if (method_exists($user, 'getProfileCompletionPercentage')) {
            $completion = $user->getProfileCompletionPercentage();
        }

        // Check if user has resume (either PDF upload or database resume)
        $hasResume = method_exists($user, 'hasResume') && $user->hasResume();

        // If profile is incomplete (< 80%) OR no resume exists, redirect to complete profile
        if ($completion < 80 || !$hasResume) {
            $missingItems = [];
            if ($completion < 80) {
                $missingItems[] = 'complete your PWD profile (currently ' . $completion . '%)';
            }
            if (!$hasResume) {
                $missingItems[] = 'upload a resume';
            }

            $message = 'Please ' . implode(' and ', $missingItems) . ' before applying for jobs.';

            return redirect()->route('profile.pwd-complete-form')
                ->with('warning', $message);
        }

        return $next($request);
    }
}
