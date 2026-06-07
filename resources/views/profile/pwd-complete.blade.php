@extends('layouts.app')

@section('title', 'Complete PWD Profile - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-3 py-md-4">
        <div class="container">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-8">
                    <h1 class="h4 h3-md mb-2 text-dark">
                        <i class="fas fa-clipboard-check me-2 text-primary"></i>
                        Complete Your PWD Profile
                    </h1>
                    <p class="mb-0 text-muted small">Please provide your PWD information to access all features and opportunities.</p>
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

                <form method="POST" action="{{ route('profile.form.submit', ['mode' => 'complete']) }}" enctype="multipart/form-data" id="profileUpdateForm">
                    @csrf

                    <!-- Resume Upload Section -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-file-upload me-2 text-primary"></i>
                                Resume Upload
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="resume" class="form-label">Upload Resume <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('resume') is-invalid @enderror" id="resume" name="resume" accept=".pdf,.doc,.docx,.txt" required>
                                @error('resume')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Accepted formats: PDF, DOC, DOCX, TXT. Max size: 5MB</div>
                            </div>
                        </div>
                    </div>

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
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="nationality" class="form-label">Nationality <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" value="{{ old('nationality', $pwdProfile->nationality ?? '') }}">
                                    @error('nationality')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender (optional)</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
                                        <option value="">Select</option>
                                        <option value="Male" {{ old('gender', $pwdProfile->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender', $pwdProfile->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender', $pwdProfile->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="date_of_birth" class="form-label">Date of Birth <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $pwdProfile->date_of_birth ?? '') }}">
                                    @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_number') is-invalid @enderror" id="contact_number" name="contact_number" value="{{ old('contact_number', $pwdProfile->contact_number ?? '') }}">
                                    @error('contact_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label for="complete_address" class="form-label">Complete Address <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('complete_address') is-invalid @enderror" id="complete_address" name="complete_address" rows="3">{{ old('complete_address', $pwdProfile->complete_address ?? '') }}</textarea>
                                    @error('complete_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Educational Background Section -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                Educational Background
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="highest_education" class="form-label">Highest Education Level</label>
                                    <label for="highest_education" class="form-label">Highest Education Level <span class="text-danger">*</span></label>
                                    <select class="form-select @error('highest_education') is-invalid @enderror" id="highest_education" name="highest_education">
                                        <option value="">Select</option>
                                        <option value="Elementary" {{ old('highest_education', $pwdProfile->highest_education ?? '') == 'Elementary' ? 'selected' : '' }}>Elementary</option>
                                        <option value="High School" {{ old('highest_education', $pwdProfile->highest_education ?? '') == 'High School' ? 'selected' : '' }}>High School</option>
                                        <option value="Vocational" {{ old('highest_education', $pwdProfile->highest_education ?? '') == 'Vocational' ? 'selected' : '' }}>Vocational</option>
                                        <option value="College" {{ old('highest_education', $pwdProfile->highest_education ?? '') == 'College' ? 'selected' : '' }}>College</option>
                                        <option value="Bachelor's Degree" {{ old('highest_education', $pwdProfile->highest_education ?? '') == 'Bachelor\'s Degree' ? 'selected' : '' }}>Bachelor's Degree</option>
                                        <option value="Master's Degree" {{ old('highest_education', $pwdProfile->highest_education ?? '') == 'Master\'s Degree' ? 'selected' : '' }}>Master's Degree</option>
                                        <option value="Doctorate" {{ old('highest_education', $pwdProfile->highest_education ?? '') == 'Doctorate' ? 'selected' : '' }}>Doctorate</option>
                                    </select>
                                    @error('highest_education')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="school_name" class="form-label">Name of School/University</label>
                                    <label for="school_name" class="form-label">Name of School/University <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('school_name') is-invalid @enderror" id="school_name" name="school_name" value="{{ old('school_name', $pwdProfile->school_name ?? '') }}">
                                    @error('school_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Experience Section -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-briefcase me-2 text-primary"></i>
                                Work Experience
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="work-experience-list">
                                <!-- Existing work experiences will be loaded here -->
                                @if(isset($pwdProfile->work_experiences) && count($pwdProfile->work_experiences) > 0)
                                    @foreach($pwdProfile->work_experiences as $index => $experience)
                                    <div class="border rounded p-3 mb-3 position-relative work-experience-entry">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-work-experience" aria-label="Remove"></button>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Job Title</label>
                                                <input type="text" class="form-control" name="work_experience[{{ $index }}][job_title]" value="{{ $experience->job_title }}" placeholder="Enter job title">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Company Name</label>
                                                <input type="text" class="form-control" name="work_experience[{{ $index }}][company_name]" value="{{ $experience->company_name }}" placeholder="Enter company name">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary" id="addWorkExperienceBtn">
                                <i class="fas fa-plus me-2"></i> Add Work Experience
                            </button>
                        </div>
                    </div>

                    <!-- Certifications & Trainings Section -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-certificate me-2 text-primary"></i>
                                Certifications & Trainings
                            </h5>
                        </div>
                        <div class="card-body">
                            <div id="certifications-list">
                                <!-- Existing certifications will be loaded here -->
                                @if(isset($pwdProfile->certifications) && count($pwdProfile->certifications) > 0)
                                    @foreach($pwdProfile->certifications as $index => $certification)
                                    <div class="border rounded p-3 mb-3 position-relative certification-entry">
                                        <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-certification" aria-label="Remove"></button>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Certification/Training Name</label>
                                                <input type="text" class="form-control" name="certifications[{{ $index }}][name]" value="{{ $certification->name }}" placeholder="Enter certification name">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Issuing Organization</label>
                                                <input type="text" class="form-control" name="certifications[{{ $index }}][organization]" value="{{ $certification->organization }}" placeholder="Enter organization">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Date Obtained</label>
                                                <input type="date" class="form-control" name="certifications[{{ $index }}][date_obtained]" value="{{ $certification->date_obtained }}">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-outline-primary" id="addCertificationBtn">
                                <i class="fas fa-plus me-2"></i> Add Certification/Training
                            </button>
                        </div>
                    </div>

                    <!-- PWD/Disability-Related Information Section -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-wheelchair me-2 text-primary"></i>
                                Disability-Related Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- Type and Severity -->
                                <div class="col-md-6">
                                    <label for="disability_type_id" class="form-label">Type of Disability <span class="text-danger">*</span></label>
                                    <select class="form-select @error('disability_type_id') is-invalid @enderror" id="disability_type_id" name="disability_type_id">
                                        <option value="">Select</option>
                                        @foreach($disabilityTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('disability_type_id', $pwdProfile->disability_type_id ?? '') == $type->id ? 'selected' : '' }}>{{ $type->type }}</option>
                                        @endforeach
                                    </select>
                                    @error('disability_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="disability_severity" class="form-label">Disability Severity <span class="text-danger">*</span></label>
                                    <select class="form-select @error('disability_severity') is-invalid @enderror" id="disability_severity" name="disability_severity" required>
                                        <option value="">Select Severity</option>
                                        <option value="Mild" {{ old('disability_severity', $pwdProfile->disability_severity ?? '') == 'Mild' ? 'selected' : '' }}>Mild</option>
                                        <option value="Moderate" {{ old('disability_severity', $pwdProfile->disability_severity ?? '') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                        <option value="Severe" {{ old('disability_severity', $pwdProfile->disability_severity ?? '') == 'Severe' ? 'selected' : '' }}>Severe</option>
                                        <option value="Profound" {{ old('disability_severity', $pwdProfile->disability_severity ?? '') == 'Profound' ? 'selected' : '' }}>Profound</option>
                                    </select>
                                    @error('disability_severity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- PWD ID Number and Photo -->
                                <div class="col-md-6">
                                    <label for="pwd_id_number" class="form-label">PWD ID Number</label>
                                    <input type="text" class="form-control @error('pwd_id_number') is-invalid @enderror" id="pwd_id_number" name="pwd_id_number" value="{{ old('pwd_id_number', $pwdProfile->pwd_id_number ?? '') }}">
                                    @error('pwd_id_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
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

                                <!-- Assistive Devices -->
                                <div class="col-md-6">
                                    <label for="assistive_devices" class="form-label">Assistive Devices Needed</label>
                                    <select class="form-select @error('assistive_devices') is-invalid @enderror" id="assistive_devices" name="assistive_devices">
                                        <option value="">-- Select Device --</option>
                                        @foreach($assistiveDeviceOptions as $option)
                                            <option value="{{ $option->name }}" {{ old('assistive_devices', $pwdProfile->assistive_devices ?? '') == $option->name ? 'selected' : '' }}>{{ $option->name }}</option>
                                        @endforeach
                                        <option value="Others" {{ old('assistive_devices', $pwdProfile->assistive_devices ?? '') == 'Others' ? 'selected' : '' }}>Others (Please Specify)</option>
                                    </select>
                                    @error('assistive_devices')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" id="assistive_devices_other_container" style="display: none;">
                                    <label for="assistive_devices_other" class="form-label">Please Specify Device</label>
                                    <input type="text" class="form-control" id="assistive_devices_other" name="assistive_devices_other" value="{{ old('assistive_devices_other', (old('assistive_devices', $pwdProfile->assistive_devices ?? '') == 'Others') ? $pwdProfile->assistive_devices : '') }}" placeholder="Enter your device...">
                                </div>

                                <!-- Preferred Work Conditions -->
                                <div class="col-md-6">
                                    <label for="preferred_work_conditions" class="form-label">Preferred Work Conditions</label>
                                    <select class="form-select @error('preferred_work_conditions') is-invalid @enderror" id="preferred_work_conditions" name="preferred_work_conditions">
                                        <option value="">-- Select Work Arrangement --</option>
                                        @foreach($workArrangementOptions as $option)
                                            <option value="{{ $option->name }}" {{ old('preferred_work_conditions', $pwdProfile->preferred_work_conditions ?? '') == $option->name ? 'selected' : '' }}>{{ $option->name }}</option>
                                        @endforeach
                                        <option value="Others" {{ old('preferred_work_conditions', $pwdProfile->preferred_work_conditions ?? '') == 'Others' ? 'selected' : '' }}>Others (Please Specify)</option>
                                    </select>
                                    @error('preferred_work_conditions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6" id="preferred_work_conditions_other_container" style="display: none;">
                                    <label for="preferred_work_conditions_other" class="form-label">Please Specify Condition</label>
                                    <input type="text" class="form-control" id="preferred_work_conditions_other" name="preferred_work_conditions_other" value="{{ old('preferred_work_conditions_other', (old('preferred_work_conditions', $pwdProfile->preferred_work_conditions ?? '') == 'Others') ? $pwdProfile->preferred_work_conditions : '') }}" placeholder="Enter your preferred work condition...">
                                </div>

                                <!-- Limitations -->
                                <div class="col-md-12">
                                    <label for="limitations" class="form-label">Limitations Employers Should Know</label>
                                    <textarea class="form-control @error('limitations') is-invalid @enderror" id="limitations" name="limitations" rows="3" placeholder="Describe any limitations employers should know">{{ old('limitations', $pwdProfile->limitations ?? '') }}</textarea>
                                    @error('limitations')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- Accessibility Accommodations -->
                                <div class="col-md-12">
                                    <label for="accessibility_accommodations" class="form-label">Accessibility Accommodations You Need</label>
                                    <select class="form-select @error('accessibility_accommodations') is-invalid @enderror" id="accessibility_accommodations" name="accessibility_accommodations">
                                        <option value="">-- Select Accommodation --</option>
                                        @foreach($accommodationOptions as $option)
                                            <option value="{{ $option->name }}" {{ old('accessibility_accommodations', $pwdProfile->accessibility_accommodations ?? '') == $option->name ? 'selected' : '' }}>{{ $option->name }}</option>
                                        @endforeach
                                        <option value="Others" {{ old('accessibility_accommodations', $pwdProfile->accessibility_accommodations ?? '') == 'Others' ? 'selected' : '' }}>Others (Please Specify)</option>
                                    </select>
                                    @error('accessibility_accommodations')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-12" id="accessibility_accommodations_other_container" style="display: none;">
                                    <label for="accessibility_accommodations_other" class="form-label">Please Specify Accommodation</label>
                                    <input type="text" class="form-control" id="accessibility_accommodations_other" name="accessibility_accommodations_other" value="{{ old('accessibility_accommodations_other', (old('accessibility_accommodations', $pwdProfile->accessibility_accommodations ?? '') == 'Others') ? $pwdProfile->accessibility_accommodations : '') }}" placeholder="Enter your accommodation...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Job Preferences Section -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0 text-dark">
                                <i class="fas fa-clipboard-list me-2 text-primary"></i>
                                Job Preferences
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="emergency_contact_name" class="form-label">Emergency Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('emergency_contact_name') is-invalid @enderror" id="emergency_contact_name" name="emergency_contact_name" value="{{ old('emergency_contact_name', $pwdProfile->emergency_contact_name ?? '') }}" required>
                                    @error('emergency_contact_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="emergency_contact_phone" class="form-label">Emergency Contact Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('emergency_contact_phone') is-invalid @enderror" id="emergency_contact_phone" name="emergency_contact_phone" value="{{ old('emergency_contact_phone', $pwdProfile->emergency_contact_phone ?? '') }}" required>
                                    @error('emergency_contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="emergency_contact_relationship" class="form-label">Relationship <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('emergency_contact_relationship') is-invalid @enderror" id="emergency_contact_relationship" name="emergency_contact_relationship" value="{{ old('emergency_contact_relationship', $pwdProfile->emergency_contact_relationship ?? '') }}" required>
                                    @error('emergency_contact_relationship')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="desired_position" class="form-label">Desired Job Position</label>
                                    <input type="text" class="form-control @error('desired_position') is-invalid @enderror" id="desired_position" name="desired_position" value="{{ old('desired_position', $pwdProfile->desired_position ?? '') }}">
                                    @error('desired_position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="employment_type" class="form-label">Type of Employment</label>
                                    <select class="form-select @error('employment_type') is-invalid @enderror" id="employment_type" name="employment_type">
                                        <option value="">Select</option>
                                        <option value="Full-time" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                        <option value="Part-time" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                        <option value="Contract" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="Freelance" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Freelance' ? 'selected' : '' }}>Freelance</option>
                                        <option value="Internship" {{ old('employment_type', $pwdProfile->employment_type ?? '') == 'Internship' ? 'selected' : '' }}>Internship</option>
                                    </select>
                                    @error('employment_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

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
                                        <span class="d-none d-sm-inline">Complete Profile</span>
                                        <span class="d-inline d-sm-none">Complete</span>
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
// --- Begin: Synced JS from edit.blade.php for reset and submit button logic ---
document.addEventListener('DOMContentLoaded', function() {
    // Work Experience dynamic fields with file upload
    const workExperienceList = document.getElementById('work-experience-list');
    const addWorkExperienceBtn = document.getElementById('addWorkExperienceBtn');
    let workExperienceIndex = {{ isset($pwdProfile->work_experiences) ? count($pwdProfile->work_experiences) : 0 }};

    function createWorkExperienceFields(index) {
        return `
        <div class="border rounded p-3 mb-3 position-relative work-experience-entry">
            <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-work-experience" aria-label="Remove"></button>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Job Title</label>
                    <input type="text" class="form-control" name="work_experience[${index}][job_title]" placeholder="Enter job title">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Company Name</label>
                    <input type="text" class="form-control" name="work_experience[${index}][company_name]" placeholder="Enter company name">
                </div>
            </div>
        </div>
        `;
    }

    if (addWorkExperienceBtn && workExperienceList) {
        addWorkExperienceBtn.addEventListener('click', function() {
            workExperienceList.insertAdjacentHTML('beforeend', createWorkExperienceFields(workExperienceIndex));
            workExperienceIndex++;
        });

        // Remove work experience entry
        workExperienceList.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-work-experience')) {
                e.target.closest('.work-experience-entry').remove();
            }
        });
    }

    // Certifications dynamic fields
    const certificationsList = document.getElementById('certifications-list');
    const addCertificationBtn = document.getElementById('addCertificationBtn');
    let certificationIndex = {{ isset($pwdProfile->certifications) ? count($pwdProfile->certifications) : 0 }};

    function createCertificationFields(index) {
        return `
        <div class="border rounded p-3 mb-3 position-relative certification-entry">
            <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-2 remove-certification" aria-label="Remove"></button>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Certification/Training Name</label>
                    <input type="text" class="form-control" name="certifications[${index}][name]" placeholder="Enter certification name">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Issuing Organization</label>
                    <input type="text" class="form-control" name="certifications[${index}][organization]" placeholder="Enter organization">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Date Obtained</label>
                    <input type="date" class="form-control" name="certifications[${index}][date_obtained]">
                </div>
            </div>
        </div>
        `;
    }

    if (addCertificationBtn && certificationsList) {
        addCertificationBtn.addEventListener('click', function() {
            certificationsList.insertAdjacentHTML('beforeend', createCertificationFields(certificationIndex));
            certificationIndex++;
        });

        // Remove certification entry
        certificationsList.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-certification')) {
                e.target.closest('.certification-entry').remove();
            }
        });
    }

    // Handle "Others" option display for various fields
    function handleOthersOption(selectId, containerId, otherFieldId) {
        const select = document.getElementById(selectId);
        const container = document.getElementById(containerId);
        const otherField = document.getElementById(otherFieldId);

        if (select && container) {
            // Initial state
            if (select.value === 'Others') {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }

            // Change event
            select.addEventListener('change', function() {
                if (this.value === 'Others') {
                    container.style.display = 'block';
                    if (otherField) otherField.focus();
                } else {
                    container.style.display = 'none';
                }
            });
        }
    }

    // Initialize handlers for all "Others" options
    handleOthersOption('assistive_devices', 'assistive_devices_other_container', 'assistive_devices_other');
    handleOthersOption('preferred_work_conditions', 'preferred_work_conditions_other_container', 'preferred_work_conditions_other');
    handleOthersOption('accessibility_accommodations', 'accessibility_accommodations_other_container', 'accessibility_accommodations_other');

    // Form submission handling
    const form = document.getElementById('profileUpdateForm');
    if (form) {
        form.addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
            }
        });
    }

    // ...existing code for other fields...
});
// --- End: Synced JS ---
</script>
@endsection
