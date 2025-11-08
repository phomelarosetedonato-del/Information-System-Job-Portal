@extends('layouts.app')

@section('title', 'Contact Us - PWD Job Portal')

@section('content')
<div class="container-fluid py-4 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-11 col-lg-10">
            <!-- Page Header -->
            <div class="page-header mb-4">
                <h1 class="h2 fw-bold text-primary mb-2">
                    <i class="fas fa-headset me-2"></i>Contact Us
                </h1>
                <p class="text-muted">Get in touch with our PWD Job Portal support team. We're here to help you with job opportunities, training programs, and accessibility support.</p>
            </div>

            <div class="row g-4">
                <!-- Contact Information & Resources -->
                <div class="col-lg-4">
                    <!-- Quick Contact Cards -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Quick Support
                            </h5>

                            <div class="d-flex align-items-start mb-3 p-3 bg-light rounded">
                                <i class="fas fa-briefcase text-primary me-3 mt-1 fs-5"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Job Applications</h6>
                                    <p class="text-muted small mb-0">Need help with job applications or employer matching?</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3 p-3 bg-light rounded">
                                <i class="fas fa-graduation-cap text-success me-3 mt-1 fs-5"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Training Programs</h6>
                                    <p class="text-muted small mb-0">Inquire about skills training and development programs</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start p-3 bg-light rounded">
                                <i class="fas fa-universal-access text-warning me-3 mt-1 fs-5"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Accessibility Support</h6>
                                    <p class="text-muted small mb-0">Report accessibility issues or request accommodations</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>Our Office
                            </h5>

                            <div class="contact-info">
                                <div class="d-flex align-items-start mb-3">
                                    <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Office Address</h6>
                                        <p class="text-muted mb-0 small">
                                            PWD Employment Center<br>
                                            123 Disability Support Blvd<br>
                                            Manila, Philippines 1000
                                        </p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start mb-3">
                                    <i class="fas fa-phone text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Contact Numbers</h6>
                                        <p class="text-muted mb-0 small">
                                            Main: (02) 1234-5678<br>
                                            Job Hotline: 0917-123-JOBS
                                        </p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start mb-3">
                                    <i class="fas fa-envelope text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Email Address</h6>
                                        <p class="text-muted mb-0 small">
                                            careers@pwd-jobs.gov.ph<br>
                                            support@pwd-jobs.gov.ph
                                        </p>
                                    </div>
                                </div>

                                <div class="d-flex align-items-start">
                                    <i class="fas fa-clock text-primary me-3 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Office Hours</h6>
                                        <p class="text-muted mb-0 small">
                                            Mon-Fri: 8:00 AM - 5:00 PM<br>
                                            Saturday: 8:00 AM - 12:00 PM<br>
                                            <em>Closed on Sundays & Holidays</em>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Urgent Support -->
                    <div class="alert alert-warning mt-4 border-0 shadow-sm">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle me-3 mt-1 fs-5"></i>
                            <div>
                                <h6 class="fw-bold mb-2">Urgent Employment Support</h6>
                                <p class="mb-2 small">For immediate job placement assistance or workplace accommodation emergencies:</p>
                                <p class="mb-0 fw-bold text-dark">Emergency Hotline: 0919-999-HELP</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-paper-plane me-2"></i>
                                Send Us a Message
                            </h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('contact.submit') }}" id="contactForm">
                                @csrf

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            Full Name <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}"
                                               placeholder="Enter your full name" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            Email Address <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                               id="email" name="email" value="{{ old('email') }}"
                                               placeholder="your.email@example.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="subject" class="form-label fw-semibold">
                                            Inquiry Type <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('subject') is-invalid @enderror"
                                                id="subject" name="subject" required>
                                            <option value="">What can we help you with?</option>
                                            <option value="job_application_support" {{ old('subject') == 'job_application_support' ? 'selected' : '' }}>Job Application Support</option>
                                            <option value="employer_partnership" {{ old('subject') == 'employer_partnership' ? 'selected' : '' }}>Employer Partnership</option>
                                            <option value="training_programs" {{ old('subject') == 'training_programs' ? 'selected' : '' }}>Training Programs</option>
                                            <option value="technical_support" {{ old('subject') == 'technical_support' ? 'selected' : '' }}>Technical Support</option>
                                            <option value="accessibility_concerns" {{ old('subject') == 'accessibility_concerns' ? 'selected' : '' }}>Accessibility Concerns</option>
                                            <option value="account_issues" {{ old('subject') == 'account_issues' ? 'selected' : '' }}>Account Issues</option>
                                            <option value="feedback" {{ old('subject') == 'feedback' ? 'selected' : '' }}>Feedback & Suggestions</option>
                                            <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <label for="message" class="form-label fw-semibold">
                                            Message <span class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control @error('message') is-invalid @enderror"
                                                  id="message" name="message" rows="6" required
                                                  placeholder="Please describe your inquiry in detail. For job-related inquiries, please include your skills and experience...">{{ old('message') }}</textarea>
                                        <div class="form-text text-end">
                                            <span id="charCount">0</span> characters
                                        </div>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input @error('terms') is-invalid @enderror"
                                                   type="checkbox" id="terms" name="terms" required>
                                            <label class="form-check-label small" for="terms">
                                                I agree to the <a href="#" data-bs-toggle="modal" data-bs-target="#privacyModal" class="text-decoration-none">privacy policy</a>
                                                and consent to being contacted regarding employment opportunities and support services.
                                            </label>
                                            @error('terms')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary px-4 py-2">
                                            <i class="fas fa-paper-plane me-2"></i>Send Message
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary px-4 py-2 ms-2">
                                            <i class="fas fa-undo me-2"></i>Reset Form
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Resources Section -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-4">
                                <i class="fas fa-rocket me-2"></i>
                                Quick Resources
                            </h5>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="text-center p-3 h-100">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                            <i class="fas fa-user-plus text-primary fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold">PWD Registration</h6>
                                        <p class="text-muted small mb-0">Complete your PWD profile and get verified to access exclusive job opportunities.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 h-100">
                                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                            <i class="fas fa-search text-success fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold">Job Search</h6>
                                        <p class="text-muted small mb-0">Browse through hundreds of PWD-friendly job positions from partner employers.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-3 h-100">
                                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                                            <i class="fas fa-chart-line text-warning fs-4"></i>
                                        </div>
                                        <h6 class="fw-bold">Career Development</h6>
                                        <p class="text-muted small mb-0">Access training programs and skill development courses to enhance your employability.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Privacy Policy Modal -->
<div class="modal fade" id="privacyModal" tabindex="-1" aria-labelledby="privacyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacyModalLabel">Privacy Policy & Data Protection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6>Data Collection Purpose</h6>
                <p>We collect your information to provide employment matching services, connect you with potential employers, and offer relevant training opportunities.</p>

                <h6>Information Usage</h6>
                <p>Your data will be used to match your skills with job opportunities, provide career counseling, and improve our PWD employment services.</p>

                <h6>Data Protection</h6>
                <p>We implement strict security measures to protect your personal information and will never share your data without your explicit consent, except as required by employment matching services.</p>

                <h6>Retention Period</h6>
                <p>Your information will be retained for as long as necessary to provide employment services and as required by government regulations for PWD support programs.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">I Understand</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    const contactForm = document.getElementById('contactForm');

    // Character counter
    function updateCharCount() {
        charCount.textContent = messageTextarea.value.length;
    }

    messageTextarea.addEventListener('input', updateCharCount);
    updateCharCount(); // Initialize count

    // Enhanced form validation
    contactForm.addEventListener('submit', function(e) {
        const requiredFields = contactForm.querySelectorAll('[required]');
        let isValid = true;
        let firstInvalidField = null;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                if (!firstInvalidField) {
                    firstInvalidField = field;
                }
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        // Validate email format
        const emailField = document.getElementById('email');
        if (emailField.value && !isValidEmail(emailField.value)) {
            emailField.classList.add('is-invalid');
            isValid = false;
            if (!firstInvalidField) {
                firstInvalidField = emailField;
            }
        }

        if (!isValid) {
            e.preventDefault();
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidField.focus();
        }
    });

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    // Auto-resize textarea
    messageTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>

<style>
.contact-info .d-flex {
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.contact-info .d-flex:last-child {
    border-bottom: none;
}

.card {
    border-radius: 12px;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    transition: all 0.2s ease-in-out;
}

.form-control:focus, .form-select:focus {
    border-color: #166534;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

.btn {
    border-radius: 8px;
    font-weight: 500;
}
</style>
@endsection
