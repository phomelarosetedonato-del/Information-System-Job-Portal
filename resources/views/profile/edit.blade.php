@extends('layouts.app')

@section('title', 'Edit Profile - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-3 py-md-4">
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    <h1 class="h4 h3-md mb-2 text-dark">
                        <i class="fas fa-user-edit me-2 text-primary"></i>
                        Edit Profile
                    </h1>
                    <p class="mb-0 text-muted small">Update your personal information and PWD details</p>
                </div>
                <div class="col-12 col-md-4 text-md-end">
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary w-100 w-md-auto">
                        <i class="fas fa-arrow-left me-2"></i> Back to Profile
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
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Profile Completion Progress -->
                @if($user->isPwd())
                @php
                    $completionPercentage = $user->getProfileCompletionPercentage();
                    $progressColor = $completionPercentage >= 80 ? 'success' : ($completionPercentage >= 50 ? 'warning' : 'danger');
                @endphp
                <div class="alert alert-{{ $progressColor }} alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-3">
                        <div class="flex-grow-1">
                            <h6 class="alert-heading mb-2">
                                <i class="fas fa-chart-line me-2"></i>Profile Completion: {{ $completionPercentage }}%
                            </h6>
                            <div class="progress mb-2" style="height: 25px;">
                                <div class="progress-bar bg-{{ $progressColor }}" role="progressbar"
                                     style="width: {{ $completionPercentage }}%;"
                                     aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    <strong>{{ $completionPercentage }}%</strong>
                                </div>
                            </div>
                            <p class="mb-0 small">
                                @if($completionPercentage >= 80)
                                    <i class="fas fa-check-circle me-1"></i>Your profile is complete! You can apply for jobs.
                                @else
                                    <i class="fas fa-info-circle me-1"></i>Complete at least 80% to apply for jobs. Fill in more details below.
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileUpdateForm">
                    @csrf
                    @method('PUT')

                    <!-- Profile Photo Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-dark">
            <i class="fas fa-camera me-2 text-primary"></i>
            Profile Photo
        </h5>
    </div>
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-4 text-center">
                <!-- Current Profile Photo -->
                @php
                    // FIX: Better null checking
                    $hasProfilePhoto = $pwdProfile && $pwdProfile->has_profile_photo;
                    $profilePhotoUrl = $hasProfilePhoto ? ($pwdProfile->profile_photo_url . '?t=' . time()) : null;
                @endphp

                @if($hasProfilePhoto && $profilePhotoUrl)
                    <img src="{{ $profilePhotoUrl }}"
                         alt="Profile Photo of {{ $user->name }}"
                         class="rounded-circle border shadow-sm mb-3"
                         width="150"
                         height="150"
                         style="object-fit: cover; border: 4px solid #e9ecef;">
                @else
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center border shadow-sm mb-3"
                         style="width: 150px; height: 150px; border: 4px solid #e9ecef;">
                        <i class="fas fa-user text-muted fa-3x"></i>
                    </div>
                @endif

                <!-- Remove Photo Button -->
                @if($hasProfilePhoto && $pwdProfile)
                    <div class="mt-2">
                        <a href="{{ route('profile.deletePhoto') }}"
                           class="btn btn-outline-danger btn-sm"
                           onclick="return confirm('Are you sure you want to remove your profile photo?')">
                            <i class="fas fa-trash me-1"></i> Remove Photo
                        </a>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="profile_photo" class="form-label fw-semibold">Update Profile Photo</label>
                    <input type="file"
                           class="form-control @error('profile_photo') is-invalid @enderror"
                           id="profile_photo"
                           name="profile_photo"
                           accept="image/jpeg,image/png,image/jpg,image/gif">
                    @error('profile_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Upload JPG, PNG, or GIF. Maximum file size: 2MB.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resume Section -->
@if($user->canUploadResume())
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-dark">
            <i class="fas fa-file me-2 text-primary"></i>
            Resume
        </h5>
    </div>
    <div class="card-body">
        @php
            $hasPdfResume = !empty($user->resume) && Storage::disk('public')->exists($user->resume);
            $hasDbResume = $user->resumes()->count() > 0;
        @endphp

        @if($hasPdfResume || $hasDbResume)
            <!-- PDF Resume Section -->
            @if($hasPdfResume)
            <div class="alert alert-success mb-3">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-1"><i class="fas fa-file-pdf me-2"></i><strong>PDF Resume:</strong> {{ $user->resume_file_name }}</p>
                        <p class="mb-0 text-muted"><small>Size: {{ $user->resume_file_size }} • Type: {{ $user->resume_file_type }}</small></p>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <a href="{{ route('profile.downloadResume') }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-download me-1"></i> Download
                        </a>
                        <form action="{{ route('profile.deleteResume') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"
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
            <div class="alert alert-info mb-0">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p class="mb-1"><i class="fas fa-file-alt me-2"></i><strong>Online Resume:</strong> {{ $dbResume->full_name }}</p>
                        <p class="mb-0">
                            <small>
                                Completion: {{ $dbResume->completion_percentage }}% |
                                Status: <span class="badge bg-{{ $dbResume->is_published ? 'success' : 'warning' }}">
                                    {{ $dbResume->is_published ? 'Published' : 'Draft' }}
                                </span>
                            </small>
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                        <a href="{{ route('resumes.show', $dbResume) }}" class="btn btn-info btn-sm me-2">
                            <i class="fas fa-eye me-1"></i> View
                        </a>
                        <a href="{{ route('resumes.edit', $dbResume) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
            @endif
        @else
            <!-- No Resume - Show Two Options -->
            <div class="text-center py-4">
                <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-4">Choose how to add your resume:</p>

                <div class="row justify-content-center">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <div class="card border">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-upload me-2"></i>Upload PDF</h6>
                                <p class="card-text small text-muted">Upload an existing resume file</p>
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#quickUploadModal">
                                    Upload PDF Resume
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="card border">
                            <div class="card-body">
                                <h6 class="card-title"><i class="fas fa-edit me-2"></i>Build Online</h6>
                                <p class="card-text small text-muted">Create a resume step-by-step</p>
                                <a href="{{ route('resumes.create') }}" class="btn btn-primary btn-sm">
                                    Build Resume
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endif





                    <!-- Basic Information Section -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>
                                Basic Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                                           placeholder="e.g., 09123456789">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="address" class="form-label">Complete Address</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                           id="address" name="address" value="{{ old('address', $user->address) }}"
                                           placeholder="e.g., Street, Barangay, City">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PWD Information Section -->
                    @if($user->isPwd())
                    <div class="card shadow-sm border-0 mb-3 mb-md-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark h6 h5-md">
                                <i class="fas fa-universal-access me-2 text-primary"></i>
                                PWD Information
                            </h5>
                        </div>
                        <div class="card-body p-3 p-md-4">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label for="disability_type_id" class="form-label">Disability Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('disability_type_id') is-invalid @enderror"
                                            id="disability_type_id" name="disability_type_id" required>
                                        <option value="">Select Disability Type</option>
                                        @foreach($disabilityTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ old('disability_type_id', $pwdProfile->disability_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                                {{ $type->type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('disability_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Select Gender</option>
                                        @php
                                            $currentGender = old('gender', $pwdProfile->gender ?? '');
                                        @endphp
                                        <option value="male" {{ $currentGender == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ $currentGender == 'female' ? 'selected' : '' }}>Female</option>
                                        <option value="other" {{ $currentGender == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="birthdate" class="form-label">Birthdate</label>
                                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                           id="birthdate" name="birthdate"
                                           value="{{ old('birthdate', $pwdProfile && $pwdProfile->birthdate ? $pwdProfile->birthdate->format('Y-m-d') : '') }}"
                                           max="{{ date('Y-m-d') }}">
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Employment Status</label>
                                    <div class="form-check mt-2">
                                        @php
                                            $isEmployed = old('is_employed', $pwdProfile && $pwdProfile->is_employed ? true : false);
                                        @endphp
                                        <input class="form-check-input" type="checkbox" id="is_employed" name="is_employed"
                                               value="1" {{ $isEmployed ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="is_employed">
                                            Currently Employed
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="skills" class="form-label">Skills & Abilities</label>
                                    <select class="form-select @error('skills') is-invalid @enderror"
                                            id="skills" name="skills">
                                        <option value="">-- Select Skill --</option>
                                        @foreach($skillOptions as $option)
                                            <option value="{{ $option->name }}"
                                                {{ old('skills', $pwdProfile->skills ?? '') == $option->name ? 'selected' : '' }}>
                                                {{ $option->name }}
                                            </option>
                                        @endforeach
                                        <option value="Others"
                                            {{ old('skills', $pwdProfile->skills ?? '') != '' && !$skillOptions->pluck('name')->contains(old('skills', $pwdProfile->skills ?? '')) && old('skills', $pwdProfile->skills ?? '') != '' ? 'selected' : '' }}>
                                            Others (Please Specify)
                                        </option>
                                    </select>
                                    @error('skills')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">Select your primary skill or choose "Others" to specify.</div>
                                </div>

                                <!-- Skills Others Input -->
                                <div class="col-12 col-md-6" id="skills_other_container" style="display: none;">
                                    <label for="skills_other" class="form-label">Please Specify Your Skill</label>
                                    <input type="text" class="form-control" id="skills_other" name="skills_other"
                                           placeholder="Enter your specific skill..."
                                           value="{{ old('skills_other', (!$skillOptions->pluck('name')->contains($pwdProfile->skills ?? '') && $pwdProfile->skills) ? $pwdProfile->skills : '') }}">
                                    <div class="form-text text-muted">Describe your specific skill or ability.</div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="qualifications" class="form-label">Educational Background & Qualifications</label>
                                    <select class="form-select @error('qualifications') is-invalid @enderror"
                                            id="qualifications" name="qualifications">
                                        <option value="">-- Select Educational Background --</option>
                                        @foreach($qualificationOptions as $option)
                                            <option value="{{ $option->name }}"
                                                {{ old('qualifications', $pwdProfile->qualifications ?? '') == $option->name ? 'selected' : '' }}>
                                                {{ $option->name }}
                                            </option>
                                        @endforeach
                                        <option value="Others"
                                            {{ old('qualifications', $pwdProfile->qualifications ?? '') != '' && !$qualificationOptions->pluck('name')->contains(old('qualifications', $pwdProfile->qualifications ?? '')) && old('qualifications', $pwdProfile->qualifications ?? '') != '' ? 'selected' : '' }}>
                                            Others (Please Specify)
                                        </option>
                                    </select>
                                    @error('qualifications')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">Select your highest educational attainment.</div>
                                </div>

                                <!-- Qualifications Others Input -->
                                <div class="col-12 col-md-6" id="qualifications_other_container" style="display: none;">
                                    <label for="qualifications_other" class="form-label">Please Specify Your Educational Background</label>
                                    <input type="text" class="form-control" id="qualifications_other" name="qualifications_other"
                                           placeholder="Enter your educational background..."
                                           value="{{ old('qualifications_other', (!$qualificationOptions->pluck('name')->contains($pwdProfile->qualifications ?? '') && $pwdProfile->qualifications) ? $pwdProfile->qualifications : '') }}">
                                    <div class="form-text text-muted">Describe your educational background or qualifications.</div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label for="special_needs" class="form-label">Special Needs & Accommodations</label>
                                    <select class="form-select @error('special_needs') is-invalid @enderror"
                                            id="special_needs" name="special_needs">
                                        <option value="">-- Select Accommodation Need --</option>
                                        @foreach($accommodationOptions as $option)
                                            <option value="{{ $option->name }}"
                                                {{ old('special_needs', $pwdProfile->special_needs ?? '') == $option->name ? 'selected' : '' }}>
                                                {{ $option->name }}
                                            </option>
                                        @endforeach
                                        <option value="Others"
                                            {{ old('special_needs', $pwdProfile->special_needs ?? '') != '' && !$accommodationOptions->pluck('name')->contains(old('special_needs', $pwdProfile->special_needs ?? '')) && old('special_needs', $pwdProfile->special_needs ?? '') != '' ? 'selected' : '' }}>
                                            Others (Please Specify)
                                        </option>
                                    </select>
                                    @error('special_needs')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">This helps employers provide appropriate workplace accommodations.</div>
                                </div>

                                <!-- Special Needs Others Input -->
                                <div class="col-12 col-md-6" id="special_needs_other_container" style="display: none;">
                                    <label for="special_needs_other" class="form-label">Please Specify Your Accommodation Needs</label>
                                    <input type="text" class="form-control" id="special_needs_other" name="special_needs_other"
                                           placeholder="Enter your specific accommodation needs..."
                                           value="{{ old('special_needs_other', (!$accommodationOptions->pluck('name')->contains($pwdProfile->special_needs ?? '') && $pwdProfile->special_needs) ? $pwdProfile->special_needs : '') }}">
                                    <div class="form-text text-muted">Describe the specific accommodations or support you need.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="card shadow-sm border-0 sticky-bottom-mobile">
                        <div class="card-body p-3 p-md-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-stretch align-items-md-center gap-2 gap-md-3">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary order-3 order-md-1">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    <span class="d-none d-sm-inline">Back to Profile</span>
                                    <span class="d-inline d-sm-none">Back</span>
                                </a>
                                <div class="d-flex flex-column flex-sm-row gap-2 order-1 order-md-2">
                                    <button type="reset" class="btn btn-outline-danger">
                                        <i class="fas fa-undo me-2"></i>
                                        <span class="d-none d-sm-inline">Reset Changes</span>
                                        <span class="d-inline d-sm-none">Reset</span>
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-2"></i>
                                        <span class="d-none d-sm-inline">Update Profile</span>
                                        <span class="d-inline d-sm-none">Update</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }

    .card {
        border-radius: 12px;
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }

    .rounded-circle {
        transition: transform 0.3s ease;
    }

    @media (min-width: 768px) {
        .rounded-circle:hover {
            transform: scale(1.05);
        }
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

    /* Sticky bottom form actions on mobile */
    @media (max-width: 767px) {
        .sticky-bottom-mobile {
            position: sticky;
            bottom: 0;
            z-index: 1020;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 0 !important;
        }

        .sticky-bottom-mobile .card-body {
            padding: 0.75rem !important;
        }
    }

    /* Improve button tap targets on mobile */
    @media (max-width: 767px) {
        .btn {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            min-height: 44px; /* iOS touch target minimum */
        }
        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
        }
        .form-select, .form-control {
            font-size: 0.95rem;
            padding: 0.6rem 0.75rem;
            min-height: 44px;
        }
        .form-control:focus, .form-select:focus {
            font-size: 16px; /* Prevent zoom on iOS */
        }
    }

    /* Better mobile spacing */
    @media (max-width: 576px) {
        .card {
            margin-bottom: 1rem !important;
        }
        .card-body {
            padding: 1rem !important;
        }
        .card-header {
            padding: 0.75rem 1rem !important;
        }
    }

    /* Ensure proper spacing on mobile */
    @media (max-width: 767px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
    }

    /* Better dropdown appearance */
    .form-select {
        cursor: pointer;
        background-position: right 0.75rem center;
    }

    .form-select:focus {
        background-color: #fff;
    }

    /* Smooth transitions for showing/hiding fields */
    #skills_other_container,
    #qualifications_other_container,
    #special_needs_other_container {
        transition: opacity 0.3s ease-in-out;
    }

    /* Form text smaller on mobile */
    @media (max-width: 767px) {
        .form-text {
            font-size: 0.8rem;
        }
    }

    /* Better spacing between form groups */
    @media (min-width: 768px) {
        .row.g-3 > * {
            margin-bottom: 0.5rem;
        }
    }

    /* CRITICAL: Ensure submit button is always clickable */
    #submitBtn {
        pointer-events: auto !important;
        cursor: pointer !important;
        opacity: 1 !important;
        position: relative;
        z-index: 10;
    }

    #submitBtn:not(:disabled):hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
    }

    #submitBtn:not(:disabled):active {
        transform: translateY(0);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Profile Edit Page');

    // Handle Skills dropdown "Others" option
    const skillsSelect = document.getElementById('skills');
    const skillsOtherContainer = document.getElementById('skills_other_container');
    const skillsOtherInput = document.getElementById('skills_other');

    function toggleSkillsOther() {
        if (skillsSelect && skillsSelect.value === 'Others') {
            skillsOtherContainer.style.opacity = '0';
            skillsOtherContainer.style.display = 'block';
            setTimeout(() => {
                skillsOtherContainer.style.opacity = '1';
            }, 10);
            skillsOtherInput.required = true;
            skillsOtherInput.focus();
        } else {
            skillsOtherContainer.style.opacity = '0';
            setTimeout(() => {
                skillsOtherContainer.style.display = 'none';
            }, 300);
            skillsOtherInput.required = false;
            skillsOtherInput.value = '';
        }
    }

    // Check on page load
    if (skillsSelect) {
        if (skillsSelect.value === 'Others') {
            skillsOtherContainer.style.display = 'block';
            skillsOtherContainer.style.opacity = '1';
            skillsOtherInput.required = true;
        }
        skillsSelect.addEventListener('change', toggleSkillsOther);
    }

    // Handle Qualifications dropdown "Others" option
    const qualificationsSelect = document.getElementById('qualifications');
    const qualificationsOtherContainer = document.getElementById('qualifications_other_container');
    const qualificationsOtherInput = document.getElementById('qualifications_other');

    function toggleQualificationsOther() {
        if (qualificationsSelect && qualificationsSelect.value === 'Others') {
            qualificationsOtherContainer.style.opacity = '0';
            qualificationsOtherContainer.style.display = 'block';
            setTimeout(() => {
                qualificationsOtherContainer.style.opacity = '1';
            }, 10);
            qualificationsOtherInput.required = true;
            qualificationsOtherInput.focus();
        } else {
            qualificationsOtherContainer.style.opacity = '0';
            setTimeout(() => {
                qualificationsOtherContainer.style.display = 'none';
            }, 300);
            qualificationsOtherInput.required = false;
            qualificationsOtherInput.value = '';
        }
    }

    // Check on page load
    if (qualificationsSelect) {
        if (qualificationsSelect.value === 'Others') {
            qualificationsOtherContainer.style.display = 'block';
            qualificationsOtherContainer.style.opacity = '1';
            qualificationsOtherInput.required = true;
        }
        qualificationsSelect.addEventListener('change', toggleQualificationsOther);
    }

    // Handle Special Needs dropdown "Others" option
    const specialNeedsSelect = document.getElementById('special_needs');
    const specialNeedsOtherContainer = document.getElementById('special_needs_other_container');
    const specialNeedsOtherInput = document.getElementById('special_needs_other');

    function toggleSpecialNeedsOther() {
        if (specialNeedsSelect && specialNeedsSelect.value === 'Others') {
            specialNeedsOtherContainer.style.opacity = '0';
            specialNeedsOtherContainer.style.display = 'block';
            setTimeout(() => {
                specialNeedsOtherContainer.style.opacity = '1';
            }, 10);
            specialNeedsOtherInput.required = true;
            specialNeedsOtherInput.focus();
        } else {
            specialNeedsOtherContainer.style.opacity = '0';
            setTimeout(() => {
                specialNeedsOtherContainer.style.display = 'none';
            }, 300);
            specialNeedsOtherInput.required = false;
            specialNeedsOtherInput.value = '';
        }
    }

    // Check on page load
    if (specialNeedsSelect) {
        if (specialNeedsSelect.value === 'Others') {
            specialNeedsOtherContainer.style.display = 'block';
            specialNeedsOtherContainer.style.opacity = '1';
            specialNeedsOtherInput.required = true;
        }
        specialNeedsSelect.addEventListener('change', toggleSpecialNeedsOther);
    }

    // Image preview functionality
    const profilePhotoInput = document.getElementById('profile_photo');

    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file first
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('File size must be less than 5MB. Please choose a smaller file.');
                    this.value = '';
                    return;
                }

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, JPG, GIF, or WebP).');
                    this.value = '';
                    return;
                }

                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const currentPhoto = document.querySelector('.rounded-circle') || document.querySelector('[width="150"]');
                    if (currentPhoto) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'Profile Photo Preview';
                        img.className = 'rounded-circle border shadow-sm mb-3';
                        img.style.width = '150px';
                        img.style.height = '150px';
                        img.style.objectFit = 'cover';
                        img.style.border = '4px solid #e9ecef';

                        currentPhoto.parentNode.replaceChild(img, currentPhoto);
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Resume file validation
    const resumeInput = document.getElementById('resume');
    if (resumeInput) {
        resumeInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('Resume file size must be less than 5MB.');
                    this.value = '';
                    return;
                }
            }
        });
    }

    // Form submission with loading state
    const form = document.getElementById('profileUpdateForm');
    const submitBtn = document.getElementById('submitBtn');

    console.log('Form found:', !!form);
    console.log('Submit button found:', !!submitBtn);

    if (form && submitBtn) {
        // CRITICAL: Ensure button is always clickable and visible
        submitBtn.disabled = false;
        submitBtn.removeAttribute('disabled');
        submitBtn.style.pointerEvents = 'auto';
        submitBtn.style.cursor = 'pointer';
        submitBtn.style.opacity = '1';

        console.log('✓ Submit button initialized and ready');

        form.addEventListener('submit', function(e) {
            console.log('✓ Form submit event triggered');

            // Handle "Others" options for skills
            if (skillsSelect && skillsSelect.value === 'Others' && skillsOtherInput && skillsOtherInput.value.trim()) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'skills';
                hiddenInput.value = skillsOtherInput.value.trim();
                form.appendChild(hiddenInput);
                skillsSelect.disabled = true;
            }

            // Handle "Others" options for qualifications
            if (qualificationsSelect && qualificationsSelect.value === 'Others' && qualificationsOtherInput && qualificationsOtherInput.value.trim()) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'qualifications';
                hiddenInput.value = qualificationsOtherInput.value.trim();
                form.appendChild(hiddenInput);
                qualificationsSelect.disabled = true;
            }

            // Handle "Others" options for special needs
            if (specialNeedsSelect && specialNeedsSelect.value === 'Others' && specialNeedsOtherInput && specialNeedsOtherInput.value.trim()) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'special_needs';
                hiddenInput.value = specialNeedsOtherInput.value.trim();
                form.appendChild(hiddenInput);
                specialNeedsSelect.disabled = true;
            }

            // Validate only VISIBLE required fields
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let firstInvalidField = null;
            let invalidFields = [];

            requiredFields.forEach(field => {
                // Skip if field is hidden, disabled, or inside hidden container
                if (field.type === 'hidden' || field.disabled) {
                    return;
                }

                // Check if field or its container is hidden
                const container = field.closest('.col-12, .col-md-6, div[id$="_container"]');
                if (container && window.getComputedStyle(container).display === 'none') {
                    return;
                }

                if (field.offsetParent === null) {
                    return;
                }

                // Validate field value
                const value = field.value ? field.value.trim() : '';
                if (!value) {
                    field.classList.add('is-invalid');
                    isValid = false;
                    invalidFields.push(field.name || field.id);
                    if (!firstInvalidField) {
                        firstInvalidField = field;
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                console.warn('✗ Validation failed - missing required fields:', invalidFields);

                // Focus first invalid field
                if (firstInvalidField) {
                    firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    setTimeout(() => firstInvalidField.focus(), 300);
                }

                // Show error message with field names
                alert('Please fill in all required fields marked with *\n\nMissing fields: ' + invalidFields.join(', '));

                // Re-enable disabled selects
                if (skillsSelect) skillsSelect.disabled = false;
                if (qualificationsSelect) qualificationsSelect.disabled = false;
                if (specialNeedsSelect) specialNeedsSelect.disabled = false;

                return false;
            }

            console.log('✓ Validation passed - submitting form');

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> <span class="d-none d-sm-inline">Updating Profile...</span><span class="d-inline d-sm-none">Updating...</span>';

            // Disable other buttons
            const allButtons = form.querySelectorAll('button');
            allButtons.forEach(btn => {
                if (btn !== submitBtn) btn.disabled = true;
            });

            return true;
        });

        // Remove validation errors on input change
        const allInputs = form.querySelectorAll('input, select, textarea');
        allInputs.forEach(input => {
            ['input', 'change'].forEach(event => {
                input.addEventListener(event, function() {
                    this.classList.remove('is-invalid');
                });
            });
        });

        // Add additional click handler to ensure button works
        submitBtn.addEventListener('click', function(e) {
            console.log('✓ Submit button clicked');
        });

        console.log('✓ Form validation and submission handlers attached');
    } else {
        console.error('✗ Form or submit button not found!');
        if (!form) console.error('Form with id "profileUpdateForm" not found');
        if (!submitBtn) console.error('Button with id "submitBtn" not found');
    }

    console.log('✓ Profile edit form initialization complete');
});
</script>
@endsection
