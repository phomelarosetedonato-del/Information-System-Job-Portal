@extends('layouts.app')

@section('title', 'My Profile - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-3 py-md-4">
        <div class="container">
            <div class="row align-items-center g-2 g-md-3">
                <div class="col-12 col-md-8">
                    <h1 class="h5 h3-md mb-1 mb-md-2 text-dark">
                        <i class="fas fa-user me-2 text-primary"></i>
                        My Profile
                    </h1>
                    <p class="mb-0 text-muted" style="font-size: 0.85rem;">View and manage your personal information</p>
                </div>
                <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                    <a href="{{ route('profile.edit') }}" class="btn btn-primary w-100 w-md-auto btn-mobile-lg">
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

                <!-- Profile Completion Progress (for PWD users) -->
                @if($user->isPwd())
                    @php
                        $completionPercentage = $user->getProfileCompletionPercentage();
                        $progressClass = $completionPercentage >= 80 ? 'bg-success' : ($completionPercentage >= 50 ? 'bg-warning' : 'bg-danger');
                        $alertClass = $completionPercentage >= 80 ? 'alert-success' : 'alert-info';
                    @endphp
                    <div class="card shadow-sm border-0 mb-3 mb-md-4">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
                                <div>
                                    <h6 class="mb-1">
                                        <i class="fas fa-chart-line me-2 text-primary"></i>
                                        Profile Completion
                                    </h6>
                                    <p class="mb-0 small text-muted">
                                        @if($completionPercentage >= 80)
                                            Your profile is complete! You can now apply for jobs.
                                        @else
                                            Complete your profile to at least 80% to apply for jobs.
                                        @endif
                                    </p>
                                </div>
                                <h3 class="mb-0 {{ $completionPercentage >= 80 ? 'text-success' : 'text-warning' }}">
                                    {{ $completionPercentage }}%
                                </h3>
                            </div>
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                     style="width: {{ $completionPercentage }}%;"
                                     aria-valuenow="{{ $completionPercentage }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    <span class="fw-semibold">{{ $completionPercentage }}%</span>
                                </div>
                            </div>
                        </div>
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
                                <h2 class="h5 h4-md text-dark mb-2 mb-md-3">{{ $user->name }}</h2>
                                <p class="text-muted mb-2 text-break" style="font-size: 0.85rem;">
                                    <i class="fas fa-envelope me-1 me-md-2"></i><span class="d-none d-sm-inline">Email: </span>{{ $user->email }}
                                </p>
                                @if($user->phone)
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-phone me-1 me-md-2"></i><span class="d-none d-sm-inline">Phone: </span>{{ $user->phone }}
                                    </p>
                                @endif
                                @if($user->address)
                                    <p class="text-muted mb-2" style="font-size: 0.85rem;">
                                        <i class="fas fa-map-marker-alt me-1 me-md-2"></i><span class="d-none d-sm-inline">Address: </span>{{ $user->address }}
                                    </p>
                                @endif
                                <span class="badge bg-{{ $user->role_badge_class }} mt-2" style="font-size: 0.85rem; padding: 0.4rem 0.75rem;">
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
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small"><i class="fas fa-wheelchair me-2"></i>Disability Type</p>
                                        <p class="mb-0 fw-semibold">
                                            {{ $pwdProfile->disabilityType->type ?? $pwdProfile->disability_type ?? 'Not specified' }}
                                        </p>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small"><i class="fas fa-signal me-2"></i>Disability Level</p>
                                        <p class="mb-0 fw-semibold">{{ $pwdProfile->disability_severity ?? 'Not specified' }}</p>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small"><i class="fas fa-venus-mars me-2"></i>Gender</p>
                                        <p class="mb-0">{{ $pwdProfile->gender ? ucfirst($pwdProfile->gender) : 'Not specified' }}</p>
                                    </div>
                                    @if($pwdProfile->birthdate)
                                        <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                            <p class="mb-1 text-muted small"><i class="fas fa-birthday-cake me-2"></i>Birthdate</p>
                                            <p class="mb-0">{{ $pwdProfile->birthdate->format('F d, Y') }}</p>
                                            <p class="mb-0 text-muted" style="font-size: 0.85rem;">Age: {{ $pwdProfile->birthdate->age }} years old</p>
                                        </div>
                                    @endif
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small"><i class="fas fa-briefcase me-2"></i>Employment Status</p>
                                        <p class="mb-0">
                                            @if($pwdProfile->is_employed)
                                                <span class="badge bg-success">Employed</span>
                                            @else
                                                <span class="badge bg-secondary">Not Employed</span>
                                            @endif
                                        </p>
                                    </div>
                                    @php
                                        $devices = '';
                                        if($pwdProfile->assistive_devices) {
                                            $devices = is_array($pwdProfile->assistive_devices)
                                                ? ($pwdProfile->assistive_devices['device'] ?? '')
                                                : $pwdProfile->assistive_devices;
                                        }
                                        $showDevices = !empty($devices) && strtolower(trim($devices)) !== 'none';
                                    @endphp
                                    @if($showDevices)
                                        <div class="col-12 col-md-6 mb-2 mb-md-3">
                                            <p class="mb-1 text-muted small"><i class="fas fa-medkit me-2"></i>Assistive Devices</p>
                                            <p class="mb-0">{{ $devices }}</p>
                                        </div>
                                    @endif
                                    @if($pwdProfile->special_needs)
                                        <div class="col-12 col-md-6 mb-2 mb-md-3">
                                            <p class="mb-1 text-muted small"><i class="fas fa-notes-medical me-2"></i>Medical Conditions</p>
                                            <p class="mb-0">{{ $pwdProfile->special_needs }}</p>
                                        </div>
                                    @endif
                                    @php
                                        $accessibilityNeeds = '';
                                        if($pwdProfile->accessibility_needs) {
                                            $accessibilityNeeds = is_array($pwdProfile->accessibility_needs)
                                                ? ($pwdProfile->accessibility_needs['notes'] ?? '')
                                                : $pwdProfile->accessibility_needs;
                                        }
                                        $showAccessibility = !empty($accessibilityNeeds) && strtolower(trim($accessibilityNeeds)) !== 'none';
                                    @endphp
                                    @if($showAccessibility)
                                        <div class="col-12 mb-2 mb-md-3">
                                            <p class="mb-1 text-muted small"><i class="fas fa-universal-access me-2"></i>Accessibility Needs</p>
                                            <p class="mb-0">{{ $accessibilityNeeds }}</p>
                                        </div>
                                    @endif
                                    @if($pwdProfile->pwd_id_number)
                                        <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                            <p class="mb-1 text-muted small"><i class="fas fa-id-card me-2"></i>PWD ID Number</p>
                                            <p class="mb-0 fw-semibold">{{ $pwdProfile->pwd_id_number }}</p>
                                        </div>
                                    @endif
                                    @if($pwdProfile->pwd_id_photo)
                                        <div class="col-12 mb-2 mb-md-3">
                                            <p class="mb-2 text-muted small"><i class="fas fa-image me-2"></i>PWD ID Photo</p>
                                            <a href="{{ asset('storage/' . $pwdProfile->pwd_id_photo) }}" target="_blank" class="d-inline-block">
                                                <img src="{{ asset('storage/' . $pwdProfile->pwd_id_photo) }}"
                                                     alt="PWD ID Photo"
                                                     class="img-thumbnail pwd-id-photo"
                                                     style="max-width: 300px; max-height: 200px; object-fit: cover; cursor: pointer;">
                                            </a>
                                            <p class="mb-0 text-muted mt-1" style="font-size: 0.85rem;">
                                                <i class="fas fa-info-circle me-1"></i>Click image to view full size
                                            </p>
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
                                                <p class="mb-1 text-muted small"><i class="fas fa-tools me-2"></i>Skills & Talents</p>
                                                <p class="mb-0">{{ $pwdProfile->skills }}</p>
                                            </div>
                                        @endif
                                        @if($pwdProfile->qualifications)
                                            <div class="col-12 col-md-6 mb-2 mb-md-3">
                                                <p class="mb-1 text-muted small"><i class="fas fa-heart me-2"></i>Interests & Hobbies</p>
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
                                        <p class="mb-1 text-muted small"><i class="fas fa-user me-2"></i>Name</p>
                                        <p class="mb-0 fw-semibold">{{ $pwdProfile->emergency_contact_name ?? 'Not specified' }}</p>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small"><i class="fas fa-phone me-2"></i>Phone</p>
                                        <p class="mb-0">{{ $pwdProfile->emergency_contact_phone ?? 'Not specified' }}</p>
                                    </div>
                                    <div class="col-12 col-sm-6 col-md-4 mb-2 mb-md-3">
                                        <p class="mb-1 text-muted small"><i class="fas fa-users me-2"></i>Relationship</p>
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
                            @php
                                $hasPdfResume = !empty($user->resume) && Storage::disk('public')->exists($user->resume);
                                $hasDbResume = $user->resumes()->count() > 0;
                            @endphp

                            @if($hasPdfResume || $hasDbResume)
                                <!-- PDF Resume Section -->
                                @if($hasPdfResume)
                                <div class="alert alert-success py-2 mb-3" style="font-size: 0.9rem;">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                        <div class="flex-grow-1">
                                            <p class="mb-1"><i class="fas fa-file-pdf me-2"></i><strong>PDF Resume:</strong></p>
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
                                                        onclick="return confirm('Are you sure you want to delete your PDF resume?')">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <!-- Database Resume Section -->
                                @if($hasDbResume)
                                @php $dbResume = $user->resumes()->latest()->first(); @endphp
                                <div class="alert alert-info py-2 mb-0" style="font-size: 0.9rem;">
                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                        <div class="flex-grow-1">
                                            <p class="mb-1"><i class="fas fa-file-alt me-2"></i><strong>Online Resume:</strong></p>
                                            <p class="mb-1">{{ $dbResume->full_name }}</p>
                                            <p class="mb-0 text-muted">
                                                <small>
                                                    Completion: {{ $dbResume->completion_percentage }}% |
                                                    Status: <span class="badge bg-{{ $dbResume->is_published ? 'success' : 'warning' }}">
                                                        {{ $dbResume->is_published ? 'Published' : 'Draft' }}
                                                    </span>
                                                </small>
                                            </p>
                                        </div>
                                        <div class="d-flex gap-2 w-100 w-md-auto">
                                            <a href="{{ route('resumes.show', $dbResume) }}" class="btn btn-sm btn-info flex-fill flex-md-grow-0">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                            <a href="{{ route('resumes.edit', $dbResume) }}" class="btn btn-sm btn-primary flex-fill flex-md-grow-0">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-3">No resume uploaded yet.</p>
                                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#quickUploadModal">
                                            <i class="fas fa-upload me-1"></i> Upload PDF Resume
                                        </button>
                                        <a href="{{ route('resumes.create') }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit me-1"></i> Build Resume Online
                                        </a>
                                    </div>
                                </div>
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

<!-- Quick Resume Upload Modal -->
<div class="modal fade" id="quickUploadModal" tabindex="-1" aria-labelledby="quickUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quickUploadModalLabel">
                    <i class="fas fa-upload me-2"></i>Upload PDF Resume
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('profile.uploadResume') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="resume" class="form-label">Select Resume File</label>
                        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx,.txt" required>
                        <div class="form-text">Accepted formats: PDF, DOC, DOCX, TXT (Max: 5MB)</div>
                    </div>
                    <div class="alert alert-info mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>Your resume will be available to potential employers when you apply for jobs.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-2"></i>Upload Resume
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 8px;
        transition: transform 0.2s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid rgba(0, 0, 0, 0.125);
    }

    /* Desktop card styling */
    @media (min-width: 768px) {
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.15) !important;
        }
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.2) !important;
        }
    }

    .profile-photo {
        transition: transform 0.3s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    @media (min-width: 768px) {
        .profile-photo:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .profile-photo {
            width: 150px !important;
            height: 150px !important;
        }
        .profile-photo i {
            font-size: 3.5rem !important;
        }
    }

    /* Mobile photo sizing */
    @media (max-width: 767px) {
        .profile-photo {
            width: 100px !important;
            height: 100px !important;
        }
        .profile-photo i {
            font-size: 2rem !important;
        }
    }

    /* PWD ID Photo styling */
    .pwd-id-photo {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 2px solid #dee2e6;
        border-radius: 8px;
    }

    @media (min-width: 768px) {
        .pwd-id-photo:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
    }

    /* Responsive PWD ID photo sizing */
    @media (max-width: 575px) {
        .pwd-id-photo {
            max-width: 100% !important;
            height: auto !important;
        }
    }

    /* Mobile-friendly buttons */
    .btn-mobile-lg {
        min-height: 44px;
        padding: 0.6rem 1rem;
    }

    .btn-mobile-touch {
        min-height: 40px;
        padding: 0.5rem 0.9rem;
        font-size: 0.85rem;
    }

    @media (max-width: 767px) {
        .btn {
            padding: 0.55rem 1rem;
            font-size: 0.9rem;
            min-height: 44px;
        }
        .btn-sm {
            padding: 0.5rem 0.85rem;
            font-size: 0.85rem;
            min-height: 42px;
        }
        .btn-mobile-lg {
            width: 100%;
            padding: 0.7rem 1rem;
            font-size: 0.95rem;
        }
        body {
            font-size: 0.95rem;
        }
        p {
            margin-bottom: 0.75rem;
        }
    }

    @media (min-width: 768px) {
        .btn-mobile-lg, .btn-mobile-touch {
            min-height: auto;
            padding: 0.375rem 0.75rem;
            font-size: inherit;
        }
    }

    /* Better text wrapping on mobile */
    .text-break {
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Responsive heading sizes */
    .h3-md {
        font-size: 1.35rem;
    }
    .h4-md {
        font-size: 1.15rem;
    }
    .h5-md {
        font-size: 1rem;
    }
    .h6 {
        font-size: 0.95rem;
    }

    .fa-3x-md {
        font-size: 2.5em;
    }

    @media (min-width: 768px) {
        .h3-md {
            font-size: 1.75rem;
        }
        .h4-md {
            font-size: 1.5rem;
        }
        .h5-md {
            font-size: 1.25rem;
        }
        .h6 {
            font-size: 1rem;
        }
        .fa-3x-md {
            font-size: 3em;
        }
    }

    /* Mobile spacing optimization */
    @media (max-width: 767px) {
        .container {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .dashboard-header {
            padding-top: 0.75rem !important;
            padding-bottom: 0.75rem !important;
        }

        .card-body.p-3.p-md-4 {
            padding: 0.9rem !important;
        }

        .card-header {
            padding: 0.65rem 0.9rem !important;
        }

        .card {
            margin-bottom: 0.75rem !important;
        }
    }

    @media (max-width: 375px) {
        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .card-body.p-3.p-md-4 {
            padding: 0.75rem !important;
        }

        .card-header {
            padding: 0.5rem 0.75rem !important;
        }
    }

    /* Touch-friendly alerts */
    @media (max-width: 575px) {
        .alert {
            padding: 0.75rem;
            font-size: 0.85rem;
            border-radius: 6px;
        }
    }

    /* Better text sizing on mobile */
    @media (max-width: 575px) {
        .small, small {
            font-size: 0.8rem;
        }
    }

    /* Badge responsive sizing */
    .badge {
        padding: 0.35em 0.65em;
        font-size: 0.875rem;
    }

    @media (max-width: 575px) {
        .badge {
            font-size: 0.8rem;
            padding: 0.3em 0.6em;
        }
    }

    /* Icons alignment with text */
    .fas, .far {
        display: inline-block;
        vertical-align: middle;
    }

    /* Ensure icons don't break on mobile */
    @media (max-width: 575px) {
        .fas, .far {
            font-size: 0.9rem;
        }
    }

    /* Better gap handling on mobile */
    @media (max-width: 575px) {
        .gap-3 {
            gap: 0.65rem !important;
        }
        .gap-2 {
            gap: 0.45rem !important;
        }
    }

    /* Form controls in alerts */
    @media (max-width: 575px) {
        .alert form {
            width: 100%;
        }

        .alert .btn {
            width: 100%;
        }
    }

    /* Improve d-flex layouts on mobile */
    @media (max-width: 575px) {
        .d-flex.flex-md-row {
            flex-direction: column !important;
        }
    }

    /* Modal mobile optimization */
    @media (max-width: 767px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-content {
            border-radius: 8px;
        }

        .modal-header, .modal-footer {
            padding: 0.75rem;
        }

        .modal-body {
            padding: 1rem;
        }

        .modal-title {
            font-size: 1.1rem;
        }
    }

    /* Progress bar mobile */
    @media (max-width: 767px) {
        .progress {
            height: 20px !important;
            font-size: 0.75rem;
        }
    }

    /* Better touch targets */
    @media (max-width: 767px) {
        a, button {
            -webkit-tap-highlight-color: rgba(0,0,0,0.1);
        }
    }
</style>
@endsection
