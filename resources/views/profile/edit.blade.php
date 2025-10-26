@extends('layouts.app')

@section('title', 'Edit Profile - PWD System')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0">Edit Profile</h1>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Photo Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="text-center">
                                    <!-- Current Profile Photo -->
                                    <div class="mb-3">
                                        @php
                                            $hasProfilePhoto = isset($pwdProfile) && $pwdProfile->has_profile_photo;
                                            $profilePhotoUrl = $hasProfilePhoto ? ($pwdProfile->profile_photo_url . '?t=' . time()) : null;
                                        @endphp

                                        @if($hasProfilePhoto && $profilePhotoUrl)
                                            <img src="{{ $profilePhotoUrl }}"
                                                 alt="Profile Photo of {{ $user->name }}"
                                                 class="rounded-circle border"
                                                 width="150"
                                                 height="150"
                                                 style="object-fit: cover; border: 3px solid #dee2e6;">
                                        @else
                                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center border"
                                                 style="width: 150px; height: 150px; border: 3px solid #dee2e6;">
                                                <i class="fas fa-user text-white fa-3x"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Photo Upload Controls -->
                                    <div class="d-flex justify-content-center">
                                        <div class="mb-3 w-75">
                                            <label for="profile_photo" class="form-label fw-bold">Update Profile Photo</label>
                                            <input type="file"
                                                   class="form-control @error('profile_photo') is-invalid @enderror"
                                                   id="profile_photo"
                                                   name="profile_photo"
                                                   accept="image/jpeg,image/png,image/jpg,image/gif">
                                            @error('profile_photo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">
                                                Upload a JPG, PNG, or GIF image. Maximum file size: 2MB.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Remove Photo Button -->
                                    @if($hasProfilePhoto)
                                        <div class="mt-2">
                                            <a href="{{ route('profile.deletePhoto') }}"
                                               class="btn btn-outline-danger btn-sm"
                                               onclick="return confirm('Are you sure you want to remove your profile photo?')">
                                                <i class="fas fa-trash me-1"></i> Remove Current Photo
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- User Basic Information Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">Basic Information</h5>
                            </div>

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

                        <!-- PWD Profile Fields -->
@if($user->isPwd())
<div class="row mb-4">
    <div class="col-12">
        <h5 class="border-bottom pb-2 mb-3">PWD Information</h5>
    </div>

    <div class="col-md-6 mb-3">
        <label for="disability_type" class="form-label">Disability Type <span class="text-danger">*</span></label>
        <select class="form-control @error('disability_type') is-invalid @enderror"
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
        <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
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
               value="{{ old('birthdate', isset($pwdProfile) && $pwdProfile->birthdate ? $pwdProfile->birthdate->format('Y-m-d') : '') }}"
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
        <div class="form-text">Separate different skills with commas or list them on new lines.</div>
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
        <div class="form-text">This helps employers provide appropriate workplace accommodations.</div>
    </div>
</div>
@endif

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Profile
                                    </a>
                                    <div>
                                        <button type="reset" class="btn btn-outline-danger me-2">
                                            <i class="fas fa-undo me-1"></i> Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Update Profile
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
</div>
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
                    img.className = 'rounded-circle border';
                    img.style.width = '150px';
                    img.style.height = '150px';
                    img.style.objectFit = 'cover';
                    img.style.border = '3px solid #dee2e6';

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
        counter.className = 'form-text text-end';
        counter.textContent = `${textarea.value.length} characters`;
        textarea.parentNode.appendChild(counter);

        textarea.addEventListener('input', function() {
            counter.textContent = `${this.value.length} characters`;
        });
    });

    console.log('Profile edit form loaded successfully');
});
</script>

<style>
.form-label {
    font-weight: 500;
    color: #495057;
}

.border-bottom {
    border-color: #dee2e6 !important;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.btn {
    border-radius: 0.375rem;
}

.form-control:focus, .form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.rounded-circle {
    transition: all 0.3s ease;
}

.rounded-circle:hover {
    transform: scale(1.05);
}
</style>
@endsection
