<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Maximum number of login attempts before lockout.
     *
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * Number of minutes to lock the user out after max attempts.
     *
     * @var int
     */
    protected $decayMinutes = 15;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('throttle:' . $this->maxAttempts . ',1,' . $this->decayMinutes)->only('login');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            // Log successful login
            Log::channel('auth')->info('User logged in successfully', [
                'user_id' => Auth::id(),
                'email' => $request->email,
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent(), 0, 200),
            ]);

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        // Log failed login attempt
        Log::channel('auth')->warning('Failed login attempt', [
            'email' => $request->email,
            'ip' => $request->ip(),
            'user_agent' => substr($request->userAgent(), 0, 200),
        ]);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'Email or username is required.',
            'password.required' => 'Password is required.',
        ]);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        // we accept either email or username via the 'login' form field
        return 'login';
    }

    /**
     * Override credentials to accept email or username
     */
    protected function credentials(Request $request)
    {
        $login = $request->input('login');
        $password = $request->input('password');

        // If input is a valid email, authenticate using email
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return ['email' => $login, 'password' => $password];
        }

        // Otherwise attempt to find user by username, fall back to email
        $user = User::where('username', $login)->orWhere('email', $login)->first();
        if ($user) {
            return ['email' => $user->email, 'password' => $password];
        }

        // Fallback to email field with the provided value (will fail)
        return ['email' => $login, 'password' => $password];
    }

    /**
     * Attempt to log the user into the application.
     * Ensure the remember option is respected.
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return mixed
     */
   protected function authenticated(Request $request, $user)
{
    // Check if user account is active
    if (!$user->isActive()) {
        Auth::logout();

        Log::channel('security')->warning('Inactive account login attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        throw ValidationException::withMessages([
            $this->username() => 'Your account has been deactivated. Please contact administrator.',
        ]);
    }

    // Check if account is locked
    if ($user->isAccountLocked()) {
        Auth::logout();

        Log::channel('security')->warning('Locked account login attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'locked_until' => $user->account_locked_until,
            'ip' => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            $this->username() => 'Account is temporarily locked until ' . $user->getTimeUntilUnlock() . '. Please try again later.',
        ]);
    }

    // Check for security warnings
    $securityScore = $user->calculateSecurityScore();
    if ($securityScore < 60) {
        session()->flash('security_warning', true);
        session()->flash('security_score', $securityScore);

        $recommendations = collect($user->getSecurityRecommendations())
            ->filter(fn($r) => $r['priority'] === 'high')
            ->pluck('message')
            ->toArray();

        session()->flash('security_recommendations', $recommendations);

        Log::channel('security')->info('Low security score login', [
            'user_id' => $user->id,
            'score' => $securityScore,
            'ip' => $request->ip(),
        ]);
    }

    // Check for password expiry
    if ($user->isPasswordExpired()) {
        session()->flash('warning', 'Your password has expired. Please change it immediately.');

        Log::channel('security')->warning('Expired password login', [
            'user_id' => $user->id,
            'last_changed' => $user->last_password_changed_at,
            'ip' => $request->ip(),
        ]);
    } elseif ($user->getDaysUntilPasswordExpires() <= 7) {
        session()->flash('info', 'Your password will expire in ' . $user->getDaysUntilPasswordExpires() . ' days. Please consider changing it soon.');
    }

    // Reset failed login attempts on successful login
    if ($user->failed_login_attempts > 0) {
        $user->unlockAccount();
    }

    // Update last login timestamp using your existing method
    $user->updateLastLogin($request->ip());

    // Enhanced security logging
    Log::channel('auth')->info('Successful user login', [
        'user_id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $user->role,
        'ip' => $request->ip(),
        'user_agent' => substr($request->userAgent(), 0, 200),
        'security_score' => $securityScore,
        'login_count' => $user->login_count + 1,
        'timestamp' => now()->toDateTimeString(),
    ]);

    // Log activity in activity log if available
    if (function_exists('activity')) {
        activity()
            ->causedBy($user)
            ->withProperties([
                'ip' => $request->ip(),
                'user_agent' => substr($request->userAgent(), 0, 200),
                'security_score' => $securityScore,
            ])
            ->log('user_logged_in');
    }

    // ROLE-BASED REDIRECTION - ENHANCED VERSION
    if ($user->isAdmin()) {
        Log::channel('admin')->info('Admin user logged in', [
            'user_id' => $user->id,
            'name' => $user->name,
            'ip' => $request->ip(),
            'last_admin_action' => $user->last_admin_action_at,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    // For PWD users
    if ($user->isPwd()) {
        if (!$user->hasPwdProfile()) {
            return redirect()->route('profile.pwd-complete-form')
                ->with('info', 'Please complete your PWD profile to access all features.');
        }

        // Check resume requirement
        if (!$user->hasResume()) {
            session()->flash('info', 'Upload your resume to apply for jobs.');
        }

        // Redirect PWD users to their dashboard after profile completion
        return redirect()->route('pwd.dashboard')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    // For employers - ENHANCED
    if ($user->isEmployer()) {
        Log::channel('employer')->info('Employer user logged in', [
            'user_id' => $user->id,
            'name' => $user->name,
            'company' => $user->company_name,
            'verification_status' => $user->employer_verification_status,
            'ip' => $request->ip(),
        ]);

        // Check verification status
        if (!$user->isEmployerVerified()) {
            session()->flash('info', 'Complete employer verification to post jobs.');
        }

        // Always redirect employers to their dashboard
        return redirect()->route('employer.dashboard')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    // Default redirect for regular users
    return redirect()->route('dashboard')
        ->with('success', 'Welcome back, ' . $user->name . '!');
}

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Increment failed login attempts for the user
        $login = $request->input('login');

        // Try to find user by email or username
        $user = null;
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $login)->first();
        } else {
            $user = User::where('username', $login)->orWhere('email', $login)->first();
        }

        if ($user) {
            $user->recordFailedLoginAttempt();

            // Check if account is now locked after this attempt
            if ($user->isAccountLocked()) {
                Log::channel('security')->warning('User account locked due to failed login attempts', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'attempts' => $user->failed_login_attempts,
                    'ip' => $request->ip(),
                ]);

                throw ValidationException::withMessages([
                    $this->username() => 'Too many login attempts. Account locked for ' . $this->decayMinutes . ' minutes.',
                ]);
            }
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        // Log logout activity
        if ($user) {
            Log::channel('auth')->info('User logged out', [
                'user_id' => $user->id,
                'name' => $user->name,
                'ip' => $request->ip(),
            ]);
        }

        $this->guard()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return $request->wantsJson()
            ? new \Illuminate\Http\JsonResponse([], 204)
            : redirect('/');
    }

    /**
     * Where to redirect users after logout.
     *
     * @return string
     */
    protected function loggedOut()
    {
        return redirect('/');
    }

    /**
     * Get the post register redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        if (method_exists($this, 'redirectTo')) {
            return $this->redirectTo();
        }

        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/dashboard';
    }
}
