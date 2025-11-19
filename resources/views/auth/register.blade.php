@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    <!-- Skip Navigation for Screen Readers -->
    <a href="#main-content" class="sr-only sr-only-focusable bg-primary text-white p-2 position-absolute top-0 start-0" style="z-index: 9999; transform: translateY(-100%); transition: transform 0.3s;">
        Skip to main content
    </a>

    <div class="row g-0 min-vh-100">
        <!-- Left Side - Security Information -->
        <div class="col-lg-6 d-none d-lg-flex text-white align-items-center justify-content-center p-5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="text-center" style="max-width: 500px;">
                <div class="security-features">
                    <h1 class="display-5 fw-bold mb-4" aria-label="Secure PWD Registration">
                        Secure PWD Registration
                    </h1>

                    <!-- Security Badges -->
                    <div class="row g-4 mb-5">
                        <div class="col-6 text-center">
                            <div class="security-badge mb-2">
                                <i class="fas fa-shield-alt fa-2x text-warning" aria-hidden="true"></i>
                            </div>
                            <small>End-to-End Encryption</small>
                        </div>
                        <div class="col-6 text-center">
                            <div class="security-badge mb-2">
                                <i class="fas fa-user-lock fa-2x text-warning" aria-hidden="true"></i>
                            </div>
                            <small>Identity Verification</small>
                        </div>
                    </div>

                    <!-- Security Checklist -->
                    <div class="text-start">
                        <h3 class="h5 mb-3">Security Features:</h3>
                        <ul class="list-unstyled small">
                            <li class="mb-2"><i class="fas fa-check text-warning me-2"></i>Strong password requirements</li>
                            <li class="mb-2"><i class="fas fa-check text-warning me-2"></i>Phone number verification</li>
                            <li class="mb-2"><i class="fas fa-check text-warning me-2"></i>Email verification required</li>
                            <li class="mb-2"><i class="fas fa-check text-warning me-2"></i>Bot protection with reCAPTCHA</li>
                            <li class="mb-2"><i class="fas fa-check text-warning me-2"></i>Secure data encryption</li>
                            <li class="mb-2"><i class="fas fa-check text-warning me-2"></i>Fraud detection system</li>
                        </ul>
                    </div>

                    <!-- Data Privacy Notice -->
                    <div class="mt-5 p-3 bg-primary-dark border border-light rounded">
                        <h4 class="h6 mb-2">
                            <i class="fas fa-database me-2 text-warning"></i>Data Privacy
                        </h4>
                        <p class="small mb-0">
                            Your information is protected under the Data Privacy Act of 2012 (RA 10173).
                            We never share your data without consent.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Registration Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-lg-5 bg-light">
            <div class="w-100" style="max-width: 500px;" id="main-content" tabindex="-1">
                <!-- Header with Security Info -->
                <div class="text-center mb-5">
                    <div class="security-badge-large mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h1 class="h2 fw-bold text-dark mb-2" id="register-heading">
                        Secure PWD Registration
                    </h1>
                    <p class="text-muted">
                        Alaminos City Persons with Disabilities System
                    </p>
                    <div class="alert alert-info border-0 bg-light small">
                        <i class="fas fa-info-circle me-2"></i>
                        All fields are required for identity verification
                    </div>
                </div>

                <!-- Rest of your registration form remains exactly the same -->
                <!-- Security Warnings -->
                @if ($errors->has('suspicious_activity'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Security Alert:</strong> {{ $errors->first('suspicious_activity') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Registration Form -->
                <div class="card border-0 shadow-sm" role="form" aria-labelledby="register-heading">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('register') }}" id="secure-register-form" class="needs-validation" novalidate>
                            @csrf

                            <!-- User Type Selection with Enhanced Security -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-user-tag me-2 text-primary"></i>
                                    Account Type
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="form-check card h-100 border security-card">
                                            <input class="form-check-input position-absolute mt-2 ms-2"
                                                   type="radio"
                                                   name="user_type"
                                                   id="pwd"
                                                   value="pwd"
                                                   {{ old('user_type') == 'pwd' ? 'checked' : '' }}
                                                   required
                                                   aria-describedby="pwd-security">
                                            <label class="form-check-label card-body d-flex align-items-center" for="pwd">
                                                <div>
                                                    <i class="fas fa-wheelchair fa-2x text-primary mb-2 d-block"></i>
                                                    <strong>PWD Member</strong>
                                                    <small class="d-block text-muted" id="pwd-security">
                                                        For Persons with Disabilities
                                                    </small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check card h-100 border security-card">
                                            <input class="form-check-input position-absolute mt-2 ms-2"
                                                   type="radio"
                                                   name="user_type"
                                                   id="employer"
                                                   value="employer"
                                                   {{ old('user_type') == 'employer' ? 'checked' : '' }}
                                                   aria-describedby="employer-security">
                                            <label class="form-check-label card-body d-flex align-items-center" for="employer">
                                                <div>
                                                    <i class="fas fa-building fa-2x text-success mb-2 d-block"></i>
                                                    <strong>Employer</strong>
                                                    <small class="d-block text-muted" id="employer-security">
                                                        For Hiring Companies
                                                    </small>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @error('user_type')
                                    <div class="text-danger small mt-2 security-error" role="alert">
                                        <i class="fas fa-shield-alt me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Personal Information Section -->
                            <div class="personal-info-section">
                                <h5 class="border-bottom pb-2 mb-3 text-dark">
                                    <i class="fas fa-id-card me-2 text-primary"></i>Personal Information
                                </h5>

                                <!-- Name Input -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-user me-2 text-success"></i>
                                        Full Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        id="name"
                                        type="text"
                                        class="form-control form-control-lg security-input @error('name') is-invalid @enderror"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        autocomplete="name"
                                        autofocus
                                        pattern="[\pL\s\-\.]+"
                                        title="Only letters, spaces, hyphens, and dots are allowed"
                                        placeholder="Enter your full name as per government ID"
                                    >
                                    <div class="form-text text-muted security-hint">
                                        Must match your government-issued ID
                                    </div>
                                    @error('name')
                                        <div class="invalid-feedback d-block security-error" role="alert">
                                            <i class="fas fa-shield-alt me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email Input -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-envelope me-2 text-success"></i>
                                        Email Address
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control form-control-lg security-input @error('email') is-invalid @enderror"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        autocomplete="email"
                                        pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$"
                                        title="Please enter a valid email address"
                                        placeholder="your.legal.email@example.com"
                                    >
                                    <div class="form-text text-muted security-hint">
                                        We'll send verification to this address
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block security-error" role="alert">
                                            <i class="fas fa-shield-alt me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Phone Input -->
                                <div class="mb-3">
                                    <label for="phone" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-phone me-2 text-success"></i>
                                        Phone Number
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input
    id="phone"
    type="tel"
    class="form-control form-control-lg security-input @error('phone') is-invalid @enderror"
    name="phone"
    value="{{ old('phone') }}"
    required
    autocomplete="tel"
    pattern="^(\+63|63|0)?[9][0-9]{9}$"
    title="Please enter a valid Philippine mobile number (e.g., +63 912 345 6789, 0912 345 6789, or 912 345 6789)"
    placeholder="+63 912 345 6789 or 0912 345 6789"
>
                                    <div class="form-text text-muted security-hint">
                                        Philippine mobile number required for verification
                                    </div>
                                    @error('phone')
                                        <div class="invalid-feedback d-block security-error" role="alert">
                                            <i class="fas fa-shield-alt me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Address Input -->
                                <div class="mb-3">
                                    <label for="address" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-map-marker-alt me-2 text-success"></i>
                                        Complete Address
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea
                                        id="address"
                                        class="form-control form-control-lg security-input @error('address') is-invalid @enderror"
                                        name="address"
                                        required
                                        rows="3"
                                        pattern="^[a-zA-Z0-9\s\-\.,#]+$"
                                        title="Please enter a valid address"
                                        placeholder="House No., Street, Barangay, City, Province"
                                    >{{ old('address') }}</textarea>
                                    <div class="form-text text-muted security-hint">
                                        Complete address for identity verification
                                    </div>
                                    @error('address')
                                        <div class="invalid-feedback d-block security-error" role="alert">
                                            <i class="fas fa-shield-alt me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Security Section -->
                            <div class="security-section mt-4">
                                <h5 class="border-bottom pb-2 mb-3 text-dark">
                                    <i class="fas fa-lock me-2 text-success"></i>Security Settings
                                </h5>

                                <!-- Password Input -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-key me-2 text-success"></i>
                                        Secure Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input
                                            id="password"
                                            type="password"
                                            class="form-control form-control-lg security-input @error('password') is-invalid @enderror"
                                            name="password"
                                            required
                                            autocomplete="new-password"
                                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{12,}$"
                                            title="Must contain uppercase, lowercase, number, special character, and be at least 12 characters long"
                                            placeholder="Create a strong password"
                                        >
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary password-toggle security-toggle"
                                            aria-label="Show password"
                                            aria-pressed="false"
                                        >
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div class="password-requirements mt-2">
                                        <small class="text-muted">Requirements:</small>
                                        <ul class="list-unstyled small mt-1">
                                            <li id="req-length" class="text-danger">
                                                <i class="fas fa-times me-1"></i>At least 12 characters
                                            </li>
                                            <li id="req-upper" class="text-danger">
                                                <i class="fas fa-times me-1"></i>Uppercase letter
                                            </li>
                                            <li id="req-lower" class="text-danger">
                                                <i class="fas fa-times me-1"></i>Lowercase letter
                                            </li>
                                            <li id="req-number" class="text-danger">
                                                <i class="fas fa-times me-1"></i>Number
                                            </li>
                                            <li id="req-special" class="text-danger">
                                                <i class="fas fa-times me-1"></i>Special character
                                            </li>
                                        </ul>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block security-error" role="alert">
                                            <i class="fas fa-shield-alt me-1"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Confirm Password Input -->
                                <div class="mb-4">
                                    <label for="password-confirm" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-key me-2 text-primary"></i>
                                        Confirm Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input
                                            id="password-confirm"
                                            type="password"
                                            class="form-control form-control-lg security-input"
                                            name="password_confirmation"
                                            required
                                            autocomplete="new-password"
                                            placeholder="Confirm your password"
                                        >
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary password-toggle security-toggle"
                                            aria-label="Show password confirmation"
                                            aria-pressed="false"
                                        >
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                    <div id="password-match" class="form-text"></div>
                                </div>
                            </div>

                            <!-- reCAPTCHA -->
                            @if(config('services.recaptcha.enabled'))
                            <div class="mb-4 security-section">
                                <label class="form-label fw-semibold text-dark mb-3">
                                    <i class="fas fa-robot me-2 text-primary"></i>
                                    Security Verification
                                    <span class="text-danger">*</span>
                                </label>
                                {!! app('captcha')->renderJs() !!}
                                {!! app('captcha')->display(['data-theme' => 'light']) !!}
                                @error('g-recaptcha-response')
                                    <div class="text-danger small mt-2 security-error" role="alert">
                                        <i class="fas fa-shield-alt me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            @endif

                            <!-- Terms and Policies -->
                            <div class="mb-4 policies-section">
                                <div class="form-check mb-2">
                                    <input
                                        class="form-check-input security-check @error('terms') is-invalid @enderror"
                                        type="checkbox"
                                        name="terms"
                                        id="terms"
                                        required
                                        {{ old('terms') ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label text-dark" for="terms">
                                        I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-primary">Terms of Service</a>
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>

                                <div class="form-check">
                                    <input
                                        class="form-check-input security-check @error('privacy_policy') is-invalid @enderror"
                                        type="checkbox"
                                        name="privacy_policy"
                                        id="privacy_policy"
                                        required
                                        {{ old('privacy_policy') ? 'checked' : '' }}
                                    >
                                    <label class="form-check-label text-dark" for="privacy_policy">
                                        I agree to the <a href="{{ route('privacy') }}" target="_blank" class="text-primary">Privacy Policy</a>
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>

                                @error('terms')
                                    <div class="text-danger small mt-1 security-error" role="alert">
                                        <i class="fas fa-shield-alt me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                                @error('privacy_policy')
                                    <div class="text-danger small mt-1 security-error" role="alert">
                                        <i class="fas fa-shield-alt me-1"></i> {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Register Button -->
                            <div class="d-grid mb-4">
                                <button
                                    type="submit"
                                    class="btn btn-success btn-lg fw-semibold py-3 security-submit"
                                    id="register-button"
                                    disabled
                                    style="border-radius: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;"
                                >
                                    <i class="fas fa-user-shield me-2"></i>
                                    Create Secure Account
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                </button>
                                <div class="form-text text-center text-muted security-hint">
                                    <i class="fas fa-lock me-1"></i>All data is encrypted and secure
                                </div>
                            </div>

                            <!-- Login Link -->
                            <div class="text-center">
                                <p class="mb-0 text-dark">
                                    Already have an account?
                                    <a class="fw-semibold text-decoration-none text-primary" href="{{ route('login') }}">
                                        Sign in securely
                                    </a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Footer -->
                <div class="mt-4 p-3 text-white rounded text-center" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <h6 class="mb-2">
                        <i class="fas fa-shield-alt me-2"></i>Maximum Security
                    </h6>
                    <p class="small mb-0">
                        Protected by end-to-end encryption • Compliant with Data Privacy Act • Regular security audits
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Security Modal -->
<div class="modal fade" id="securityModal" tabindex="-1" aria-labelledby="securityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="securityModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Security Verification
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>For your security, we've detected unusual activity. Please complete the additional verification steps.</p>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="human-verification">
                    <label class="form-check-label" for="human-verification">
                        I confirm I am a real person registering for legitimate purposes
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-human">Confirm</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Security configuration
    const SECURITY_CONFIG = {
        minPasswordLength: 12,
        sessionTimeout: 30 * 60 * 1000, // 30 minutes
        maxAttempts: 5
    };

    // Initialize security features
    initSecurityFeatures();

    function initSecurityFeatures() {
        setupPasswordSecurity();
        setupFormValidation();
        setupSessionSecurity();
        setupRealTimeValidation();
    }

    // Password security features
    function setupPasswordSecurity() {
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password-confirm');
        const passwordToggle = document.querySelectorAll('.password-toggle');
        const requirements = {
            length: document.getElementById('req-length'),
            upper: document.getElementById('req-upper'),
            lower: document.getElementById('req-lower'),
            number: document.getElementById('req-number'),
            special: document.getElementById('req-special')
        };

        // Password visibility toggle
        passwordToggle.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const input = this.parentElement.querySelector('input');
                const isPassword = input.type === 'password';

                input.type = isPassword ? 'text' : 'password';
                this.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
                this.setAttribute('aria-pressed', isPassword);
                this.querySelector('i').className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
            });
        });

        // Real-time password strength checking
        passwordInput.addEventListener('input', function() {
            const password = this.value;

            // Check requirements
            const hasLength = password.length >= SECURITY_CONFIG.minPasswordLength;
            const hasUpper = /[A-Z]/.test(password);
            const hasLower = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[@$!%*?&]/.test(password);

            // Update requirement indicators
            updateRequirement(requirements.length, hasLength);
            updateRequirement(requirements.upper, hasUpper);
            updateRequirement(requirements.lower, hasLower);
            updateRequirement(requirements.number, hasNumber);
            updateRequirement(requirements.special, hasSpecial);

            validateForm();
        });

        // Password confirmation matching
        confirmInput.addEventListener('input', function() {
            const matchIndicator = document.getElementById('password-match');
            const passwordsMatch = passwordInput.value === this.value;

            if (this.value) {
                matchIndicator.textContent = passwordsMatch
                    ? '✓ Passwords match'
                    : '✗ Passwords do not match';
                matchIndicator.className = passwordsMatch
                    ? 'form-text text-success'
                    : 'form-text text-danger';
            } else {
                matchIndicator.textContent = '';
            }

            validateForm();
        });
    }

    function updateRequirement(element, met) {
        const icon = element.querySelector('i');
        if (met) {
            element.className = 'text-success';
            icon.className = 'fas fa-check me-1';
        } else {
            element.className = 'text-danger';
            icon.className = 'fas fa-times me-1';
        }
    }

    // Form validation
    function setupFormValidation() {
        const form = document.getElementById('secure-register-form');
        const submitButton = document.getElementById('register-button');

        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                showSecurityAlert('Please fix the validation errors before submitting.');
            } else {
                // Add security delay to prevent automated submissions
                submitButton.disabled = true;
                submitButton.querySelector('.spinner-border').classList.remove('d-none');
                submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Secure Account...';

                // Log submission attempt
                logSecurityEvent('form_submission_attempt', {
                    timestamp: new Date().toISOString(),
                    userAgent: navigator.userAgent
                });
            }
        });
    }

    function validateForm() {
        const form = document.getElementById('secure-register-form');
        const submitButton = document.getElementById('register-button');
        const isValid = form.checkValidity();

        submitButton.disabled = !isValid;
        return isValid;
    }

    // Session security
    function setupSessionSecurity() {
        let lastActivity = Date.now();

        document.addEventListener('mousemove', updateLastActivity);
        document.addEventListener('keypress', updateLastActivity);

        function updateLastActivity() {
            lastActivity = Date.now();
        }

        setInterval(() => {
            if (Date.now() - lastActivity > SECURITY_CONFIG.sessionTimeout) {
                showSecurityAlert('Session timeout for security. Please refresh the page.');
            }
        }, 60000); // Check every minute
    }

    // Real-time validation
    function setupRealTimeValidation() {
        const inputs = document.querySelectorAll('.security-input');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });

            input.addEventListener('input', function() {
                clearFieldError(this);
            });
        });

        // Phone number formatting
        const phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');

            // Auto-format Philippine numbers
            if (this.value.startsWith('09') && this.value.length === 11) {
                this.value = '+63' + this.value.substring(1);
            }
        });
    }

    function validateField(field) {
        if (!field.checkValidity()) {
            showFieldError(field, field.validationMessage);
        } else {
            clearFieldError(field);
        }
        validateForm();
    }

    function showFieldError(field, message) {
        clearFieldError(field);
        field.classList.add('is-invalid');

        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback d-block security-error';
        errorDiv.innerHTML = `<i class="fas fa-shield-alt me-1"></i> ${message}`;
        field.parentNode.appendChild(errorDiv);
    }

    function clearFieldError(field) {
        field.classList.remove('is-invalid');
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
    }

    // Security utilities
    function showSecurityAlert(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-warning alert-dismissible fade show';
        alertDiv.innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.card-body').prepend(alertDiv);
    }

    function logSecurityEvent(event, data) {
        // In a real implementation, this would send to your security logging system
        console.log('Security Event:', event, data);
    }

    // User type selection styling
    const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
    userTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            document.querySelectorAll('.security-card').forEach(card => {
                card.classList.remove('border-primary', 'bg-light');
            });
            if (this.checked) {
                this.closest('.security-card').classList.add('border-primary', 'bg-light');
            }
            validateForm();
        });

        if (radio.checked) {
            radio.closest('.security-card').classList.add('border-primary', 'bg-light');
        }
    });

    // Initialize form validation
    validateForm();
});
</script>

<style>
.security-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
}

.security-card:hover {
    border-color: #dee2e6;
    transform: translateY(-2px);
}

.security-card.border-primary {
    border-color: #0d6efd !important;
    background-color: #f8f9fa !important;
}

.security-input {
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.security-input:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.15);
}

.security-input.is-invalid {
    border-color: #dc3545;
}

.security-toggle {
    border: 2px solid #e9ecef;
    border-left: none;
}

.security-toggle:hover {
    background-color: #f8f9fa;
}

.security-error {
    font-size: 0.875em;
    font-weight: 500;
}

.security-hint {
    font-size: 0.8em;
}

.security-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.password-requirements ul li {
    font-size: 0.8em;
    margin-bottom: 0.25rem;
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    .security-card,
    .security-input {
        transition: none;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .security-input {
        border-width: 3px;
    }
}

/* Print styles */
@media print {
    .security-card,
    .security-toggle,
    .g-recaptcha {
        display: none !important;
    }
}

/* Green theme background */
.bg-success-dark {
    background-color: rgba(0, 0, 0, 0.2) !important;
}
</style>
@endsection
@endsection
