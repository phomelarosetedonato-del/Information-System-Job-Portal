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

        // Require a reasonably-complete profile and a resume before allowing protected actions.
        // Use the User model's profile completion logic (calculated percentage) and resume presence.
        $completion = 0;
        if (method_exists($user, 'getProfileCompletionPercentage')) {
            $completion = $user->getProfileCompletionPercentage();
        }

        if ($completion < 80 || !method_exists($user, 'hasResume') || !$user->hasResume()) {
            return redirect()->route('profile.pwd-complete-form')
                ->with('warning', 'Please complete your PWD profile and upload your resume before accessing this feature.');
        }

        return $next($request);
    }
}
