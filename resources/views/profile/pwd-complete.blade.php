@extends('layouts.app')

@section('title', 'Complete PWD Profile - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2 text-dark">
                        <i class="fas fa-clipboard-check me-2 text-primary"></i>
                        Complete Your PWD Profile
                    </h1>
                    <p class="mb-0 text-muted">Please provide your PWD information to access all features and opportunities</p>
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
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('profile.pwd-complete') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Disability Information -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-wheelchair me-2 text-primary"></i>
                                Disability Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="disability_type_id" class="form-label">Type of Disability <span class="text-danger">*</span></label>
                                    <select class="form-select @error('disability_type_id') is-invalid @enderror"
                                            id="disability_type_id" name="disability_type_id" required>
                                        <option value="">Select Disability Type</option>
                                        @foreach(($disabilityTypes ?? []) as $dt)
                                            <option value="{{ $dt->id }}" {{ old('disability_type_id', $pwdProfile->disability_type_id ?? '') == $dt->id ? 'selected' : '' }}>{{ $dt->type }}</option>
                                        @endforeach
                                    </select>
                                    @error('disability_type_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="disability_level" class="form-label">Disability Level <span class="text-danger">*</span></label>
                                    <select class="form-select @error('disability_level') is-invalid @enderror"
                                            id="disability_level" name="disability_level" required>
                                        <option value="">Select Level</option>
                                        <option value="mild" {{ old('disability_level', $pwdProfile->disability_level ?? '') == 'mild' ? 'selected' : '' }}>Mild</option>
                                        <option value="moderate" {{ old('disability_level', $pwdProfile->disability_level ?? '') == 'moderate' ? 'selected' : '' }}>Moderate</option>
                                        <option value="severe" {{ old('disability_level', $pwdProfile->disability_level ?? '') == 'severe' ? 'selected' : '' }}>Severe</option>
                                        <option value="profound" {{ old('disability_level', $pwdProfile->disability_level ?? '') == 'profound' ? 'selected' : '' }}>Profound</option>
                                    </select>
                                    @error('disability_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="assistive_devices" class="form-label">Assistive Devices Used</label>
                                    <textarea class="form-control @error('assistive_devices') is-invalid @enderror"
                                              id="assistive_devices" name="assistive_devices"
                                              rows="2" placeholder="e.g., Wheelchair, hearing aid, white cane, etc.">{{ old('assistive_devices', $pwdProfile->assistive_devices ?? '') }}</textarea>
                                    @error('assistive_devices')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="medical_conditions" class="form-label">Medical Conditions</label>
                                    <textarea class="form-control @error('medical_conditions') is-invalid @enderror"
                                              id="medical_conditions" name="medical_conditions"
                                              rows="2" placeholder="Any relevant medical conditions or allergies">{{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') }}</textarea>
                                    @error('medical_conditions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-phone-emergency me-2 text-danger"></i>
                                Emergency Contact Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_name" class="form-label">Emergency Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                           id="emergency_contact_name" name="emergency_contact_name"
                                           value="{{ old('emergency_contact_name', $pwdProfile->emergency_contact_name ?? '') }}" required>
                                    @error('emergency_contact_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                           id="emergency_contact_phone" name="emergency_contact_phone"
                                           value="{{ old('emergency_contact_phone', $pwdProfile->emergency_contact_phone ?? '') }}" required>
                                    @error('emergency_contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="emergency_contact_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror"
                                           id="emergency_contact_relationship" name="emergency_contact_relationship"
                                           value="{{ old('emergency_contact_relationship', $pwdProfile->emergency_contact_relationship ?? '') }}"
                                           placeholder="e.g., Parent, Spouse, Sibling" required>
                                    @error('emergency_contact_relationship')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Skills & Accommodation -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-user-check me-2 text-success"></i>
                                Skills & Accommodation Needs
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="skills" class="form-label">Skills & Talents</label>
                                    <textarea class="form-control @error('skills') is-invalid @enderror"
                                              id="skills" name="skills"
                                              rows="3" placeholder="List your skills, talents, or areas of expertise">{{ old('skills', $pwdProfile->skills ?? '') }}</textarea>
                                    @error('skills')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">This helps match you with suitable job opportunities.</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="interests" class="form-label">Interests & Hobbies</label>
                                    <textarea class="form-control @error('interests') is-invalid @enderror"
                                              id="interests" name="interests"
                                              rows="3" placeholder="Your interests and hobbies">{{ old('interests', $pwdProfile->interests ?? '') }}</textarea>
                                    @error('interests')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">Helps us understand your preferences for training programs.</div>
                                </div>

                                <div class="col-12 mb-3">
                                    <label for="accommodation_needs" class="form-label">Accommodation Needs</label>
                                    <textarea class="form-control @error('accommodation_needs') is-invalid @enderror"
                                              id="accommodation_needs" name="accommodation_needs"
                                              rows="3" placeholder="Any specific accommodations needed for work or training (e.g., wheelchair access, sign language interpreter, etc.)">{{ old('accommodation_needs', $pwdProfile->accommodation_needs ?? '') }}</textarea>
                                    @error('accommodation_needs')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror>
                                    <div class="form-text text-muted">This information helps employers provide appropriate workplace accommodations.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PWD Identification -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-id-card me-2 text-warning"></i>
                                PWD Identification
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pwd_id_number" class="form-label">PWD ID Number</label>
                                    <input type="text" class="form-control @error('pwd_id_number') is-invalid @enderror"
                                           id="pwd_id_number" name="pwd_id_number"
                                           value="{{ old('pwd_id_number', $pwdProfile->pwd_id_number ?? '') }}"
                                           placeholder="Your official PWD ID number">
                                    @error('pwd_id_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="pwd_id_photo" class="form-label">PWD ID Photo</label>
                                    <input type="file" class="form-control @error('pwd_id_photo') is-invalid @enderror"
                                           id="pwd_id_photo" name="pwd_id_photo"
                                           accept="image/*">
                                    @error('pwd_id_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">Upload a clear photo of your PWD ID (optional)</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Photo -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-camera me-2 text-info"></i>
                                Profile Photo
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3">
                                    <label for="profile_photo" class="form-label">Profile Photo</label>
                                    <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                                           id="profile_photo" name="profile_photo"
                                           accept="image/*">
                                    @error('profile_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">Upload a profile photo (optional)</div>
                                </div>

                                @if(($pwdProfile->profile_photo ?? false) && $pwdProfile->id)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Current Photo</label>
                                    <div>
                                        <img src="{{ Storage::url($pwdProfile->profile_photo) }}"
                                             alt="Current Profile Photo"
                                             class="img-thumbnail rounded" style="max-height: 100px;">
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Back to Profile
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check-circle me-2"></i> Complete Profile
                                </button>
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

    .form-text {
        font-size: 0.875rem;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File input validation
        const fileInputs = document.querySelectorAll('input[type="file"]');
        const maxSize = 2 * 1024 * 1024; // 2MB in bytes

        fileInputs.forEach(input => {
            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Check file size
                    if (file.size > maxSize) {
                        alert('File size exceeds 2MB limit. Please choose a smaller file.');
                        e.target.value = ''; // Clear the file input
                        return;
                    }

                    // Check file type for images
                    if (this.accept.includes('image')) {
                        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                        if (!validTypes.includes(file.type)) {
                            alert('Please select a valid image file (JPEG, PNG, JPG, or GIF).');
                            e.target.value = ''; // Clear the file input
                            return;
                        }
                    }
                }
            });
        });

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

        // Form submission validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Check required fields
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                return false;
            }
        });
    });
</script>
@endsection
