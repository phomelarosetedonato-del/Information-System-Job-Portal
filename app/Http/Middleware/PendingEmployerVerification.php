<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PendingEmployerVerification
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isEmployer()) {
            return $next($request);
        }

        // Redirect to verification if not applied but trying to access certain features
        $verificationStatus = $user->getEmployerVerificationStatusText();

        if ($verificationStatus === 'Not Applied' &&
            $user->getEmployerProfileCompletion() >= 70 &&
            !$this->isExcludedRoute($request)) {

            return redirect()->route('employer.verification.apply')
                ->with('info', 'Please complete employer verification to access all features.');
        }

        // Suggest verification renewal if expired
        if ($verificationStatus === 'Verification Expired' &&
            !$this->isExcludedRoute($request)) {

            return redirect()->route('employer.verification.status')
                ->with('warning', 'Your employer verification has expired. Please renew your verification.');
        }

        return $next($request);
    }

    /**
     * Get routes that should be excluded from redirection
     */
    private function getExcludedRoutes(): array
    {
        return [
            'employer.verification.*',
            'employer.profile.*',
            'employer.settings',
            'employer.dashboard',
            'logout'
        ];
    }

    /**
     * Check if current route should be excluded
     */
    private function isExcludedRoute(Request $request): bool
    {
        $currentRoute = $request->route()->getName();

        foreach ($this->getExcludedRoutes() as $excludedRoute) {
            if (str_contains($excludedRoute, '*')) {
                $pattern = str_replace('*', '.*', $excludedRoute);
                if (preg_match('#^' . $pattern . '$#', $currentRoute)) {
                    return true;
                }
            } elseif ($currentRoute === $excludedRoute) {
                return true;
            }
        }

        return false;
    }
}
