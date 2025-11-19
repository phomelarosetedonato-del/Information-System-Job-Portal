@extends('layouts.app')

@section('title', 'PWD Dashboard - PWD System')

@section('content')
<!-- Skip Navigation for Accessibility -->
<a href="#main-content" class="sr-only sr-only-focusable bg-primary text-white p-2 position-absolute top-0 start-0" style="z-index: 9999; transform: translateY(-100%); transition: transform 0.3s;">
    Skip to main content
</a>

@php
    // Safe method checks to prevent errors
    $applicationCount = method_exists(auth()->user(), 'jobApplications') ? auth()->user()->jobApplications()->count() : 0;
    $enrollmentCount = method_exists(auth()->user(), 'trainingEnrollments') ? auth()->user()->trainingEnrollments()->count() : 0;
    $documentCount = method_exists(auth()->user(), 'documents') ? auth()->user()->documents()->count() : 0;
    $isProfileComplete = method_exists(auth()->user(), 'isProfileComplete') ? auth()->user()->isProfileComplete() : true;
    $hasPwdProfile = method_exists(auth()->user(), 'hasPwdProfile') ? auth()->user()->hasPwdProfile() : true;

    // Initialize arrays to avoid undefined variable errors
    $userJobApplications = [];
    $userTrainingEnrollments = [];

    // Get user applications and enrollments if methods exist
    if (method_exists(auth()->user(), 'jobApplications')) {
        $userJobApplications = auth()->user()->jobApplications()->pluck('job_posting_id')->toArray();
    }
    if (method_exists(auth()->user(), 'trainingEnrollments')) {
        $userTrainingEnrollments = auth()->user()->trainingEnrollments()->pluck('skill_training_id')->toArray();
    }
@endphp

<div class="dashboard-container" id="main-content" tabindex="-1" role="main" aria-labelledby="dashboard-heading">
    <!-- Dashboard Header -->
    <div class="dashboard-header bg-primary text-white py-4">
        <div class="dashboard-header-content">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-1" id="dashboard-heading">
                        <i class="fas fa-universal-access me-2" aria-hidden="true"></i>
                        Welcome back, {{ auth()->user()->name }}!
                    </h1>
                    <p class="mb-0 opacity-75">Here's your PWD dashboard overview</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group">
                        <a href="{{ route('notifications.index') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-bell me-1"></i> Notifications
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                            @endif
                        </a>
                        <a href="{{ route('accessibility.settings') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-universal-access me-1"></i> Accessibility
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="dashboard-content">
        <!-- Session Messages & Alerts -->
        @if (session('status'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" aria-live="polite">
                <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                {{ session('status') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close success message"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" aria-live="polite">
                <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close success notification"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" aria-live="assertive">
                <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close error message"></button>
            </div>
        @endif

        <!-- Profile Completion Alert -->
        @if(!$isProfileComplete)
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert" aria-live="polite">
                <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                <strong>Complete Your Profile:</strong> Please complete your PWD profile to access all features.
                <a href="{{ route('profile.pwd-complete-form') }}" class="alert-link">Complete Profile Now</a>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close profile completion reminder"></button>
            </div>
        @endif

        <!-- Quick Stats -->
        <div class="row mb-4" role="region" aria-labelledby="stats-heading">
            <h2 id="stats-heading" class="sr-only">Quick Statistics</h2>

            <!-- Job Applications Card -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card text-white bg-primary h-100 shadow-sm" role="group" aria-labelledby="applications-card-heading">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="h5 card-title" id="applications-card-heading">My Applications</h3>
                                <p class="card-text display-6" aria-live="polite">{{ $applicationCount }}</p>
                                <small>Active: {{ method_exists(auth()->user(), 'jobApplications') ? auth()->user()->jobApplications()->where('status', 'pending')->count() : 0 }}</small>
                            </div>
                            <i class="fas fa-briefcase fa-2x opacity-75" aria-hidden="true"></i>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('applications.index') }}" class="btn btn-light btn-sm w-100" aria-describedby="applications-help">
                                View Applications
                            </a>
                            <div id="applications-help" class="sr-only">Navigate to your job applications page</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Training Enrollments Card -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card text-white bg-success h-100 shadow-sm" role="group" aria-labelledby="trainings-card-heading">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="h5 card-title" id="trainings-card-heading">My Trainings</h3>
                                <p class="card-text display-6" aria-live="polite">{{ $enrollmentCount }}</p>
                                <small>Enrolled: {{ method_exists(auth()->user(), 'trainingEnrollments') ? auth()->user()->trainingEnrollments()->where('status', 'enrolled')->count() : 0 }}</small>
                            </div>
                            <i class="fas fa-graduation-cap fa-2x opacity-75" aria-hidden="true"></i>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('enrollments.index') }}" class="btn btn-light btn-sm w-100" aria-describedby="trainings-help">
                                View Enrollments
                            </a>
                            <div id="trainings-help" class="sr-only">Navigate to your training enrollments page</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card text-white bg-info h-100 shadow-sm" role="group" aria-labelledby="documents-card-heading">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="h5 card-title" id="documents-card-heading">My Documents</h3>
                                <p class="card-text display-6" aria-live="polite">{{ $documentCount }}</p>
                                <small>Uploaded files</small>
                            </div>
                            <i class="fas fa-file-alt fa-2x opacity-75" aria-hidden="true"></i>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('documents.index') }}" class="btn btn-light btn-sm w-100" aria-describedby="documents-help">
                                Manage Documents
                            </a>
                            <div id="documents-help" class="sr-only">Navigate to your documents management page</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Opportunities Card -->
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card text-white bg-warning h-100 shadow-sm" role="group" aria-labelledby="opportunities-card-heading">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="h5 card-title" id="opportunities-card-heading">Available</h3>
                                <p class="card-text display-6" aria-live="polite">{{ ($jobPostings->count() ?? 0) + ($skillTrainings->count() ?? 0) }}</p>
                                <small>Jobs & Trainings</small>
                            </div>
                            <i class="fas fa-bullseye fa-2x opacity-75" aria-hidden="true"></i>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('job-postings.public') }}" class="btn btn-light btn-sm w-100" aria-describedby="opportunities-help">
                                Explore Opportunities
                            </a>
                            <div id="opportunities-help" class="sr-only">Navigate to available opportunities page</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4" role="navigation" aria-labelledby="quick-actions-heading">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h2 class="h5 mb-0" id="quick-actions-heading">
                            <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-xl-3 col-md-6">
                                <a href="{{ route('job-postings.public') }}" class="btn btn-primary w-100 h-100 py-3 d-flex flex-column align-items-center justify-content-center" aria-describedby="browse-jobs-help">
                                    <i class="fas fa-search fa-2x mb-2" aria-hidden="true"></i>
                                    <div class="fw-bold">Browse Jobs</div>
                                    <small class="opacity-75 text-center">Find employment opportunities</small>
                                </a>
                                <div id="browse-jobs-help" class="sr-only">Open job listings page</div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <a href="{{ route('skill-trainings.public') }}" class="btn btn-success w-100 h-100 py-3 d-flex flex-column align-items-center justify-content-center" aria-describedby="find-training-help">
                                    <i class="fas fa-graduation-cap fa-2x mb-2" aria-hidden="true"></i>
                                    <div class="fw-bold">Find Training</div>
                                    <small class="opacity-75 text-center">Skill development programs</small>
                                </a>
                                <div id="find-training-help" class="sr-only">Open training programs page</div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <a href="{{ route('documents.create') }}" class="btn btn-info w-100 h-100 py-3 d-flex flex-column align-items-center justify-content-center" aria-describedby="upload-documents-help">
                                    <i class="fas fa-upload fa-2x mb-2" aria-hidden="true"></i>
                                    <div class="fw-bold">Upload Documents</div>
                                    <small class="opacity-75 text-center">Resume, certificates, IDs</small>
                                </a>
                                <div id="upload-documents-help" class="sr-only">Open documents upload page</div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <a href="{{ route('profile.edit') }}" class="btn btn-warning w-100 h-100 py-3 d-flex flex-column align-items-center justify-content-center" aria-describedby="update-profile-help">
                                    <i class="fas fa-user-edit fa-2x mb-2" aria-hidden="true"></i>
                                    <div class="fw-bold">Update Profile</div>
                                    <small class="opacity-75 text-center">Keep information current</small>
                                </a>
                                <div id="update-profile-help" class="sr-only">Open profile editing page</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Opportunities Section -->
        <div class="row">
            <!-- Available Job Postings -->
            <div class="col-xl-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0" id="jobs-section-heading">
                            <i class="fas fa-briefcase me-2 text-primary" aria-hidden="true"></i>
                            Available Job Opportunities
                        </h2>
                        <a href="{{ route('job-postings.public') }}" class="btn btn-sm btn-primary">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        @if(isset($jobPostings) && $jobPostings->count() > 0)
                            <div class="list-group list-group-flush" role="list" aria-labelledby="jobs-section-heading">
                                @foreach($jobPostings as $job)
                                    <div class="list-group-item px-0 border-0 mb-2" role="listitem">
                                        <div class="card card-hover border">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h3 class="h6 mb-1">{{ $job->title }}</h3>
                                                        <p class="mb-1 small text-muted">
                                                            <i class="fas fa-building me-1" aria-hidden="true"></i>{{ $job->company ?? 'N/A' }}
                                                        </p>
                                                        <p class="mb-1 small text-muted">
                                                            <i class="fas fa-map-marker-alt me-1" aria-hidden="true"></i>{{ $job->location ?? 'N/A' }}
                                                        </p>
                                                        <p class="mb-2 small text-muted">
                                                            <i class="fas fa-clock me-1" aria-hidden="true"></i>
                                                            Apply by: {{ $job->application_deadline ? $job->application_deadline->format('M j, Y') : 'No deadline' }}
                                                        </p>
                                                    </div>
                                                    <div class="text-end ms-3">
                                                        @if(in_array($job->id, $userJobApplications))
                                                            <span class="badge bg-success mb-2" aria-label="Already applied">Applied</span>
                                                        @else
                                                            <span class="badge bg-primary mb-2" aria-label="Available for application">Available</span>
                                                        @endif
                                                        <br>
                                                        <a href="{{ route('job-postings.public.show', $job) }}" class="btn btn-sm btn-outline-primary mt-1" aria-describedby="job-details-{{ $job->id }}">
                                                            View Details
                                                        </a>
                                                        <div id="job-details-{{ $job->id }}" class="sr-only">
                                                            View details for {{ $job->title }} at {{ $job->company ?? 'company' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-briefcase fa-3x text-muted mb-3" aria-hidden="true"></i>
                                <p class="text-muted mb-2">No available job postings at the moment.</p>
                                <a href="{{ route('job-postings.public') }}" class="btn btn-primary">Browse All Jobs</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Available Trainings -->
            <div class="col-xl-6 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h2 class="h5 mb-0" id="trainings-section-heading">
                            <i class="fas fa-graduation-cap me-2 text-success" aria-hidden="true"></i>
                            Available Training Programs
                        </h2>
                        <a href="{{ route('skill-trainings.public') }}" class="btn btn-sm btn-success">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="card-body">
                        @if(isset($skillTrainings) && $skillTrainings->count() > 0)
                            <div class="list-group list-group-flush" role="list" aria-labelledby="trainings-section-heading">
                                @foreach($skillTrainings as $training)
                                    <div class="list-group-item px-0 border-0 mb-2" role="listitem">
                                        <div class="card card-hover border">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div class="flex-grow-1">
                                                        <h3 class="h6 mb-1">{{ $training->title }}</h3>
                                                        <p class="mb-1 small text-muted">
                                                            <i class="fas fa-calendar me-1" aria-hidden="true"></i>
                                                            Starts: {{ $training->start_date ? $training->start_date->format('M j, Y') : 'TBA' }}
                                                        </p>
                                                        <p class="mb-1 small text-muted">
                                                            <i class="fas fa-clock me-1" aria-hidden="true"></i>
                                                            Duration: {{ $training->duration_days ?? 'N/A' }} days
                                                        </p>
                                                        <p class="mb-2 small text-muted">
                                                            <i class="fas fa-users me-1" aria-hidden="true"></i>
                                                            Slots: {{ $training->available_slots ?? 'N/A' }}
                                                        </p>
                                                    </div>
                                                    <div class="text-end ms-3">
                                                        @if(in_array($training->id, $userTrainingEnrollments))
                                                            <span class="badge bg-success mb-2" aria-label="Already enrolled">Enrolled</span>
                                                        @else
                                                            <span class="badge bg-primary mb-2" aria-label="Available for enrollment">Available</span>
                                                        @endif
                                                        <br>
                                                        <a href="{{ route('skill-trainings.public.show', $training) }}" class="btn btn-sm btn-outline-success mt-1" aria-describedby="training-details-{{ $training->id }}">
                                                            View Details
                                                        </a>
                                                        <div id="training-details-{{ $training->id }}" class="sr-only">
                                                            View details for {{ $training->title }} training
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-graduation-cap fa-3x text-muted mb-3" aria-hidden="true"></i>
                                <p class="text-muted mb-2">No available training programs at the moment.</p>
                                <a href="{{ route('skill-trainings.public') }}" class="btn btn-success">Browse All Trainings</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss success alerts after 8 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-success').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 8000);

    // Screen reader announcement for new opportunities
    @if(isset($jobPostings) && isset($skillTrainings) && ($jobPostings->count() > 0 || $skillTrainings->count() > 0))
    setTimeout(() => {
        const message = "New job and training opportunities are available. Check the Available Opportunities section.";
        const liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'sr-only';
        liveRegion.textContent = message;
        document.body.appendChild(liveRegion);

        setTimeout(() => {
            document.body.removeChild(liveRegion);
        }, 3000);
    }, 1000);
    @endif

    // Fix skip link functionality
    const skipLink = document.querySelector('.sr-only-focusable');
    if (skipLink) {
        skipLink.addEventListener('focus', function() {
            this.style.transform = 'translateY(0)';
        });

        skipLink.addEventListener('blur', function() {
            this.style.transform = 'translateY(-100%)';
        });
    }
});
</script>

{{-- Include Accessibility Widget --}}
@include('partials.accessibility-widget')
@endsection
