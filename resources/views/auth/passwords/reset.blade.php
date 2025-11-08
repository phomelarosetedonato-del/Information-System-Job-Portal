@extends('layouts.app')

@section('title', 'Reset Password - PWD System')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h2 class="h4 mb-0">
                        <i class="fas fa-key me-2"></i>Reset Your Password
                    </h2>
                </div>

                <div class="card-body p-5">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" id="passwordResetForm">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email Address -->
                        <div class="mb-4">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope me-2"></i>Email Address
                            </label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ $email ?? old('email') }}"
                                   required
                                   autocomplete="email"
                                   autofocus
                                   placeholder="Enter your email address">

                            @error('email')
                                <div class="invalid-feedback">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fas fa-lock me-2"></i>New Password
                            </label>
                            <div class="input-group">
                                <input id="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       name="password"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Enter new password">
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>

                            @error('password')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                            @enderror

                            <div class="password-strength mt-2">
                                <div class="progress" style="height: 5px;">
                                    <div class="progress-bar" id="passwordStrengthBar" role="progressbar"></div>
                                </div>
                                <small class="form-text text-muted" id="passwordStrengthText">
                                    Password strength: None
                                </small>
                            </div>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <label for="password-confirm" class="form-label">
                                <i class="fas fa-lock me-2"></i>Confirm New Password
                            </label>
                            <div class="input-group">
                                <input id="password-confirm" type="password"
                                       class="form-control"
                                       name="password_confirmation"
                                       required
                                       autocomplete="new-password"
                                       placeholder="Confirm your new password">
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                        data-target="password-confirm">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <span id="passwordMatch" class="d-none text-success">
                                    <i class="fas fa-check me-1"></i>Passwords match
                                </span>
                                <span id="passwordMismatch" class="d-none text-danger">
                                    <i class="fas fa-times me-1"></i>Passwords do not match
                                </span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="resetButton">
                                <i class="fas fa-redo me-2"></i>Reset Password
                            </button>
                        </div>

                        <!-- Back to Login -->
                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}" class="text-decoration-none">
                                <i class="fas fa-arrow-left me-1"></i>Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Requirements -->
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-info-circle me-2"></i>Password Requirements
                    </h6>
                    <ul class="list-unstyled small mb-0">
                        <li id="reqLength" class="text-muted">
                            <i class="fas fa-circle me-1"></i>At least 8 characters
                        </li>
                        <li id="reqUppercase" class="text-muted">
                            <i class="fas fa-circle me-1"></i>One uppercase letter
                        </li>
                        <li id="reqLowercase" class="text-muted">
                            <i class="fas fa-circle me-1"></i>One lowercase letter
                        </li>
                        <li id="reqNumber" class="text-muted">
                            <i class="fas fa-circle me-1"></i>One number
                        </li>
                        <li id="reqSpecial" class="text-muted">
                            <i class="fas fa-circle me-1"></i>One special character
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password-confirm');
    const strengthBar = document.getElementById('passwordStrengthBar');
    const strengthText = document.getElementById('passwordStrengthText');
    const toggleButtons = document.querySelectorAll('.toggle-password');

    // Toggle password visibility
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            const type = target.type === 'password' ? 'text' : 'password';
            target.type = type;
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
        });
    });

    // Password strength checker
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = calculatePasswordStrength(password);

        strengthBar.style.width = strength.percentage + '%';
        strengthBar.className = 'progress-bar ' + strength.class;
        strengthText.textContent = 'Password strength: ' + strength.text;
        strengthText.className = 'form-text ' + strength.textClass;

        // Update requirements
        updateRequirements(password);
    });

    // Password confirmation check
    confirmInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirm = this.value;

        const match = document.getElementById('passwordMatch');
        const mismatch = document.getElementById('passwordMismatch');

        if (confirm === '') {
            match.classList.add('d-none');
            mismatch.classList.add('d-none');
        } else if (password === confirm) {
            match.classList.remove('d-none');
            mismatch.classList.add('d-none');
        } else {
            match.classList.add('d-none');
            mismatch.classList.remove('d-none');
        }
    });

    function calculatePasswordStrength(password) {
        let score = 0;
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };

        // Calculate score
        Object.values(requirements).forEach(met => {
            if (met) score++;
        });

        const percentage = (score / 5) * 100;

        if (password.length === 0) {
            return { percentage: 0, class: 'bg-secondary', text: 'None', textClass: 'text-muted' };
        } else if (score <= 2) {
            return { percentage, class: 'bg-danger', text: 'Weak', textClass: 'text-danger' };
        } else if (score <= 3) {
            return { percentage, class: 'bg-warning', text: 'Fair', textClass: 'text-warning' };
        } else if (score <= 4) {
            return { percentage, class: 'bg-info', text: 'Good', textClass: 'text-info' };
        } else {
            return { percentage, class: 'bg-success', text: 'Strong', textClass: 'text-success' };
        }
    }

    function updateRequirements(password) {
        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };

        Object.keys(requirements).forEach(req => {
            const element = document.getElementById('req' + req.charAt(0).toUpperCase() + req.slice(1));
            if (requirements[req]) {
                element.classList.remove('text-muted');
                element.classList.add('text-success');
                element.innerHTML = '<i class="fas fa-check-circle me-1"></i>' + element.textContent.replace(/^.*?\]/, '');
            } else {
                element.classList.remove('text-success');
                element.classList.add('text-muted');
                element.innerHTML = '<i class="fas fa-circle me-1"></i>' + element.textContent.replace(/^.*?\]/, '');
            }
        });
    }

    // Form submission validation
    document.getElementById('passwordResetForm').addEventListener('submit', function(e) {
        const password = passwordInput.value;
        const confirm = confirmInput.value;

        if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match. Please confirm your password.');
            confirmInput.focus();
        }

        if (password.length < 8) {
            e.preventDefault();
            alert('Password must be at least 8 characters long.');
            passwordInput.focus();
        }
    });
});
</script>
@endpush

<style>
.password-strength {
    display: none;
}

#password:not(:placeholder-shown) + .input-group + .password-strength {
    display: block;
}

.toggle-password {
    border-left: 0;
}

.toggle-password:focus {
    box-shadow: none;
    border-color: #ced4da;
}

.progress {
    background-color: #e9ecef;
}

.card {
    border-radius: 15px;
}

.card-header {
    border-radius: 15px 15px 0 0 !important;
}

.btn {
    border-radius: 10px;
}

.form-control {
    border-radius: 8px;
}
</style>
