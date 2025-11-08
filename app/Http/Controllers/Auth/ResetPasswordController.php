<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Traits\HasPasswordHistory;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
                function ($attribute, $value, $fail) {
                    // Check if password is in user's password history
                    $user = User::where('email', request('email'))->first();
                    if ($user && method_exists($user, 'isPasswordInHistory') && $user->isPasswordInHistory($value)) {
                        $fail('You cannot use a password that you have used in the last 5 passwords. Please choose a different password.');
                    }
                },
            ],
        ];
    }

    /**
     * Get the password reset validation error messages.
     *
     * @return array
     */
    protected function validationErrorMessages()
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.exists' => 'We could not find a user with that email address.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character (@$!%*#?&).',
        ];
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            // Log the password reset event
            activity()
                ->causedBy($user)
                ->withProperties([
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'event' => 'password_reset_successful'
                ])
                ->log('password_reset_successful');

            // Clear any existing login locks
            if (method_exists($user, 'clearLoginAttempts')) {
                $user->clearLoginAttempts();
            }
        }

        return redirect($this->redirectPath())
            ->with('status', trans($response))
            ->with('success', 'Your password has been reset successfully! You can now login with your new password.');
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        // Log failed password reset attempt
        activity()
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'email' => $request->email,
                'reason' => trans($response),
                'event' => 'password_reset_failed'
            ])
            ->log('password_reset_failed');

        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)])
            ->with('error', 'Password reset failed. Please check your email and try again.');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \App\Models\User  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        // Use the User model instance directly
        $user->password = Hash::make($password);
        $user->setRememberToken(\Illuminate\Support\Str::random(60));
        $user->password_changed_at = now();
        $user->last_password_changed_at = now();
        $user->password_meets_current_standards = true;
        $user->failed_login_attempts = 0; // Reset failed attempts
        $user->account_locked_until = null; // Unlock account if locked
        $user->save();

        // Password history is automatically handled by the HasPasswordHistory trait

        // Fire password reset event
        event(new \Illuminate\Auth\Events\PasswordReset($user));

        // Log the password change
        activity()
            ->causedBy($user)
            ->withProperties([
                'ip' => request()->ip(),
                'event' => 'password_changed_via_reset'
            ])
            ->log('password_changed_via_reset');
    }

    /**
     * Validate the request for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateRequest(Request $request)
    {
        $request->validate($this->rules(), $this->validationErrorMessages());
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $token
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function showResetForm(Request $request, $token = null)
    {
        // If no token, redirect to password request page
        if (!$token) {
            return redirect()->route('password.request')
                ->with('error', 'Invalid password reset token.');
        }

        // Check if token exists and is valid in the database
        $tokenData = DB::table('password_reset_tokens')
            ->where('token', hash('sha256', $token))
            ->first();

        if (!$tokenData) {
            return redirect()->route('password.request')
                ->with('error', 'This password reset link is invalid or has expired. Please request a new one.');
        }

        // Check if token is expired (default: 60 minutes)
        $tokenAge = now()->diffInMinutes($tokenData->created_at);
        $tokenExpireMinutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        if ($tokenAge > $tokenExpireMinutes) {
            // Delete expired token
            DB::table('password_reset_tokens')->where('token', hash('sha256', $token))->delete();

            return redirect()->route('password.request')
                ->with('error', 'This password reset link has expired. Please request a new one.');
        }

        // Check if email from token matches the request email (if provided)
        if ($request->email && $tokenData->email !== $request->email) {
            return redirect()->route('password.request')
                ->with('error', 'The email address does not match the reset token.');
        }

        return view('auth.passwords.reset')->with([
            'token' => $token,
            'email' => $request->email ?: $tokenData->email
        ]);
    }

    /**
     * Check if the user's password has been compromised in data breaches.
     *
     * @param  string  $password
     * @return bool
     */
    protected function isPasswordCompromised($password)
    {
        // You can integrate with Have I Been Pwned API here
        // This is a basic implementation - consider using the API for production
        $hash = sha1($password);
        $prefix = substr($hash, 0, 5);
        $suffix = substr($hash, 5);

        // In a real implementation, you would make a request to HIBP API
        // For now, we'll return false (not compromised)
        return false;
    }
}
