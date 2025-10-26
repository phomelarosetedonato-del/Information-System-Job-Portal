@extends('layouts.app')

@section('title', 'Complete PWD Profile - PWD System')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-clipboard-check me-2"></i>
                        Complete Your PWD Profile
                    </h4>
                    <small class="opacity-75">Please provide your PWD information to access all features</small>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.pwd-complete') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Disability Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-wheelchair me-2 text-primary"></i>
                                    Disability Information
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="disability_type" class="form-label required">Type of Disability</label>
                                <select class="form-select @error('disability_type') is-invalid @enderror"
                                        id="disability_type" name="disability_type" required>
                                    <option value="">Select Disability Type</option>
                                    <option value="physical" {{ old('disability_type', $pwdProfile->disability_type ?? '') == 'physical' ? 'selected' : '' }}>Physical</option>
                                    <option value="visual" {{ old('disability_type', $pwdProfile->disability_type ?? '') == 'visual' ? 'selected' : '' }}>Visual Impairment</option>
                                    <option value="hearing" {{ old('disability_type', $pwdProfile->disability_type ?? '') == 'hearing' ? 'selected' : '' }}>Hearing Impairment</option>
                                    <option value="speech" {{ old('disability_type', $pwdProfile->disability_type ?? '') == 'speech' ? 'selected' : '' }}>Speech Impairment</option>
                                    <option value="intellectual" {{ old('disability_type', $pwdProfile->disability_type ?? '') == 'intellectual' ? 'selected' : '' }}>Intellectual Disability</option>
                                    <option value="psychosocial" {{ old('disability_type', $pwdProfile->disability_type ?? '') == 'psychosocial' ? 'selected' : '' }}>Psychosocial Disability</option>
                                    <option value="learning" {{ old('disability_type', $pwdProfile->disability_type ?? '') == 'learning' ? 'selected' : '' }}>Learning Disability</option>
                                    <option value="multiple" {{ old('disability_type', $pwdProfile->disability_type ?? '') == 'multiple' ? 'selected' : '' }}>Multiple Disabilities</option>
                                    <option value="other" {{ old('disability_type', $pwdProfile->disability_type ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('disability_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="disability_level" class="form-label required">Disability Level</label>
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

                        <!-- Emergency Contact -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-phone-emergency me-2 text-danger"></i>
                                    Emergency Contact Information
                                </h5>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="emergency_contact_name" class="form-label required">Emergency Contact Name</label>
                                <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror"
                                       id="emergency_contact_name" name="emergency_contact_name"
                                       value="{{ old('emergency_contact_name', $pwdProfile->emergency_contact_name ?? '') }}" required>
                                @error('emergency_contact_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="emergency_contact_phone" class="form-label required">Emergency Contact Phone</label>
                                <input type="tel" class="form-control @error('emergency_contact_phone') is-invalid @enderror"
                                       id="emergency_contact_phone" name="emergency_contact_phone"
                                       value="{{ old('emergency_contact_phone', $pwdProfile->emergency_contact_phone ?? '') }}" required>
                                @error('emergency_contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="emergency_contact_relationship" class="form-label required">Relationship</label>
                                <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror"
                                       id="emergency_contact_relationship" name="emergency_contact_relationship"
                                       value="{{ old('emergency_contact_relationship', $pwdProfile->emergency_contact_relationship ?? '') }}"
                                       placeholder="e.g., Parent, Spouse, Sibling" required>
                                @error('emergency_contact_relationship')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Skills & Accommodation -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-user-check me-2 text-success"></i>
                                    Skills & Accommodation Needs
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="skills" class="form-label">Skills & Talents</label>
                                <textarea class="form-control @error('skills') is-invalid @enderror"
                                          id="skills" name="skills"
                                          rows="3" placeholder="List your skills, talents, or areas of expertise">{{ old('skills', $pwdProfile->skills ?? '') }}</textarea>
                                @error('skills')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="interests" class="form-label">Interests & Hobbies</label>
                                <textarea class="form-control @error('interests') is-invalid @enderror"
                                          id="interests" name="interests"
                                          rows="3" placeholder="Your interests and hobbies">{{ old('interests', $pwdProfile->interests ?? '') }}</textarea>
                                @error('interests')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label for="accommodation_needs" class="form-label">Accommodation Needs</label>
                                <textarea class="form-control @error('accommodation_needs') is-invalid @enderror"
                                          id="accommodation_needs" name="accommodation_needs"
                                          rows="3" placeholder="Any specific accommodations needed for work or training (e.g., wheelchair access, sign language interpreter, etc.)">{{ old('accommodation_needs', $pwdProfile->accommodation_needs ?? '') }}</textarea>
                                @error('accommodation_needs')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- PWD Identification -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-id-card me-2 text-warning"></i>
                                    PWD Identification
                                </h5>
                            </div>

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
                                <small class="form-text text-muted">Upload a clear photo of your PWD ID (optional)</small>
                            </div>
                        </div>

                        <!-- Profile Photo -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-camera me-2 text-info"></i>
                                    Profile Photo
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="profile_photo" class="form-label">Profile Photo</label>
                                <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                                       id="profile_photo" name="profile_photo"
                                       accept="image/*">
                                @error('profile_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Upload a profile photo (optional)</small>
                            </div>

                            @if(($pwdProfile->profile_photo ?? false) && $pwdProfile->id)
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Current Photo</label>
                                <div>
                                    <img src="{{ Storage::url($pwdProfile->profile_photo) }}"
                                         alt="Current Profile Photo"
                                         class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Profile
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-check-circle me-1"></i> Complete Profile
                                    </button>
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

@section('styles')
<style>
.required:after {
    content: " *";
    color: #dc3545;
}

.card-header {
    border-bottom: none;
}

.form-label {
    font-weight: 500;
    color: #495057;
}

.border-bottom {
    border-color: #dee2e6 !important;
}
</style>
@endsection
