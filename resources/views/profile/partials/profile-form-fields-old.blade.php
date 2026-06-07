{{--
    Unified Profile Form Fields Partial
    This partial is included by profile-form.blade.php for both edit and complete modes.
--}}

@include('profile.pwd-complete')
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
