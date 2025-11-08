@extends('layouts.app')

@section('title', 'Login - PWD System')

@section('content')
<div class="container-fluid p-0">
    <!-- Skip Navigation for Screen Readers -->
    <a href="#main-content" class="sr-only sr-only-focusable bg-primary text-white p-2 position-absolute top-0 start-0" style="z-index: 9999; transform: translateY(-100%); transition: transform 0.3s;">
        Skip to main content
    </a>

    <div class="row g-0 min-vh-100">
        <!-- Left Side - Welcome Section with Accessibility Features -->
        <div class="col-lg-6 d-none d-lg-flex bg-primary text-white align-items-center justify-content-center p-5">
            <div class="text-center" style="max-width: 500px;">
                <!-- Accessibility Controls -->
                <div class="accessibility-controls mb-5 p-4 bg-primary-dark rounded-lg">
                    <h2 class="h3 mb-4" id="accessibility-heading">
                        <i class="fas fa-universal-access me-2"></i>Accessibility Features
                    </h2>

                    <div class="row g-3">
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-light btn-sm w-100" id="increase-font" aria-label="Increase font size">
                                <i class="fas fa-text-height me-1"></i> Larger Text
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-light btn-sm w-100" id="high-contrast" aria-label="Toggle high contrast mode">
                                <i class="fas fa-adjust me-1"></i> High Contrast
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-light btn-sm w-100" id="read-page" aria-label="Read page content aloud">
                                <i class="fas fa-volume-up me-1"></i> Read Aloud
                            </button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="btn btn-outline-light btn-sm w-100" id="simplify-layout" aria-label="Simplify page layout">
                                <i class="fas fa-eye me-1"></i> Simple View
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Welcome Message -->
                <div class="welcome-message">
                    <h1 class="display-5 fw-bold mb-4" aria-label="Welcome to Alaminos City PWD System">
                        Welcome to Alaminos City<br>PWD System
                    </h1>
                    <p class="lead mb-4" style="font-size: 1.25rem;">
                        A secure and accessible platform designed specifically for Persons with Disabilities
                    </p>

                    <!-- Feature Icons -->
                    <div class="row g-4 mt-5">
                        <div class="col-4 text-center">
                            <div class="feature-icon mb-2">
                                <i class="fas fa-shield-alt fa-2x" aria-hidden="true"></i>
                            </div>
                            <small>Secure Login</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="feature-icon mb-2">
                                <i class="fas fa-universal-access fa-2x" aria-hidden="true"></i>
                            </div>
                            <small>Fully Accessible</small>
                        </div>
                        <div class="col-4 text-center">
                            <div class="feature-icon mb-2">
                                <i class="fas fa-hands-helping fa-2x" aria-hidden="true"></i>
                            </div>
                            <small>PWD Support</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-lg-5">
            <div class="w-100" style="max-width: 400px;" id="main-content" tabindex="-1">
                <!-- Logo and Header -->
                <div class="text-center mb-5">
                    <h1 class="h2 fw-bold text-primary mb-2" id="login-heading">
                        Sign In to Your Account
                    </h1>
                    <p class="text-muted">
                        Access job opportunities, training programs, and community support
                    </p>
                </div>

                <!-- Session Status Messages -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert" aria-live="polite">
                        <div class="d-flex">
                            <i class="fas fa-check-circle me-3 mt-1" aria-hidden="true"></i>
                            <div>{{ session('status') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle me-3 mt-1" aria-hidden="true"></i>
                            <div>{{ session('error') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Account Locked Alert -->
                @if (session('locked'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert" aria-live="assertive">
                        <div class="d-flex">
                            <i class="fas fa-lock me-3 mt-1" aria-hidden="true"></i>
                            <div>
                                <strong>Account Temporarily Locked</strong><br>
                                {{ session('locked') }}
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Security Status Alert -->
                @if(session('security_warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert" aria-live="polite">
                    <div class="d-flex">
                        <i class="fas fa-exclamation-triangle me-3 mt-1" aria-hidden="true"></i>
                        <div>
                            <strong>Security Recommendation</strong><br>
                            Your security score is {{ session('security_score') }}/100.
                            @foreach(session('security_recommendations', []) as $recommendation)
                                <br>- {{ $recommendation }}
                            @endforeach
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close security warning"></button>
                </div>
                @endif

                <!-- Login Form -->
                <div class="card border-0 shadow-sm" role="form" aria-labelledby="login-heading">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}" id="login-form">
                            @csrf

                            <!-- Email Input with Enhanced Accessibility -->
                            <div class="mb-4">
                                <label for="login" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2 text-primary" aria-hidden="true"></i>
                                    Email or Username
                                    <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <div class="input-group">
                                        <input
                                            id="login"
                                            type="text"
                                            class="form-control form-control-lg @error('login') is-invalid @enderror"
                                            name="login"
                                            value="{{ old('login') }}"
                                            required
                                            autocomplete="username"
                                            autofocus
                                            aria-required="true"
                                            aria-describedby="email-help @error('login') login-error @enderror"
                                            placeholder="you@example.com or username"
                                        >
                                    <span class="input-group-text bg-transparent">
                                        <i class="fas fa-user text-muted" aria-hidden="true"></i>
                                    </span>
                                </div>

                                <!-- Help Text -->
                                <div id="email-help" class="form-text text-muted small mt-1">
                                    Enter your registered email address
                                </div>

                                <!-- Error Message -->
                                @error('login')
                                    <div id="login-error" class="invalid-feedback d-block" role="alert" aria-live="polite">
                                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <!-- Password Input with Toggle Visibility -->
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-2 text-primary" aria-hidden="true"></i>
                                    Password
                                    <span class="text-danger" aria-hidden="true">*</span>
                                </label>
                                <div class="input-group">
                                    <input
                                        id="password"
                                        type="password"
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        name="password"
                                        required
                                        autocomplete="current-password"
                                        aria-required="true"
                                        aria-describedby="password-help password-toggle @error('password') password-error @enderror"
                                        placeholder="Enter your password"
                                    >
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary password-toggle"
                                        id="password-toggle"
                                        aria-label="Show password"
                                        aria-pressed="false"
                                    >
                                        <i class="fas fa-eye" aria-hidden="true"></i>
                                    </button>
                                </div>

                                <!-- Help Text -->
                                <div id="password-help" class="form-text text-muted small mt-1">
                                    Enter your account password
                                </div>

                                <!-- Error Message -->
                                @error('password')
                                    <div id="password-error" class="invalid-feedback d-block" role="alert" aria-live="polite">
                                        <i class="fas fa-exclamation-circle me-1" aria-hidden="true"></i>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <!-- Remember Me & Security Info -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                        {{ old('remember') ? 'checked' : '' }}
                                        aria-describedby="remember-help"
                                    >
                                    <label class="form-check-label fw-semibold" for="remember">
                                        Keep me signed in
                                    </label>
                                </div>
                                <div id="remember-help" class="form-text text-muted small">
                                    Only use on trusted devices
                                </div>
                            </div>

                            <!-- Login Button -->
                            <div class="d-grid mb-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-lg fw-semibold py-3"
                                    id="login-button"
                                    aria-describedby="login-help"
                                >
                                    <i class="fas fa-sign-in-alt me-2" aria-hidden="true"></i>
                                    Sign In
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                </button>
                                <div id="login-help" class="form-text text-center mt-2">
                                    Secure connection • Encrypted transmission
                                </div>
                            </div>

                            <!-- Additional Help Links -->
                            <div class="text-center">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link text-decoration-none p-0 me-3" href="{{ route('password.request') }}">
                                        <i class="fas fa-key me-1" aria-hidden="true"></i>
                                        Forgot Password?
                                    </a>
                                @endif

                                @if (Route::has('register'))
                                    <a class="btn btn-link text-decoration-none p-0" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1" aria-hidden="true"></i>
                                        Create Account
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Security Features Notice -->
                <div class="mt-4 p-3 bg-light rounded text-center">
                    <h3 class="h6 fw-semibold mb-2" id="security-features-heading">
                        <i class="fas fa-shield-alt me-2 text-success" aria-hidden="true"></i>
                        Security Features Enabled
                    </h3>
                    <div class="row g-2 small text-muted">
                        <div class="col-4">Account Lockout</div>
                        <div class="col-4">Login Tracking</div>
                        <div class="col-4">Session Security</div>
                        <div class="col-4">Password Encryption</div>
                        <div class="col-4">Activity Logging</div>
                        <div class="col-4">Role-based Access</div>
                    </div>
                </div>

                <!-- Admin Notice -->
                <div class="mt-3 alert alert-info text-center">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Admin Users:</strong> You will be redirected to the admin dashboard automatically
                    </small>
                </div>

                <!-- Emergency Access -->
                <div class="mt-3 text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#emergencyModal">
                        <i class="fas fa-life-ring me-1" aria-hidden="true"></i>
                        Need Emergency Access?
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Access Modal -->
<div class="modal fade" id="emergencyModal" tabindex="-1" aria-labelledby="emergencyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h2 class="modal-title h5" id="emergencyModalLabel">
                    <i class="fas fa-life-ring me-2"></i>
                    Emergency Access Assistance
                </h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>If you're having trouble accessing your account due to disability-related issues:</p>
                <ul>
                    <li><strong>Phone Support:</strong> Call (075) 123-4567</li>
                    <li><strong>Email Support:</strong> pwd.support@alaminoscity.gov.ph</li>
                    <li><strong>In-Person:</strong> Visit Alaminos City Hall, PWD Office</li>
                </ul>
                <p class="mb-0"><small>Our support team is trained to assist with various accessibility needs.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="tel:+63751234567" class="btn btn-warning">
                    <i class="fas fa-phone me-1"></i> Call Now
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Skip navigation focus
    const skipLink = document.querySelector('.sr-only-focusable');
    skipLink.addEventListener('focus', function() {
        this.style.transform = 'translateY(0)';
    });
    skipLink.addEventListener('blur', function() {
        this.style.transform = 'translateY(-100%)';
    });

    // Password visibility toggle
    const passwordToggle = document.getElementById('password-toggle');
    const passwordInput = document.getElementById('password');

    if (passwordToggle && passwordInput) {
        passwordToggle.addEventListener('click', function() {
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            this.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            this.setAttribute('aria-pressed', isPassword);
            this.querySelector('i').className = isPassword ? 'fas fa-eye-slash' : 'fas fa-eye';
        });
    }

    // Accessibility controls
    const increaseFontBtn = document.getElementById('increase-font');
    const highContrastBtn = document.getElementById('high-contrast');
    const readPageBtn = document.getElementById('read-page');
    const simplifyLayoutBtn = document.getElementById('simplify-layout');

    if (increaseFontBtn) {
        increaseFontBtn.addEventListener('click', function() {
            document.body.classList.toggle('large-font');
            localStorage.setItem('largeFont', document.body.classList.contains('large-font'));
        });
    }

    if (highContrastBtn) {
        highContrastBtn.addEventListener('click', function() {
            document.body.classList.toggle('high-contrast');
            localStorage.setItem('highContrast', document.body.classList.contains('high-contrast'));
        });
    }

    if (readPageBtn) {
        readPageBtn.addEventListener('click', function() {
            if ('speechSynthesis' in window) {
                const pageTitle = document.getElementById('login-heading')?.textContent || 'Login Page';
                const welcomeText = document.querySelector('.welcome-message p')?.textContent || 'Welcome to Alaminos City PWD System';
                const speech = new SpeechSynthesisUtterance(pageTitle + '. ' + welcomeText);
                window.speechSynthesis.speak(speech);
            } else {
                alert('Text-to-speech is not supported in your browser.');
            }
        });
    }

    if (simplifyLayoutBtn) {
        simplifyLayoutBtn.addEventListener('click', function() {
            document.body.classList.toggle('simplified-layout');
            localStorage.setItem('simplifiedLayout', document.body.classList.contains('simplified-layout'));
        });
    }

    // Load saved accessibility settings
    if (localStorage.getItem('largeFont') === 'true') {
        document.body.classList.add('large-font');
    }
    if (localStorage.getItem('highContrast') === 'true') {
        document.body.classList.add('high-contrast');
    }
    if (localStorage.getItem('simplifiedLayout') === 'true') {
        document.body.classList.add('simplified-layout');
    }

    // Form submission loading state
    const loginForm = document.getElementById('login-form');
    const loginButton = document.getElementById('login-button');

    if (loginForm && loginButton) {
        loginForm.addEventListener('submit', function() {
            const spinner = loginButton.querySelector('.spinner-border');
            loginButton.disabled = true;
            if (spinner) {
                spinner.classList.remove('d-none');
            }
            loginButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing In...';
        });
    }

    // Enhanced error announcement for screen readers
    @if($errors->any())
        const errorMessage = "There are errors in the form. Please check the highlighted fields.";
        const errorAlert = document.createElement('div');
        errorAlert.className = 'alert alert-danger sr-only';
        errorAlert.setAttribute('role', 'alert');
        errorAlert.setAttribute('aria-live', 'assertive');
        errorAlert.textContent = errorMessage;
        document.body.insertBefore(errorAlert, document.body.firstChild);

        // Announce errors after a short delay
        setTimeout(() => {
            if ('speechSynthesis' in window) {
                const speech = new SpeechSynthesisUtterance(errorMessage);
                window.speechSynthesis.speak(speech);
            }
        }, 1000);
    @endif

    // Auto-focus on email field if no errors
    @if(!$errors->has('email'))
        const emailField = document.getElementById('email');
        if (emailField) {
            setTimeout(() => emailField.focus(), 100);
        }
    @endif
});
</script>

<!-- CSS for Accessibility Features -->
<style>
.large-font {
    font-size: 1.2em;
}
.large-font .form-control {
    font-size: 1.1em;
    padding: 0.75rem 1rem;
}

.high-contrast {
    --bs-body-color: #000 !important;
    --bs-body-bg: #fff !important;
    --bs-primary: #166534 !important;
    --bs-border-color: #000 !important;
    filter: contrast(1.5);
}
.high-contrast .card {
    border: 2px solid #000 !important;
}
.high-contrast .btn-primary {
    background-color: #166534 !important;
    border-color: #166534 !important;
    color: #fff !important;
}
.high-contrast .text-muted {
    color: #666 !important;
}
.high-contrast .bg-primary {
    background-color: #166534 !important;
}

.simplified-layout .card {
    border: 1px solid #ccc !important;
    box-shadow: none !important;
}
.simplified-layout .feature-icon,
.simplified-layout .accessibility-controls {
    display: none !important;
}

/* Focus styles for keyboard navigation */
.form-control:focus,
.btn:focus {
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25) !important;
    outline: 2px solid transparent !important;
}

/* High contrast focus */
.high-contrast .form-control:focus,
.high-contrast .btn:focus {
    box-shadow: 0 0 0 3px #000 !important;
    outline: 2px solid #fff !important;
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Screen reader only */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

.sr-only-focusable:focus {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 9999;
    width: auto;
    height: auto;
    padding: 0.5rem 1rem;
    margin: 0;
    overflow: visible;
    clip: auto;
    white-space: normal;
    background: #000;
    color: #fff;
    text-decoration: none;
}

/* Input group focus fix */
.input-group:focus-within {
    z-index: 3;
}
</style>
@endsection
