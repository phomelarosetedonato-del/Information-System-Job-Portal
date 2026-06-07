@extends('layouts.app')

@section('title', 'Forgot Password - PWD System')

@section('content')
<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">
        <!-- Left Side - Security Information -->
        <div class="col-lg-6 d-none d-lg-flex text-white align-items-center justify-content-center p-5" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="text-center" style="max-width: 500px;">
                <div class="mb-5">
                    <i class="fas fa-shield-alt fa-5x mb-4"></i>
                    <h1 class="display-5 fw-bold mb-4">Secure Password Recovery</h1>
                    <p class="lead mb-4">
                        Your account security is our top priority. We'll send you a secure link to reset your password.
                    </p>
                </div>

                <!-- Security Features -->
                <div class="text-start">
                    <h3 class="h5 mb-3">Security Features:</h3>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Time-Limited Links:</strong> Reset links expire after 60 minutes
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Email Verification:</strong> Links are sent only to verified emails
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>One-Time Use:</strong> Each link can only be used once
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>IP Tracking:</strong> All reset attempts are logged
                        </li>
                    </ul>
                </div>

                <!-- Help Notice -->
                <div class="mt-5 p-4 rounded" style="background-color: rgba(0, 0, 0, 0.2);">
                    <h4 class="h6 mb-2">
                        <i class="fas fa-life-ring me-2"></i>Need Help?
                    </h4>
                    <p class="small mb-0">
                        If you're having trouble, contact support at
                        <strong>pwd.support@alaminoscity.gov.ph</strong>
                    </p>
                </div>
            </div>
        </div>

        <!-- Right Side - Password Reset Form -->
        <div class="col-lg-6 d-flex align-items-center justify-content-center p-4 p-lg-5">
            <div class="w-100" style="max-width: 450px;">
                <!-- Green Header -->
                <div class="text-center mb-5" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); border-radius: 12px; padding: 32px 0 20px 0; color: #fff;">
                    <div class="mb-3">
                        <i class="fas fa-key fa-3x" style="color: #fff;"></i>
                    </div>
                </div>

                <!-- Status Messages -->
                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <div class="d-flex">
                            <i class="fas fa-check-circle me-3 mt-1"></i>
                            <div>
                                <strong>Email Sent!</strong><br>
                                {{ session('status') }}
                                <small class="d-block mt-2">Please check your inbox and spam folder.</small>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Password Reset Form -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('password.email') }}" id="reset-request-form">
                            @csrf

                            <!-- Email Input -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-2 text-success"></i>
                                    Email Address
                                    <span class="text-danger">*</span>
                                </label>
                                <input
                                    id="email"
                                    type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                    autofocus
                                    placeholder="your.email@example.com"
                                >
                                <div class="form-text text-muted">
                                    Enter the email address associated with your account
                                </div>

                                @error('email')
                                    <div class="invalid-feedback d-block" role="alert">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>

                            <!-- Security Notice -->
                            <div class="alert alert-info border-0 bg-light mb-4">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle me-3 mt-1 text-info"></i>
                                    <small>
                                        <strong>Security Notice:</strong> For your protection, password reset links expire after 60 minutes.
                                        This action will be logged for security purposes.
                                    </small>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid mb-3">
                                <button
                                    type="submit"
                                    class="btn btn-success btn-lg fw-semibold py-3"
                                    id="submit-button"
                                    style="border-radius: 50px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none;"
                                >
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Send Reset Link
                                    <span class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                                </button>
                            </div>

                            <!-- Back to Login -->
                            <div class="text-center">
                                <a href="{{ route('login') }}" class="btn btn-link text-decoration-none" style="color: #10b981;">
                                    <i class="fas fa-arrow-left me-1"></i>
                                    Back to Login
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Additional Help -->
                <div class="mt-4 text-center">
                    <p class="text-muted small mb-2">
                        <i class="fas fa-question-circle me-1"></i>
                        <strong>Don't have an account?</strong>
                    </p>
                    <a href="{{ route('register') }}" class="btn btn-outline-success" style="border-color: #10b981; color: #10b981; border-radius: 50px;">
                        <i class="fas fa-user-plus me-1"></i>
                        Create New Account
                    </a>
                </div>

                <!-- Security Badge -->
                <div class="mt-4 p-3 bg-light rounded text-center">
                    <small class="text-muted">
                        <i class="fas fa-lock me-2 text-success"></i>
                        <strong>Secure Connection</strong> • All communications are encrypted
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reset-request-form');
    const submitButton = document.getElementById('submit-button');
    const emailInput = document.getElementById('email');

    // Form submission handling
    form.addEventListener('submit', function(e) {
        submitButton.disabled = true;
        const spinner = submitButton.querySelector('.spinner-border');
        if (spinner) {
            spinner.classList.remove('d-none');
        }
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    });

    // Email validation
    emailInput.addEventListener('input', function() {
        this.value = this.value.trim().toLowerCase();
    });

    // Auto-dismiss success messages after 5 seconds
    const successAlert = document.querySelector('.alert-success');
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.remove('show');
            setTimeout(() => successAlert.remove(), 300);
        }, 5000);
    }
});
</script>

<style>
.bg-primary-dark {
    background-color: rgba(0, 0, 0, 0.2);
}

.card {
    border-radius: 15px;
}

.form-control-lg {
    border-radius: 10px;
}

.btn-lg {
    border-radius: 10px;
}

/* Accessibility improvements */
.form-control:focus {
    border-color: #10b981;
    box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.15);
}

@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endsection
