<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerifiedEmployer
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('warning', 'Please log in to access this page.');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if user is an employer
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

        // Check employer verification status
        if (!$user->isEmployerVerified()) {
            $verificationStatus = $user->getEmployerVerificationStatusText();
            $message = $this->getVerificationMessage($verificationStatus, $user);

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $message,
                    'verification_status' => $verificationStatus,
                    'redirect' => route('employer.verification.status'),
                    'can_apply' => $this->canApplyForVerification($user)
                ], 403);
            }

            return redirect()->route('employer.verification.status')
                ->with('warning', $message)
                ->with('verification_status', $verificationStatus)
                ->with('can_apply', $this->canApplyForVerification($user));
        }

        // Check if verification has expired
        if ($user->isVerificationExpired()) {
            $message = 'Your employer verification has expired. Please renew your verification to continue posting jobs.';

            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $message,
                    'verification_status' => 'expired',
                    'redirect' => route('employer.verification.status')
                ], 403);
            }

            return redirect()->route('employer.verification.status')
                ->with('error', $message)
                ->with('verification_status', 'expired');
        }

        // Check if profile is sufficiently complete (optional but recommended)
        if ($user->getEmployerProfileCompletion() < 70) {
            if ($request->expectsJson()) {
                return response()->json([
                    'warning' => 'Please complete your employer profile to access all features.',
                    'profile_completion' => $user->getEmployerProfileCompletion(),
                    'redirect' => route('employer.profile.edit')
                ], 403);
            }

            return redirect()->route('employer.profile.edit')
                ->with('info', 'Please complete your employer profile to access all features.')
                ->with('profile_completion', $user->getEmployerProfileCompletion());
        }

        return $next($request);
    }

    /**
     * Get appropriate verification message based on status
     */
    private function getVerificationMessage(string $status, $user): string
    {
        return match($status) {
            'Pending Verification' => 'Your verification request is under review. This usually takes 1-2 business days.',
            'Verification Rejected' => $user->verification_rejected_reason
                ? "Your verification was rejected: {$user->verification_rejected_reason}"
                : 'Your verification was rejected. Please check the requirements and reapply.',
            'Verification Expired' => 'Your employer verification has expired. Please renew your verification.',
            'Not Applied' => $this->canApplyForVerification($user)
                ? 'Please complete employer verification to post jobs and access all features.'
                : 'Please complete your employer profile before applying for verification.',
            default => 'Employer verification required to access this feature.'
        };
    }

    /**
     * Check if employer can apply for verification
     */
    private function canApplyForVerification($user): bool
    {
        if ($user->isEmployerPendingVerification() || $user->isEmployerVerified()) {
            return false;
        }

        if ($user->isEmployerRejected()) {
            return $user->canResubmitVerification();
        }

        // For new applications, require minimum profile completion
        return $user->getEmployerProfileCompletion() >= 70;
    }
}
