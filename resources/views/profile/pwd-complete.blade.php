@extends('layouts.app')

@section('title', 'Complete PWD Profile - PWD System')

@php
    // Extract assistive devices value from JSON
    $assistiveDevicesValue = '';
    if (isset($pwdProfile->assistive_devices)) {
        if (is_array($pwdProfile->assistive_devices)) {
            $assistiveDevicesValue = $pwdProfile->assistive_devices['device'] ?? '';
        } elseif (is_string($pwdProfile->assistive_devices)) {
            $assistiveDevicesValue = $pwdProfile->assistive_devices;
        }
    }

    // Extract accessibility needs value from JSON
    $accessibilityNeedsValue = '';
    if (isset($pwdProfile->accessibility_needs)) {
        if (is_array($pwdProfile->accessibility_needs)) {
            $accessibilityNeedsValue = $pwdProfile->accessibility_needs['notes'] ?? '';
        } elseif (is_string($pwdProfile->accessibility_needs)) {
            $accessibilityNeedsValue = $pwdProfile->accessibility_needs;
        }
    }
@endphp

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
                                            @if($dt && is_object($dt))
                                            <option value="{{ $dt->id }}" {{ old('disability_type_id', $pwdProfile->disability_type_id ?? '') == $dt->id ? 'selected' : '' }}>{{ trim($dt->type) }}</option>
                                            @endif
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

                                <div class="col-md-6 mb-3">
                                    <label for="assistive_devices" class="form-label">Assistive Devices Used</label>
                                    <select class="form-select @error('assistive_devices') is-invalid @enderror"
                                            id="assistive_devices" name="assistive_devices">
                                        <option value="">Select Assistive Device</option>
                                        <option value="None" {{ old('assistive_devices', $assistiveDevicesValue) == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="Wheelchair" {{ old('assistive_devices', $assistiveDevicesValue) == 'Wheelchair' ? 'selected' : '' }}>Wheelchair</option>
                                        <option value="Crutches" {{ old('assistive_devices', $assistiveDevicesValue) == 'Crutches' ? 'selected' : '' }}>Crutches</option>
                                        <option value="Walker" {{ old('assistive_devices', $assistiveDevicesValue) == 'Walker' ? 'selected' : '' }}>Walker</option>
                                        <option value="White Cane" {{ old('assistive_devices', $assistiveDevicesValue) == 'White Cane' ? 'selected' : '' }}>White Cane</option>
                                        <option value="Hearing Aid" {{ old('assistive_devices', $assistiveDevicesValue) == 'Hearing Aid' ? 'selected' : '' }}>Hearing Aid</option>
                                        <option value="Cochlear Implant" {{ old('assistive_devices', $assistiveDevicesValue) == 'Cochlear Implant' ? 'selected' : '' }}>Cochlear Implant</option>
                                        <option value="Prosthetic Limb" {{ old('assistive_devices', $assistiveDevicesValue) == 'Prosthetic Limb' ? 'selected' : '' }}>Prosthetic Limb</option>
                                        <option value="Screen Reader" {{ old('assistive_devices', $assistiveDevicesValue) == 'Screen Reader' ? 'selected' : '' }}>Screen Reader</option>
                                        <option value="Braille Display" {{ old('assistive_devices', $assistiveDevicesValue) == 'Braille Display' ? 'selected' : '' }}>Braille Display</option>
                                        <option value="Communication Board" {{ old('assistive_devices', $assistiveDevicesValue) == 'Communication Board' ? 'selected' : '' }}>Communication Board</option>
                                        <option value="Others" {{ old('assistive_devices', $assistiveDevicesValue) != '' && !in_array(old('assistive_devices', $assistiveDevicesValue), ['None', 'Wheelchair', 'Crutches', 'Walker', 'White Cane', 'Hearing Aid', 'Cochlear Implant', 'Prosthetic Limb', 'Screen Reader', 'Braille Display', 'Communication Board']) ? 'selected' : '' }}>Others (Please Specify)</option>
                                    </select>
                                    @error('assistive_devices')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3" id="assistive_devices_other_container" style="display: none;">
                                    <label for="assistive_devices_other" class="form-label">Please Specify Assistive Device</label>
                                    <input type="text" class="form-control @error('assistive_devices') is-invalid @enderror"
                                           id="assistive_devices_other" name="assistive_devices_other"
                                           placeholder="Please specify the assistive device"
                                           value="{{ old('assistive_devices_other', (!in_array($assistiveDevicesValue, ['None', 'Wheelchair', 'Crutches', 'Walker', 'White Cane', 'Hearing Aid', 'Cochlear Implant', 'Prosthetic Limb', 'Screen Reader', 'Braille Display', 'Communication Board', ''])) ? $assistiveDevicesValue : '') }}">
                                    <div class="form-text text-muted">Please describe the assistive device you use</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="medical_conditions" class="form-label">Medical Conditions</label>
                                    <select class="form-select @error('medical_conditions') is-invalid @enderror"
                                            id="medical_conditions" name="medical_conditions">
                                        <option value="">Select Medical Condition</option>
                                        <option value="None" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="Asthma" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Asthma' ? 'selected' : '' }}>Asthma</option>
                                        <option value="Diabetes" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Diabetes' ? 'selected' : '' }}>Diabetes</option>
                                        <option value="Hypertension" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Hypertension' ? 'selected' : '' }}>Hypertension</option>
                                        <option value="Epilepsy" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Epilepsy' ? 'selected' : '' }}>Epilepsy</option>
                                        <option value="Heart Disease" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Heart Disease' ? 'selected' : '' }}>Heart Disease</option>
                                        <option value="Arthritis" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Arthritis' ? 'selected' : '' }}>Arthritis</option>
                                        <option value="Cerebral Palsy" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Cerebral Palsy' ? 'selected' : '' }}>Cerebral Palsy</option>
                                        <option value="Multiple Sclerosis" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Multiple Sclerosis' ? 'selected' : '' }}>Multiple Sclerosis</option>
                                        <option value="Autism Spectrum Disorder" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Autism Spectrum Disorder' ? 'selected' : '' }}>Autism Spectrum Disorder</option>
                                        <option value="ADHD" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'ADHD' ? 'selected' : '' }}>ADHD</option>
                                        <option value="Depression/Anxiety" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Depression/Anxiety' ? 'selected' : '' }}>Depression/Anxiety</option>
                                        <option value="Allergies" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') == 'Allergies' ? 'selected' : '' }}>Allergies</option>
                                        <option value="Others" {{ old('medical_conditions', $pwdProfile->medical_conditions ?? '') != '' && !in_array(old('medical_conditions', $pwdProfile->medical_conditions ?? ''), ['None', 'Asthma', 'Diabetes', 'Hypertension', 'Epilepsy', 'Heart Disease', 'Arthritis', 'Cerebral Palsy', 'Multiple Sclerosis', 'Autism Spectrum Disorder', 'ADHD', 'Depression/Anxiety', 'Allergies']) ? 'selected' : '' }}>Others (Please Specify)</option>
                                    </select>
                                    @error('medical_conditions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3" id="medical_conditions_other_container" style="display: none;">
                                    <label for="medical_conditions_other" class="form-label">Please Specify Medical Condition</label>
                                    <input type="text" class="form-control @error('medical_conditions') is-invalid @enderror"
                                           id="medical_conditions_other" name="medical_conditions_other"
                                           placeholder="Please specify your medical condition or allergies"
                                           value="{{ old('medical_conditions_other', (!in_array($pwdProfile->medical_conditions ?? '', ['None', 'Asthma', 'Diabetes', 'Hypertension', 'Epilepsy', 'Heart Disease', 'Arthritis', 'Cerebral Palsy', 'Multiple Sclerosis', 'Autism Spectrum Disorder', 'ADHD', 'Depression/Anxiety', 'Allergies', ''])) ? ($pwdProfile->medical_conditions ?? '') : '') }}">
                                    <div class="form-text text-muted">Please describe any relevant medical conditions or allergies</div>
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

                                <div class="col-md-6 mb-3">
                                    <label for="accommodation_needs" class="form-label">Accommodation Needs</label>
                                    <select class="form-select @error('accommodation_needs') is-invalid @enderror"
                                            id="accommodation_needs" name="accommodation_needs">
                                        <option value="">Select Accommodation Need</option>
                                        <option value="None" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                        <option value="Wheelchair Access" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Wheelchair Access' ? 'selected' : '' }}>Wheelchair Access</option>
                                        <option value="Accessible Parking" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Accessible Parking' ? 'selected' : '' }}>Accessible Parking</option>
                                        <option value="Accessible Restroom" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Accessible Restroom' ? 'selected' : '' }}>Accessible Restroom</option>
                                        <option value="Elevator Access" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Elevator Access' ? 'selected' : '' }}>Elevator Access</option>
                                        <option value="Sign Language Interpreter" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Sign Language Interpreter' ? 'selected' : '' }}>Sign Language Interpreter</option>
                                        <option value="Screen Reader Software" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Screen Reader Software' ? 'selected' : '' }}>Screen Reader Software</option>
                                        <option value="Assistive Technology" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Assistive Technology' ? 'selected' : '' }}>Assistive Technology</option>
                                        <option value="Flexible Work Hours" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Flexible Work Hours' ? 'selected' : '' }}>Flexible Work Hours</option>
                                        <option value="Remote Work Option" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Remote Work Option' ? 'selected' : '' }}>Remote Work Option</option>
                                        <option value="Ergonomic Workstation" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Ergonomic Workstation' ? 'selected' : '' }}>Ergonomic Workstation</option>
                                        <option value="Quiet Workspace" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Quiet Workspace' ? 'selected' : '' }}>Quiet Workspace</option>
                                        <option value="Large Print Materials" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Large Print Materials' ? 'selected' : '' }}>Large Print Materials</option>
                                        <option value="Braille Materials" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Braille Materials' ? 'selected' : '' }}>Braille Materials</option>
                                        <option value="Modified Equipment" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') == 'Modified Equipment' ? 'selected' : '' }}>Modified Equipment</option>
                                        <option value="Others" {{ old('accommodation_needs', $accessibilityNeedsValue ?? '') != '' && !in_array(old('accommodation_needs', $accessibilityNeedsValue ?? ''), ['None', 'Wheelchair Access', 'Accessible Parking', 'Accessible Restroom', 'Elevator Access', 'Sign Language Interpreter', 'Screen Reader Software', 'Assistive Technology', 'Flexible Work Hours', 'Remote Work Option', 'Ergonomic Workstation', 'Quiet Workspace', 'Large Print Materials', 'Braille Materials', 'Modified Equipment']) ? 'selected' : '' }}>Others (Please Specify)</option>
                                    </select>
                                    @error('accommodation_needs')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">This information helps employers provide appropriate workplace accommodations.</div>
                                </div>

                                <div class="col-md-6 mb-3" id="accommodation_needs_other_container" style="display: none;">
                                    <label for="accommodation_needs_other" class="form-label">Please Specify Accommodation Need</label>
                                    <textarea class="form-control @error('accommodation_needs') is-invalid @enderror"
                                              id="accommodation_needs_other" name="accommodation_needs_other"
                                              rows="3" placeholder="Please specify any specific accommodations needed for work or training">{{ old('accommodation_needs_other', (!in_array($accessibilityNeedsValue ?? '', ['None', 'Wheelchair Access', 'Accessible Parking', 'Accessible Restroom', 'Elevator Access', 'Sign Language Interpreter', 'Screen Reader Software', 'Assistive Technology', 'Flexible Work Hours', 'Remote Work Option', 'Ergonomic Workstation', 'Quiet Workspace', 'Large Print Materials', 'Braille Materials', 'Modified Equipment', ''])) ? ($accessibilityNeedsValue ?? '') : '') }}</textarea>
                                    <div class="form-text text-muted">Please describe your specific accommodation requirements</div>
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
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    @error('pwd_id_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">Upload a clear photo of your PWD ID - Max 5MB (JPEG, PNG, JPG, GIF, WebP)</div>
                                    <div id="pwd_id_photo_preview" class="mt-2" style="display: none;">
                                        <img src="" alt="PWD ID Preview" class="img-thumbnail" style="max-height: 150px;">
                                    </div>
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
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                    @error('profile_photo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">Upload a profile photo - Max 5MB (JPEG, PNG, JPG, GIF, WebP)</div>
                                    <div id="profile_photo_preview" class="mt-2" style="display: none;">
                                        <img src="" alt="Profile Photo Preview" class="img-thumbnail rounded-circle" style="max-height: 150px; max-width: 150px;">
                                    </div>
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

    /* Fix dropdown option spacing */
    .form-select option {
        padding: 8px 12px;
        white-space: normal;
        line-height: 1.5;
    }

    /* Ensure consistent dropdown width */
    .form-select {
        width: 100%;
        max-width: 100%;
    }

    /* Image preview styles */
    #pwd_id_photo_preview img,
    #profile_photo_preview img {
        border: 2px solid #dee2e6;
        transition: all 0.3s ease;
    }

    #pwd_id_photo_preview img:hover,
    #profile_photo_preview img:hover {
        border-color: #4361ee;
        transform: scale(1.02);
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle assistive devices dropdown
        const assistiveDevicesSelect = document.getElementById('assistive_devices');
        const assistiveDevicesOtherContainer = document.getElementById('assistive_devices_other_container');
        const assistiveDevicesOtherInput = document.getElementById('assistive_devices_other');

        function toggleAssistiveDevicesOther() {
            if (assistiveDevicesSelect.value === 'Others') {
                assistiveDevicesOtherContainer.style.display = 'block';
                assistiveDevicesOtherInput.required = true;
            } else {
                assistiveDevicesOtherContainer.style.display = 'none';
                assistiveDevicesOtherInput.required = false;
                assistiveDevicesOtherInput.value = '';
            }
        }

        // Check on page load if "Others" is selected
        toggleAssistiveDevicesOther();

        // Add change event listener
        assistiveDevicesSelect.addEventListener('change', toggleAssistiveDevicesOther);

        // Handle medical conditions dropdown
        const medicalConditionsSelect = document.getElementById('medical_conditions');
        const medicalConditionsOtherContainer = document.getElementById('medical_conditions_other_container');
        const medicalConditionsOtherInput = document.getElementById('medical_conditions_other');

        function toggleMedicalConditionsOther() {
            if (medicalConditionsSelect.value === 'Others') {
                medicalConditionsOtherContainer.style.display = 'block';
                medicalConditionsOtherInput.required = true;
            } else {
                medicalConditionsOtherContainer.style.display = 'none';
                medicalConditionsOtherInput.required = false;
                medicalConditionsOtherInput.value = '';
            }
        }

        // Check on page load if "Others" is selected
        toggleMedicalConditionsOther();

        // Add change event listener
        medicalConditionsSelect.addEventListener('change', toggleMedicalConditionsOther);

        // Handle accommodation needs dropdown
        const accommodationNeedsSelect = document.getElementById('accommodation_needs');
        const accommodationNeedsOtherContainer = document.getElementById('accommodation_needs_other_container');
        const accommodationNeedsOtherInput = document.getElementById('accommodation_needs_other');

        function toggleAccommodationNeedsOther() {
            if (accommodationNeedsSelect.value === 'Others') {
                accommodationNeedsOtherContainer.style.display = 'block';
                accommodationNeedsOtherInput.required = true;
            } else {
                accommodationNeedsOtherContainer.style.display = 'none';
                accommodationNeedsOtherInput.required = false;
                accommodationNeedsOtherInput.value = '';
            }
        }

        // Check on page load if "Others" is selected
        toggleAccommodationNeedsOther();

        // Add change event listener
        accommodationNeedsSelect.addEventListener('change', toggleAccommodationNeedsOther);

        // File input validation with optimized preview
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];

        // Show loading indicator
        function showLoadingIndicator(previewElement) {
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'text-center p-3';
            loadingDiv.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Loading preview...';
            loadingDiv.id = 'loading-indicator';
            previewElement.innerHTML = '';
            previewElement.appendChild(loadingDiv);
            previewElement.style.display = 'block';
        }

        // Optimized image preview function to prevent Chrome freezing
        function createImagePreview(file, previewElement, callback) {
            // Show loading indicator
            showLoadingIndicator(previewElement);

            // Use setTimeout to prevent blocking the UI thread
            setTimeout(function() {
                try {
                    // Create object URL instead of reading entire file into memory
                    const objectUrl = URL.createObjectURL(file);

                    const img = new Image();

                    img.onload = function() {
                        // Use requestAnimationFrame for smooth rendering
                        requestAnimationFrame(function() {
                            try {
                                // Revoke the object URL to free memory
                                URL.revokeObjectURL(objectUrl);

                                // Create canvas for optimized preview
                                const canvas = document.createElement('canvas');
                                const ctx = canvas.getContext('2d', { alpha: false });

                                // Calculate new dimensions (max 150px for preview)
                                const maxDimension = 150;
                                let width = img.width;
                                let height = img.height;

                                if (width > height) {
                                    if (width > maxDimension) {
                                        height = Math.floor(height * (maxDimension / width));
                                        width = maxDimension;
                                    }
                                } else {
                                    if (height > maxDimension) {
                                        width = Math.floor(width * (maxDimension / height));
                                        height = maxDimension;
                                    }
                                }

                                canvas.width = width;
                                canvas.height = height;

                                // Draw image on canvas with better quality
                                ctx.imageSmoothingEnabled = true;
                                ctx.imageSmoothingQuality = 'high';
                                ctx.drawImage(img, 0, 0, width, height);

                                // Get data URL from canvas (compressed to 70% quality)
                                const previewUrl = canvas.toDataURL('image/jpeg', 0.7);

                                if (callback) {
                                    callback(previewUrl);
                                }
                            } catch (error) {
                                console.error('Error processing image:', error);
                                alert('Error processing image preview. Please try a different file.');
                                previewElement.style.display = 'none';
                            }
                        });
                    };

                    img.onerror = function() {
                        URL.revokeObjectURL(objectUrl);
                        alert('Error loading image preview. The file may be corrupted or in an unsupported format.');
                        previewElement.style.display = 'none';
                    };

                    img.src = objectUrl;
                } catch (error) {
                    console.error('Error creating preview:', error);
                    alert('Error loading image preview. Please try again.');
                    previewElement.style.display = 'none';
                }
            }, 50); // Small delay to allow UI to update
        }

        // PWD ID Photo handler
        const pwdIdPhotoInput = document.getElementById('pwd_id_photo');
        const pwdIdPhotoPreview = document.getElementById('pwd_id_photo_preview');

        if (pwdIdPhotoInput) {
            pwdIdPhotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                // Hide preview if no file
                if (!file) {
                    pwdIdPhotoPreview.style.display = 'none';
                    return;
                }

                // Check file size
                if (file.size > maxSize) {
                    alert('File size exceeds 5MB limit. Please choose a smaller file.');
                    e.target.value = '';
                    pwdIdPhotoPreview.style.display = 'none';
                    return;
                }

                // Check file type
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, JPG, GIF, or WebP).');
                    e.target.value = '';
                    pwdIdPhotoPreview.style.display = 'none';
                    return;
                }

                // Show preview with optimized loading
                createImagePreview(file, pwdIdPhotoPreview, function(previewUrl) {
                    // Remove loading indicator and show actual image
                    pwdIdPhotoPreview.innerHTML = '<img src="" alt="PWD ID Preview" class="img-thumbnail" style="max-height: 150px;">';
                    pwdIdPhotoPreview.querySelector('img').src = previewUrl;
                    pwdIdPhotoPreview.style.display = 'block';
                });
            });
        }

        // Profile Photo handler
        const profilePhotoInput = document.getElementById('profile_photo');
        const profilePhotoPreview = document.getElementById('profile_photo_preview');

        if (profilePhotoInput) {
            profilePhotoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                // Hide preview if no file
                if (!file) {
                    profilePhotoPreview.style.display = 'none';
                    return;
                }

                // Check file size
                if (file.size > maxSize) {
                    alert('File size exceeds 5MB limit. Please choose a smaller file.');
                    e.target.value = '';
                    profilePhotoPreview.style.display = 'none';
                    return;
                }

                // Check file type
                if (!validTypes.includes(file.type)) {
                    alert('Please select a valid image file (JPEG, PNG, JPG, GIF, or WebP).');
                    e.target.value = '';
                    profilePhotoPreview.style.display = 'none';
                    return;
                }

                // Show preview with optimized loading
                createImagePreview(file, profilePhotoPreview, function(previewUrl) {
                    // Remove loading indicator and show actual image
                    profilePhotoPreview.innerHTML = '<img src="" alt="Profile Photo Preview" class="img-thumbnail rounded-circle" style="max-height: 150px; max-width: 150px;">';
                    profilePhotoPreview.querySelector('img').src = previewUrl;
                    profilePhotoPreview.style.display = 'block';
                });
            });
        }

        // Form submission - show loading indicator
        const form = document.querySelector('form');
        const submitBtn = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', function(e) {
            // If "Others" is selected for assistive devices, use the other input value
            if (assistiveDevicesSelect.value === 'Others' && assistiveDevicesOtherInput.value.trim()) {
                // Create a hidden input to send the custom value
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'assistive_devices';
                hiddenInput.value = assistiveDevicesOtherInput.value.trim();
                form.appendChild(hiddenInput);
                // Disable the select to prevent its value from being sent
                assistiveDevicesSelect.disabled = true;
            }

            // If "Others" is selected for medical conditions, use the other input value
            if (medicalConditionsSelect.value === 'Others' && medicalConditionsOtherInput.value.trim()) {
                // Create a hidden input to send the custom value
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'medical_conditions';
                hiddenInput.value = medicalConditionsOtherInput.value.trim();
                form.appendChild(hiddenInput);
                // Disable the select to prevent its value from being sent
                medicalConditionsSelect.disabled = true;
            }

            // If "Others" is selected for accommodation needs, use the other input value
            if (accommodationNeedsSelect.value === 'Others' && accommodationNeedsOtherInput.value.trim()) {
                // Create a hidden input to send the custom value
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'accommodation_needs';
                hiddenInput.value = accommodationNeedsOtherInput.value.trim();
                form.appendChild(hiddenInput);
                // Disable the select to prevent its value from being sent
                accommodationNeedsSelect.disabled = true;
            }

            // Basic validation
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value || !field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields marked with *.');
                // Re-enable the selects if validation fails
                assistiveDevicesSelect.disabled = false;
                medicalConditionsSelect.disabled = false;
                accommodationNeedsSelect.disabled = false;
                return false;
            }

            // Show loading state on submit button
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Saving Profile...';

            // Allow form to submit
            return true;
        });
    });
</script>
@endsection

