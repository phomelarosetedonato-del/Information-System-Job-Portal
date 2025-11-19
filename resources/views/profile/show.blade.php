@extends('layouts.app')

@section('title', 'My Profile - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-3 py-md-4">
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    <h1 class="h4 h3-md mb-2 text-dark">
                        <i class="fas fa-user me-2 text-primary"></i>
                        My Profile
                    </h1>
                    <p class="mb-0 text-muted small">View and manage your personal information</p>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100 w-md-auto">
                        <i class="fas fa-edit me-2"></i> Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Session Messages -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('info'))
                    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Profile Photo & Basic Info Card -->
                <div class="card shadow-sm border-0 mb-3 mb-md-4">
                    <div class="card-body p-3 p-md-4">
                        <div class="row align-items-center g-3">
                            <div class="col-12 col-md-3 text-center">
                                @if($pwdProfile && $pwdProfile->has_profile_photo)
                                    <img src="{{ $pwdProfile->profile_photo_url }}"
                                         alt="Profile Photo"
                                         class="rounded-circle border shadow-sm mb-3 profile-photo"
                                         style="width: 120px; height: 120px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center border shadow-sm mb-3 profile-photo"
                                         style="width: 120px; height: 120px;">
                                        <i class="fas fa-user fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-12 col-md-9 text-center text-md-start">
                                <h2 class="h4 h3-md text-dark mb-2">{{ $user->name }}</h2>
                                <p class="text-muted mb-2 small text-break">
                                    <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                                </p>
                                @if($user->phone)
                                    <p class="text-muted mb-2 small">
                                        <i class="fas fa-phone me-2"></i>{{ $user->phone }}
                                    </p>
                                @endif
                                @if($user->address)
                                    <p class="text-muted mb-2 small">
                                        <i class="fas fa-map-marker-alt me-2"></i>{{ $user->address }}
                                    </p>
                                @endif
                                <span class="badge bg-{{ $user->role_badge_class }} fs-6 mt-2">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PWD Profile Information -->
                @if($user->isPwd())
                    @if($pwdProfile && $pwdProfile->profile_completed)
                        <div class="card shadow-sm border-0 mb-3 mb-md-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="mb-0 text-dark h6 h5-md">
                                    <i class="fas fa-universal-access me-2 text-primary"></i>
                                    PWD Information
                                </h5>
                            </div>
                            <div class="card-body p-3 p-md-4">
                                <div class="row g-3">
                                    <div class="col-12 col-md-6 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small">Disability Type</p>
                                        <p class="mb-0 fw-semibold">
                                            {{ $pwdProfile->disabilityType->type ?? $pwdProfile->disability_type ?? 'Not specified' }}
                                        </p>
                                    </div>
                                    <div class="col-12 col-md-6 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small">Disability Level</p>
                                        <p class="mb-0 fw-semibold">{{ $pwdProfile->disability_severity ?? 'Not specified' }}</p>
                                    </div>
                                    @if($pwdProfile->assistive_devices)
                                        <div class="col-12 col-md-6 mb-2 mb-md-3">
                                            <p class="mb-1 text-muted small">Assistive Devices</p>
                                            <p class="mb-0">
                                                @php
                                                    $devices = is_array($pwdProfile->assistive_devices)
                                                        ? ($pwdProfile->assistive_devices['device'] ?? '')
                                                        : $pwdProfile->assistive_devices;
                                                @endphp
                                                {{ $devices ?: 'None' }}
                                            </p>
                                        </div>
                                    @endif
                                    @if($pwdProfile->special_needs)
                                        <div class="col-12 col-md-6 mb-2 mb-md-3">
                                            <p class="mb-1 text-muted small">Medical Conditions</p>
                                            <p class="mb-0">{{ $pwdProfile->special_needs }}</p>
                                        </div>
                                    @endif
                                    @if($pwdProfile->pwd_id_number)
                                        <div class="col-12 col-md-6 mb-2 mb-md-3">
                                            <p class="mb-1 text-muted small">PWD ID Number</p>
                                            <p class="mb-0 fw-semibold">{{ $pwdProfile->pwd_id_number }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Skills & Qualifications -->
                        @if($pwdProfile->skills || $pwdProfile->qualifications)
                            <div class="card shadow-sm border-0 mb-3 mb-md-4">
                                <div class="card-header bg-white border-bottom py-3">
                                    <h5 class="mb-0 text-dark h6 h5-md">
                                        <i class="fas fa-star me-2 text-primary"></i>
                                        Skills & Interests
                                    </h5>
                                </div>
                                <div class="card-body p-3 p-md-4">
                                    <div class="row g-3">
                                        @if($pwdProfile->skills)
                                            <div class="col-12 col-md-6 mb-2 mb-md-3">
                                                <p class="mb-1 text-muted small">Skills & Talents</p>
                                                <p class="mb-0">{{ $pwdProfile->skills }}</p>
                                            </div>
                                        @endif
                                        @if($pwdProfile->qualifications)
                                            <div class="col-12 col-md-6 mb-2 mb-md-3">
                                                <p class="mb-1 text-muted small">Interests & Hobbies</p>
                                                <p class="mb-0">{{ $pwdProfile->qualifications }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Emergency Contact -->
                        <div class="card shadow-sm border-0 mb-3 mb-md-4">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="mb-0 text-dark h6 h5-md">
                                    <i class="fas fa-phone-square me-2 text-primary"></i>
                                    Emergency Contact
                                </h5>
                            </div>
                            <div class="card-body p-3 p-md-4">
                                <div class="row g-3">
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small">Name</p>
                                        <p class="mb-0 fw-semibold">{{ $pwdProfile->emergency_contact_name ?? 'Not specified' }}</p>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small">Phone</p>
                                        <p class="mb-0">{{ $pwdProfile->emergency_contact_phone ?? 'Not specified' }}</p>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small">Relationship</p>
                                        <p class="mb-0">{{ $pwdProfile->emergency_contact_relationship ?? 'Not specified' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning mb-3 mb-md-4">
                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                                <div class="flex-grow-1">
                                    <h5 class="alert-heading mb-1 h6">Complete Your PWD Profile</h5>
                                    <p class="mb-2 small">Please complete your PWD profile to access all features and opportunities.</p>
                                    <a href="{{ route('profile.pwd-complete-form') }}" class="btn btn-warning btn-sm w-100 w-sm-auto">
                                        <i class="fas fa-clipboard-check me-2"></i>Complete Profile Now
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Resume Section -->
                @if($user->canUploadResume())
                    <div class="card shadow-sm border-0 mb-3 mb-md-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark h6 h5-md">
                                <i class="fas fa-file me-2 text-primary"></i>
                                Resume
                            </h5>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            @if($user->hasResume())
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                    <div class="flex-grow-1">
                                        <p class="mb-1 small"><strong>Current Resume:</strong></p>
                                        <p class="mb-1 text-break">{{ $user->resume_file_name }}</p>
                                        <p class="mb-0 text-muted"><small>Size: {{ $user->resume_file_size }}</small></p>
                                    </div>
                                    <div class="d-flex gap-2 w-100 w-md-auto">
                                        <a href="{{ route('profile.downloadResume') }}" class="btn btn-sm btn-primary flex-fill flex-md-grow-0">
                                            <i class="fas fa-download me-1"></i> Download
                                        </a>
                                        <form action="{{ route('profile.deleteResume') }}" method="POST" class="flex-fill flex-md-grow-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger w-100"
                                                    onclick="return confirm('Are you sure you want to delete your resume?')">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <p class="text-muted mb-0 small">No resume uploaded. <a href="{{ route('profile.edit') }}">Upload one now</a></p>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Account Actions -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h5 class="mb-0 text-dark h6 h5-md">
                            <i class="fas fa-cog me-2 text-primary"></i>
                            Account Actions
                        </h5>
                    </div>
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <div>
                                <p class="mb-0 small">Need to update your information?</p>
                            </div>
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100 w-md-auto">
                                <i class="fas fa-edit me-2"></i> Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 12px;
        transition: transform 0.2s;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3) !important;
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    /* Disable hover transform on mobile for better touch experience */
    @media (min-width: 768px) {
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.4) !important;
        }
    }

    .profile-photo {
        transition: transform 0.3s ease;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
    }

    @media (min-width: 768px) {
        .profile-photo:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
        }
    }

    /* Larger profile photo on desktop */
    @media (min-width: 768px) {
        .profile-photo {
            width: 150px !important;
            height: 150px !important;
        }
        .profile-photo i {
            font-size: 4rem !important;
        }
    }

    /* Improve button tap targets on mobile */
    @media (max-width: 767px) {
        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
    }

    /* Better text wrapping on mobile */
    .text-break {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Responsive heading sizes */
    .h3-md {
        font-size: 1.5rem;
    }
    .h5-md {
        font-size: 1.125rem;
    }
    @media (min-width: 768px) {
        .h3-md {
            font-size: 1.75rem;
        }
        .h5-md {
            font-size: 1.25rem;
        }
    }

    /* Ensure proper spacing on mobile */
    @media (max-width: 767px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }

    /* Touch-friendly alert on mobile */
    @media (max-width: 575px) {
        .alert {
            padding: 1rem;
        }
    }
</style>
@endsection
