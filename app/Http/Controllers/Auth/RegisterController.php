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
                'min:2',
                'max:255',
                'regex:/^[\pL\s\-\.]+$/u', // Only letters, spaces, hyphens, and dots
                function ($attribute, $value, $fail) {
                    // Check for suspicious patterns (repeated characters)
                    if (preg_match('/(.)\1{4,}/', $value)) {
                        $fail('Please enter a valid name without repeated characters.');
                    }
                    // Check for single character names
                    if (str_word_count($value) === 1 && strlen($value) < 3) {
                        $fail('Please enter your full name.');
                    }
                }
            ],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                'unique:users',
                function ($attribute, $value, $fail) {
                    // Expanded list of disposable email providers
                    $disposableDomains = [
                        'tempmail.com', 'guerrillamail.com', 'mailinator.com',
                        '10minutemail.com', 'throwawaymail.com', 'yopmail.com',
                        'temp-mail.org', 'getairmail.com', 'fakeinbox.com',
                        'trashmail.com', 'getnada.com', 'maildrop.cc',
                        'sharklasers.com', 'guerrillamailblock.com', 'spam4.me',
                        'mintemail.com', 'mytrashmail.com', 'emailondeck.com'
                    ];

                    $domain = explode('@', $value)[1] ?? '';
                    if (in_array(strtolower($domain), $disposableDomains)) {
                        $fail('Disposable email addresses are not allowed for security reasons.');
                    }

                    // Check for suspicious email patterns
                    $localPart = explode('@', $value)[0] ?? '';
                    if (preg_match('/^(test|admin|spam|fake|dummy|trash)/i', $localPart)) {
                        $fail('Please use a valid personal or business email address.');
                    }
                }
            ],
            'password' => [
                'required',
                'string',
                'min:12', // Increased minimum length for better security
                'max:128', // Prevent extremely long passwords
                'confirmed',
                new StrongPassword,
                function ($attribute, $value, $fail) {
                    // Check for common weak passwords
                    $commonPasswords = [
                        'password123', '123456789012', 'qwerty123456',
                        'admin123456', 'welcome12345', 'Password@123'
                    ];
                    if (in_array(strtolower($value), array_map('strtolower', $commonPasswords))) {
                        $fail('This password is too common. Please choose a more unique password.');
                    }

                    // Check for sequential characters
                    if (preg_match('/(?:abc|bcd|cde|def|efg|fgh|ghi|hij|ijk|jkl|klm|lmn|mno|nop|opq|pqr|qrs|rst|stu|tuv|uvw|vwx|wxy|xyz|012|123|234|345|456|567|678|789)/i', $value)) {
                        $fail('Password should not contain sequential characters.');
                    }
                }
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                new PhoneNumber,
                new UniquePhone,
                function ($attribute, $value, $fail) {
                    // Additional validation for phone numbers
                    $cleaned = preg_replace('/[^0-9+]/', '', $value);
                    if (preg_match('/(\d)\1{5,}/', $cleaned)) {
                        $fail('Please enter a valid phone number.');
                    }
                }
            ],
            'address' => [
                'required',
                'string',
                'min:10',
                'max:500',
                'regex:/^[a-zA-Z0-9\s\-\.,#]+$/',
                function ($attribute, $value, $fail) {
                    // Check for meaningful address (not just random characters)
                    if (str_word_count($value) < 3) {
                        $fail('Please provide a complete address.');
                    }
                }
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
            'name.required' => 'Full name is required.',
            'name.min' => 'Name must be at least 2 characters.',
            'name.regex' => 'Name can only contain letters, spaces, hyphens, and dots.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already registered. Please login or use a different email.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 12 characters long for security.',
            'password.max' => 'Password is too long. Maximum 128 characters allowed.',
            'password.confirmed' => 'Password confirmation does not match.',
            'phone.required' => 'Phone number is required.',
            'address.required' => 'Address is required.',
            'address.min' => 'Please provide a complete address (minimum 10 characters).',
            'address.regex' => 'Address contains invalid characters.',
            'user_type.required' => 'Please select an account type.',
            'user_type.in' => 'Invalid account type selected.',
            'terms.required' => 'You must accept the Terms of Service to register.',
            'terms.accepted' => 'You must accept the Terms of Service to register.',
            'privacy_policy.required' => 'You must accept the Privacy Policy to register.',
            'privacy_policy.accepted' => 'You must accept the Privacy Policy to register.',
            'g-recaptcha-response.required' => 'Please complete the reCAPTCHA verification.',
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Check for suspicious registration patterns
            $this->checkSuspiciousRegistration($data);

            $normalizedPhone = $this->normalizePhone($data['phone']);

            $user = User::create([
                'name' => strip_tags(trim($data['name'])),
                'email' => strtolower(trim($data['email'])),
                'password' => Hash::make($data['password']),
                'role' => $data['user_type'],
                'phone' => $normalizedPhone,
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

            // Ensure user is fully persisted before accessing ID
            $user->refresh();

            // Create PWD profile if user registered as PWD
            if ($data['user_type'] === 'pwd') {
                PwdProfile::create([
                    'user_id' => $user->id,
                    'disability_type' => 'Not Specified',
                    'disability_severity' => 'moderate',
                    'assistive_devices' => null,
                    'accessibility_needs' => null,
                    'skills' => null,
                    'qualifications' => null,
                    'phone' => $normalizedPhone,
                    'address' => strip_tags(trim($data['address'])),
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
