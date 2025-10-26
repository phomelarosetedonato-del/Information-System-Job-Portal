<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PwdProfile;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Rules\StrongPassword;
use App\Rules\PhoneNumber;
use App\Rules\UniquePhone;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/dashboard';

    // Maximum registration attempts per IP in 24 hours
    protected $maxAttempts = 5;
    protected $decayMinutes = 1440; // 24 hours

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('throttle:' . $this->maxAttempts . ',1,' . $this->decayMinutes)->only('register');
    }

    protected function validator(array $data)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\s\-\.]+$/u' // Only letters, spaces, hyphens, and dots
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    // Check for disposable emails
                    $disposableDomains = [
                        'tempmail.com', 'guerrillamail.com', 'mailinator.com',
                        '10minutemail.com', 'throwawaymail.com', 'yopmail.com'
                    ];

                    $domain = explode('@', $value)[1] ?? '';
                    if (in_array(strtolower($domain), $disposableDomains)) {
                        $fail('Disposable email addresses are not allowed.');
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:12', // Increased minimum length
                'confirmed',
                new StrongPassword,
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                new PhoneNumber,
                new UniquePhone,
            ],
            'address' => [
                'required',
                'string',
                'max:500',
                'regex:/^[a-zA-Z0-9\s\-\.,#]+$/'
            ],
            'user_type' => [
                'required',
                'in:pwd,employer'
            ],
            'terms' => [
                'required',
                'accepted'
            ],
            'privacy_policy' => [
                'required',
                'accepted'
            ],
        ];

        // Add reCAPTCHA validation if enabled
        if (config('services.recaptcha.enabled', false)) {
            $rules['g-recaptcha-response'] = ['required', 'captcha'];
        }

        $messages = [
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and dots.',
            'email.email' => 'Please provide a valid email address.',
            'password.min' => 'Password must be at least 12 characters long.',
            'address.regex' => 'Address contains invalid characters.',
            'terms.required' => 'You must accept the Terms of Service.',
            'privacy_policy.required' => 'You must accept the Privacy Policy.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Check for suspicious registration patterns
            $this->checkSuspiciousRegistration($data);

            $user = User::create([
                'name' => strip_tags(trim($data['name'])),
                'email' => strtolower(trim($data['email'])),
                'password' => Hash::make($data['password']),
                'role' => $data['user_type'],
                'phone' => $this->normalizePhone($data['phone']),
                'address' => strip_tags(trim($data['address'])),
                'registration_ip' => request()->ip(),
                'is_active' => true,
                'email_verified_at' => null,
                'password_meets_current_standards' => true,
                'last_password_changed_at' => now(),
                'security_questions_set' => false,
                'failed_login_attempts' => 0,
                'account_locked_until' => null,
                'last_security_activity' => now(),
                'registration_user_agent' => request()->userAgent(),
            ]);

          // Create PWD profile if user registered as PWD
if ($data['user_type'] === 'pwd') {
    PwdProfile::create([
        'user_id' => $user->id,
        'disability_type' => 'Not Specified', // Required field
        'disability_severity' => null,
        'assistive_devices' => null,
        'accessibility_needs' => null,
        'skills' => null,
        'qualifications' => null,
        'phone' => $user->phone,
        'address' => $user->address,
        'birthdate' => null,
        'gender' => null,
        'special_needs' => null,
        'profile_completed' => false,
        'is_employed' => false,
    ]);
}

            // Log successful registration
            Log::channel('registration')->info('User registered successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'ip' => request()->ip(),
                'user_agent' => substr(request()->userAgent(), 0, 200),
            ]);

            return $user;
        });
    }

    /**
     * Normalize phone number to consistent format
     */
    private function normalizePhone($phone)
    {
        $cleanPhone = preg_replace('/[^0-9+]/', '', $phone);

        // Normalize Philippine numbers
        if (str_starts_with($cleanPhone, '09') && strlen($cleanPhone) === 11) {
            $cleanPhone = '+63' . substr($cleanPhone, 1);
        } elseif (str_starts_with($cleanPhone, '63') && strlen($cleanPhone) === 11) {
            $cleanPhone = '+' . $cleanPhone;
        }

        return $cleanPhone;
    }

    /**
     * Check for suspicious registration patterns
     */
    private function checkSuspiciousRegistration(array $data)
    {
        $ip = request()->ip();
        $email = strtolower(trim($data['email']));
        $phone = $this->normalizePhone($data['phone']);

        // Check multiple accounts from same IP in last 24 hours
        $recentRegistrations = User::where('registration_ip', $ip)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        if ($recentRegistrations >= 3) {
            Log::channel('security')->warning('Multiple registrations from same IP', [
                'ip' => $ip,
                'email' => $email,
                'count' => $recentRegistrations
            ]);
        }

        // Check if phone number was recently used
        $recentPhoneUse = User::where('phone', $phone)
            ->where('created_at', '>=', now()->subDays(7))
            ->exists();

        if ($recentPhoneUse) {
            Log::channel('security')->warning('Phone number reused in registration', [
                'phone' => $phone,
                'email' => $email,
                'ip' => $ip
            ]);
        }

        // Check for suspicious email patterns
        if ($this->isSuspiciousEmail($email)) {
            Log::channel('security')->error('SUSPICIOUS: Suspicious email pattern detected', [
                'email' => $email,
                'ip' => $ip
            ]);
        }
    }

    /**
     * Check for suspicious email patterns
     */
    private function isSuspiciousEmail($email)
    {
        // Check for sequential numbers or letters
        if (preg_match('/[a-z]{3,}\d{3,}@/', $email) || preg_match('/\d{3,}[a-z]{3,}@/', $email)) {
            return true;
        }

        // Check for random string patterns
        $localPart = explode('@', $email)[0];
        if (strlen($localPart) > 30) {
            return true;
        }

        return false;
    }

    /**
     * The user has been registered.
     */
    protected function registered(Request $request, $user)
    {
        // Send email verification notification
        if (method_exists($user, 'sendEmailVerificationNotification')) {
            $user->sendEmailVerificationNotification();
        }

        // Send admin notification for new PWD registration
        if ($user->isPwd()) {
            $this->notifyAdminOfPwdRegistration($user);
        }

        // Show appropriate success message
        $message = 'Account created successfully! ';

        if ($user->isPwd()) {
            $message .= 'Please check your email to verify your account and then complete your PWD profile.';
        } else {
            $message .= 'Please check your email to verify your account.';
        }

        return redirect($this->redirectPath())
            ->with('success', $message)
            ->with('verified', false);
    }

    /**
     * Notify admin of new PWD registration
     */
    private function notifyAdminOfPwdRegistration(User $user)
    {
        // Log admin notification
        Log::channel('admin')->info('New PWD registration', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
        ]);
    }
}
