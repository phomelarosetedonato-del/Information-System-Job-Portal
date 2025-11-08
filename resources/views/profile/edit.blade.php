@extends('layouts.app')

@section('title', 'Edit Profile - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2 text-dark">
                        <i class="fas fa-user-edit me-2 text-primary"></i>
                        Edit Profile
                    </h1>
                    <p class="mb-0 text-muted">Update your personal information and PWD details</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
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

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
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
        @if($user->hasResume())
            <!-- Current Resume -->
            <div class="row align-items-center">
                <div class="col-md-8">
                    <p class="mb-1"><strong>Current Resume:</strong> {{ $user->resume_file_name }}</p>
                    <p class="mb-1 text-muted"><small>Size: {{ $user->resume_file_size }} • Type: {{ $user->resume_file_type }}</small></p>
                    <p class="mb-0">
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i> Resume Uploaded
                        </span>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="{{ route('profile.downloadResume') }}" class="btn btn-primary btn-sm me-2">
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                    <a href="{{ route('profile.deleteResume') }}"
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Are you sure you want to delete your resume?')">
                        <i class="fas fa-trash me-1"></i> Delete
                    </a>
                </div>
            </div>
        @else
            <!-- No Resume - Upload Form -->
            <div class="text-center py-3">
                <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-3">No resume uploaded yet.</p>

                <!-- Upload Resume Form -->
                <form action="{{ route('profile.uploadResume') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                    @csrf
                    <div class="mb-3">
                        <label for="resume" class="form-label">Upload Resume</label>
                        <input type="file"
                               class="form-control @error('resume') is-invalid @enderror"
                               id="resume"
                               name="resume"
                               accept=".pdf,.doc,.docx,.txt">
                        @error('resume')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Accepted formats: PDF, DOC, DOCX, TXT. Maximum file size: 5MB.
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload me-1"></i> Upload Resume
                    </button>
                </form>
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
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-universal-access me-2 text-primary"></i>
                                PWD Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="disability_type" class="form-label">Disability Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('disability_type') is-invalid @enderror"
                                            id="disability_type" name="disability_type" required>
                                        <option value="">Select Disability Type</option>
                                        @php
                                            $disabilityTypes = [
                                                'Physical Disability',
                                                'Visual Impairment',
                                                'Hearing Impairment',
                                                'Speech Impairment',
                                                'Intellectual Disability',
                                                'Psychosocial Disability',
                                                'Chronic Illness',
                                                'Multiple Disabilities',
                                                'Other'
                                            ];
                                            $currentDisabilityType = old('disability_type', $pwdProfile->disability_type ?? '');
                                        @endphp
                                        @foreach($disabilityTypes as $type)
                                            <option value="{{ $type }}" {{ $currentDisabilityType == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('disability_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
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

                                <div class="col-md-6 mb-3">
                                    <label for="birthdate" class="form-label">Birthdate</label>
                                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                           id="birthdate" name="birthdate"
                                           value="{{ old('birthdate', $pwdProfile && $pwdProfile->birthdate ? $pwdProfile->birthdate->format('Y-m-d') : '') }}"
                                           max="{{ date('Y-m-d') }}">
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Employment Status</label>
                                    <div class="form-check mt-2">
                                        @php
                                            $isEmployed = old('is_employed', $pwdProfile->is_employed ?? false);
                                        @endphp
                                        <input class="form-check-input" type="checkbox" id="is_employed" name="is_employed"
                                               value="1" {{ $isEmployed ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="is_employed">
                                            Currently Employed
                                        </label>
                                    </div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="skills" class="form-label">Skills & Abilities</label>
                                    <textarea class="form-control @error('skills') is-invalid @enderror"
                                              id="skills" name="skills" rows="3"
                                              placeholder="List your skills, talents, and abilities...">{{ old('skills', $pwdProfile->skills ?? '') }}</textarea>
                                    @error('skills')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">Separate different skills with commas or list them on new lines.</div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="qualifications" class="form-label">Educational Background & Qualifications</label>
                                    <textarea class="form-control @error('qualifications') is-invalid @enderror"
                                              id="qualifications" name="qualifications" rows="3"
                                              placeholder="List your educational background, training, certifications...">{{ old('qualifications', $pwdProfile->qualifications ?? '') }}</textarea>
                                    @error('qualifications')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="special_needs" class="form-label">Special Needs & Accommodations</label>
                                    <textarea class="form-control @error('special_needs') is-invalid @enderror"
                                              id="special_needs" name="special_needs" rows="3"
                                              placeholder="Describe any special accommodations or support you may need...">{{ old('special_needs', $pwdProfile->special_needs ?? '') }}</textarea>
                                    @error('special_needs')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">This helps employers provide appropriate workplace accommodations.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Profile
                                </a>
                                <div>
                                    <button type="reset" class="btn btn-outline-danger me-2">
                                        <i class="fas fa-undo me-2"></i> Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update Profile
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

    .rounded-circle:hover {
        transform: scale(1.05);
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Image preview functionality
    const profilePhotoInput = document.getElementById('profile_photo');
    let currentProfilePhoto = document.querySelector('.rounded-circle');

    if (profilePhotoInput) {
        profilePhotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create a new image element for preview
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = 'Profile Photo Preview';
                    img.className = 'rounded-circle border shadow-sm mb-3';
                    img.style.width = '150px';
                    img.style.height = '150px';
                    img.style.objectFit = 'cover';
                    img.style.border = '4px solid #e9ecef';

                    // Replace the current preview
                    const photoContainer = currentProfilePhoto.parentNode;
                    photoContainer.replaceChild(img, currentProfilePhoto);
                    currentProfilePhoto = img;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form validation enhancements
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('profile_photo');
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes

                if (file.size > maxSize) {
                    e.preventDefault();
                    alert('File size must be less than 2MB. Please choose a smaller file.');
                    fileInput.focus();
                    return false;
                }

                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    e.preventDefault();
                    alert('Please select a valid image file (JPEG, PNG, JPG, or GIF).');
                    fileInput.focus();
                    return false;
                }
            }
        });
    }

    // Character counters for textareas
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        const counter = document.createElement('div');
        counter.className = 'form-text text-end mt-1';
        counter.textContent = `${textarea.value.length} characters`;
        textarea.parentNode.appendChild(counter);

        textarea.addEventListener('input', function() {
            counter.textContent = `${this.value.length} characters`;
        });
    });

    // Real-time validation for required fields
    const requiredFields = document.querySelectorAll('input[required], select[required]');
    requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
            if (!this.value.trim()) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
    });

    console.log('Profile edit form loaded successfully');
});
</script>
@endsection
