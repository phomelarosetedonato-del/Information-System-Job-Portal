{{-- Comprehensive Profile Form Fields for both edit and complete modes --}}

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
                @php
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

                @if($hasProfilePhoto && $pwdProfile)
                    <div class="mt-2">
                        <button type="button" class="btn btn-outline-danger btn-sm" onclick="document.getElementById('deletePhotoForm').submit();">
                            <i class="fas fa-trash me-1"></i> Remove Photo
                        </button>
                    </div>
                @endif
            </div>
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="profile_photo" class="form-label">Upload New Profile Photo</label>
                    <input type="file" class="form-control @error('profile_photo') is-invalid @enderror" id="profile_photo" name="profile_photo" accept="image/*">
                    @error('profile_photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Accepted formats: JPG, PNG, GIF. Max size: 2MB</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($hasProfilePhoto && $pwdProfile)
    <form id="deletePhotoForm" action="{{ route('profile.deletePhoto') }}" method="POST" style="display: none;" onsubmit="return confirm('Are you sure you want to remove your profile photo?')">
        @csrf
        @method('DELETE')
    </form>
@endif

<!-- Personal Information Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-dark">
            <i class="fas fa-user me-2 text-primary"></i>
            Personal Information
        </h5>
    </div>
    <div class="card-body p-3 p-md-4">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                       value="{{ old('email', $user->email) }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                       value="{{ old('phone', $user->phone ?? '') }}">
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="Male" {{ old('gender', $pwdProfile->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender', $pwdProfile->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                    <option value="Other" {{ old('gender', $pwdProfile->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
                @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="birthdate" class="form-label">Date of Birth</label>
                <input type="date" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" name="birthdate"
                       value="{{ old('birthdate', $pwdProfile && $pwdProfile->birthdate ? $pwdProfile->birthdate->format('Y-m-d') : '') }}"
                       max="{{ date('Y-m-d') }}">
                @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="nationality" class="form-label">Nationality</label>
                <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality"
                       value="{{ old('nationality', $pwdProfile->nationality ?? '') }}">
                @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label for="address" class="form-label">Complete Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $user->address ?? '') }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

<!-- Education Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-dark">
            <i class="fas fa-graduation-cap me-2 text-primary"></i>
            Education
        </h5>
    </div>
    <div class="card-body p-3 p-md-4">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="education_level" class="form-label">Education Level</label>
                <select class="form-select @error('education_level') is-invalid @enderror" id="education_level" name="education_level">
                    <option value="">-- Select Educational Background --</option>
                    @foreach($qualificationOptions as $option)
                        <option value="{{ $option->name }}"
                            {{ old('education_level', $pwdProfile->education_level ?? '') == $option->name ? 'selected' : '' }}>
                            {{ $option->name }}
                        </option>
                    @endforeach
                    <option value="Others"
                        {{ old('education_level', $pwdProfile->education_level ?? '') == 'Others' ? 'selected' : '' }}>
                        Others (Please Specify)
                    </option>
                </select>
                @error('education_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="school_name" class="form-label">School/Institution Name</label>
                <input type="text" class="form-control @error('school_name') is-invalid @enderror" id="school_name" name="school_name"
                       value="{{ old('school_name', $pwdProfile->school_name ?? '') }}"
                       placeholder="Enter school or institution name">
                @error('school_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

<!-- PWD Information Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-dark">
            <i class="fas fa-universal-access me-2 text-primary"></i>
            PWD Information
        </h5>
    </div>
    <div class="card-body p-3 p-md-4">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="disability_type_id" class="form-label">Disability Type <span class="text-danger">*</span></label>
                <select class="form-select @error('disability_type_id') is-invalid @enderror" id="disability_type_id" name="disability_type_id" required>
                    <option value="">Select Disability Type</option>
                    @foreach($disabilityTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ old('disability_type_id', $pwdProfile->disability_type_id ?? '') == $type->id ? 'selected' : '' }}>
                            {{ $type->type }}
                        </option>
                    @endforeach
                </select>
                @error('disability_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="pwd_id_number" class="form-label">PWD ID Number</label>
                <input type="text" class="form-control @error('pwd_id_number') is-invalid @enderror" id="pwd_id_number" name="pwd_id_number"
                       value="{{ old('pwd_id_number', $pwdProfile->pwd_id_number ?? '') }}"
                       placeholder="Enter your PWD ID number">
                @error('pwd_id_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="pwd_id_photo" class="form-label">PWD ID Photo</label>
                <input type="file" class="form-control @error('pwd_id_photo') is-invalid @enderror" id="pwd_id_photo" name="pwd_id_photo" accept=".jpg,.jpeg,.png,.gif,.webp">
                @error('pwd_id_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <div class="form-text">Accepted formats: JPG, PNG, GIF, WEBP. Max size: 7MB</div>
                @if(isset($pwdProfile->pwd_id_photo) && $pwdProfile->pwd_id_photo)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $pwdProfile->pwd_id_photo) }}" alt="PWD ID Photo" class="img-thumbnail" style="max-width: 150px;">
                    </div>
                @endif
            </div>
            <div class="col-12 col-md-6">
                <label for="skills" class="form-label">Skills & Abilities</label>
                <select class="form-select @error('skills') is-invalid @enderror" id="skills" name="skills">
                    <option value="">-- Select Skill --</option>
                    @foreach($skillOptions as $option)
                        <option value="{{ $option->name }}"
                            {{ old('skills', $pwdProfile->skills ?? '') == $option->name ? 'selected' : '' }}>
                            {{ $option->name }}
                        </option>
                    @endforeach
                    <option value="Others"
                        {{ old('skills', $pwdProfile->skills ?? '') != '' && !$skillOptions->pluck('name')->contains(old('skills', $pwdProfile->skills ?? '')) ? 'selected' : '' }}>
                        Others (Please Specify)
                    </option>
                </select>
                @error('skills')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6" id="skills_other_container" style="display: none;">
                <label for="skills_other" class="form-label">Please Specify Your Skill</label>
                <input type="text" class="form-control" id="skills_other" name="skills_other"
                       value="{{ old('skills_other', (!$skillOptions->pluck('name')->contains($pwdProfile->skills ?? '') && $pwdProfile->skills) ? $pwdProfile->skills : '') }}"
                       placeholder="Enter your specific skill">
            </div>
            <div class="col-12">
                <label for="limitations" class="form-label">Limitations & Support Needs</label>
                <textarea class="form-control @error('limitations') is-invalid @enderror" id="limitations" name="limitations" rows="3"
                          placeholder="Describe any limitations employers should know about">{{ old('limitations', $pwdProfile->limitations ?? '') }}</textarea>
                @error('limitations')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6">
                <label for="special_needs" class="form-label">Special Needs & Accommodations</label>
                <select class="form-select @error('special_needs') is-invalid @enderror" id="special_needs" name="special_needs">
                    <option value="">-- Select Accommodation Need --</option>
                    @foreach($accommodationOptions as $option)
                        <option value="{{ $option->name }}"
                            {{ old('special_needs', $pwdProfile->special_needs ?? '') == $option->name ? 'selected' : '' }}>
                            {{ $option->name }}
                        </option>
                    @endforeach
                    <option value="Others"
                        {{ old('special_needs', $pwdProfile->special_needs ?? '') != '' && !$accommodationOptions->pluck('name')->contains(old('special_needs', $pwdProfile->special_needs ?? '')) ? 'selected' : '' }}>
                        Others (Please Specify)
                    </option>
                </select>
                @error('special_needs')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12 col-md-6" id="special_needs_other_container" style="display: none;">
                <label for="special_needs_other" class="form-label">Please Specify Your Accommodation Needs</label>
                <input type="text" class="form-control" id="special_needs_other" name="special_needs_other"
                       value="{{ old('special_needs_other', (!$accommodationOptions->pluck('name')->contains($pwdProfile->special_needs ?? '') && $pwdProfile->special_needs) ? $pwdProfile->special_needs : '') }}"
                       placeholder="Enter your specific accommodation needs">
            </div>
        </div>
    </div>
</div>

<!-- Employment Information Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-dark">
            <i class="fas fa-briefcase me-2 text-primary"></i>
            Employment Information
        </h5>
    </div>
    <div class="card-body p-3 p-md-4">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label class="form-label">Employment Status</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="is_employed" name="is_employed" value="1"
                           {{ old('is_employed', $pwdProfile->is_employed ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_employed">
                        Currently Employed
                    </label>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <label for="desired_position" class="form-label">Desired Position</label>
                <input type="text" class="form-control" id="desired_position" name="desired_position"
                       value="{{ old('desired_position', $pwdProfile->desired_position ?? '') }}"
                       placeholder="e.g., Software Developer, Data Analyst">
            </div>
            <div class="col-12 col-md-6">
                <label for="employment_type" class="form-label">Preferred Employment Type</label>
                <select class="form-select" id="employment_type" name="employment_type">
                    <option value="">-- Select Employment Type --</option>
                    <option value="Full-time" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                    <option value="Part-time" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                    <option value="Contract" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Contract' ? 'selected' : '' }}>Contract</option>
                    <option value="Freelance" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                    <option value="Remote" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Remote' ? 'selected' : '' }}>Remote</option>
                </select>
            </div>
            <div class="col-12 col-md-6">
                <label for="preferred_work_conditions" class="form-label">Preferred Work Conditions</label>
                <select class="form-select" id="preferred_work_conditions" name="preferred_work_conditions">
                    <option value="">-- Select Work Conditions --</option>
                    @if(isset($workArrangementOptions))
                        @foreach($workArrangementOptions as $option)
                            <option value="{{ $option->name }}"
                                {{ old('preferred_work_conditions', $pwdProfile->preferred_work_conditions ?? '') == $option->name ? 'selected' : '' }}>
                                {{ $option->name }}
                            </option>
                        @endforeach
                    @endif
                    <option value="Others"
                        {{ old('preferred_work_conditions', $pwdProfile->preferred_work_conditions ?? '') == 'Others' ? 'selected' : '' }}>
                        Others (Please Specify)
                    </option>
                </select>
            </div>
            <div class="col-12 col-md-6" id="preferred_work_conditions_other_container" style="display: none;">
                <label for="preferred_work_conditions_other" class="form-label">Please Specify Your Preferred Work Conditions</label>
                <input type="text" class="form-control" id="preferred_work_conditions_other" name="preferred_work_conditions_other"
                       value="{{ old('preferred_work_conditions_other') }}"
                       placeholder="Enter your specific work condition preferences">
            </div>
            <div class="col-12 col-md-6">
                <label for="assistive_devices" class="form-label">Assistive Devices Used</label>
                <select class="form-select" id="assistive_devices" name="assistive_devices">
                    <option value="">-- Select Assistive Device --</option>
                    @if(isset($assistiveDeviceOptions))
                        @foreach($assistiveDeviceOptions as $option)
                            <option value="{{ $option->name }}"
                                {{ old('assistive_devices', $pwdProfile->assistive_devices ?? '') == $option->name ? 'selected' : '' }}>
                                {{ $option->name }}
                            </option>
                        @endforeach
                    @endif
                    <option value="Others"
                        {{ old('assistive_devices', $pwdProfile->assistive_devices ?? '') == 'Others' ? 'selected' : '' }}>
                        Others (Please Specify)
                    </option>
                </select>
            </div>
            <div class="col-12 col-md-6" id="assistive_devices_other_container" style="display: none;">
                <label for="assistive_devices_other" class="form-label">Please Specify Your Assistive Device</label>
                <input type="text" class="form-control" id="assistive_devices_other" name="assistive_devices_other"
                       value="{{ old('assistive_devices_other') }}"
                       placeholder="Enter your assistive device">
            </div>
            <div class="col-12">
                <label for="accessibility_accommodations" class="form-label">Workplace Accessibility & Accommodations</label>
                <textarea class="form-control" id="accessibility_accommodations" name="accessibility_accommodations" rows="3"
                          placeholder="Describe the accessibility features and accommodations you need in the workplace">{{ old('accessibility_accommodations', $pwdProfile->accessibility_accommodations ?? '') }}</textarea>
            </div>
        </div>
    </div>
</div>

<!-- Emergency Contact Section -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white border-bottom py-3">
        <h5 class="mb-0 text-dark">
            <i class="fas fa-phone me-2 text-primary"></i>
            Emergency Contact
        </h5>
    </div>
    <div class="card-body p-3 p-md-4">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <label for="emergency_contact_name" class="form-label">Contact Name</label>
                <input type="text" class="form-control" id="emergency_contact_name" name="emergency_contact_name"
                       value="{{ old('emergency_contact_name', $pwdProfile->emergency_contact_name ?? '') }}"
                       placeholder="Enter contact person's name">
            </div>
            <div class="col-12 col-md-6">
                <label for="emergency_contact_relationship" class="form-label">Relationship</label>
                <input type="text" class="form-control" id="emergency_contact_relationship" name="emergency_contact_relationship"
                       value="{{ old('emergency_contact_relationship', $pwdProfile->emergency_contact_relationship ?? '') }}"
                       placeholder="e.g., Parent, Spouse, Sibling">
            </div>
            <div class="col-12 col-md-6">
                <label for="emergency_contact_phone" class="form-label">Contact Phone</label>
                <input type="text" class="form-control" id="emergency_contact_phone" name="emergency_contact_phone"
                       value="{{ old('emergency_contact_phone', $pwdProfile->emergency_contact_phone ?? '') }}"
                       placeholder="Enter contact phone number">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle skills "Others" option
    const skillsSelect = document.getElementById('skills');
    const skillsOtherContainer = document.getElementById('skills_other_container');

    if (skillsSelect && skillsOtherContainer) {
        skillsSelect.addEventListener('change', function() {
            skillsOtherContainer.style.display = this.value === 'Others' ? 'block' : 'none';
        });
        // Trigger on page load if "Others" is already selected
        if (skillsSelect.value === 'Others') {
            skillsOtherContainer.style.display = 'block';
        }
    }

    // Handle special needs "Others" option
    const specialNeedsSelect = document.getElementById('special_needs');
    const specialNeedsOtherContainer = document.getElementById('special_needs_other_container');

    if (specialNeedsSelect && specialNeedsOtherContainer) {
        specialNeedsSelect.addEventListener('change', function() {
            specialNeedsOtherContainer.style.display = this.value === 'Others' ? 'block' : 'none';
        });
        if (specialNeedsSelect.value === 'Others') {
            specialNeedsOtherContainer.style.display = 'block';
        }
    }

    // Handle assistive devices "Others" option
    const assistiveDevicesSelect = document.getElementById('assistive_devices');
    const assistiveDevicesOtherContainer = document.getElementById('assistive_devices_other_container');

    if (assistiveDevicesSelect && assistiveDevicesOtherContainer) {
        assistiveDevicesSelect.addEventListener('change', function() {
            assistiveDevicesOtherContainer.style.display = this.value === 'Others' ? 'block' : 'none';
        });
        if (assistiveDevicesSelect.value === 'Others') {
            assistiveDevicesOtherContainer.style.display = 'block';
        }
    }

    // Handle preferred work conditions "Others" option
    const workConditionsSelect = document.getElementById('preferred_work_conditions');
    const workConditionsOtherContainer = document.getElementById('preferred_work_conditions_other_container');

    if (workConditionsSelect && workConditionsOtherContainer) {
        workConditionsSelect.addEventListener('change', function() {
            workConditionsOtherContainer.style.display = this.value === 'Others' ? 'block' : 'none';
        });
        if (workConditionsSelect.value === 'Others') {
            workConditionsOtherContainer.style.display = 'block';
        }
    }
});
</script>
