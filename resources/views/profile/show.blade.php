@extends('layouts.app')

@section('title', 'My Profile - PWD System')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        My Profile
                    </h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- User Information -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-user-circle me-2 text-primary"></i>
                                Basic Information
                            </h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <p class="form-control-plaintext">{{ $user->name }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <p class="form-control-plaintext">{{ $user->email }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Phone Number</label>
                            <p class="form-control-plaintext">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Address</label>
                            <p class="form-control-plaintext">{{ $user->address ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">User Role</label>
                            <p class="form-control-plaintext">
                                <span class="badge {{ $user->role_badge_class }}">
                                    {{ $user->getRoleDisplayName() }}
                                </span>
                            </p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Profile Status</label>
                            <p class="form-control-plaintext">
                                @if($user->isProfileComplete())
                                    <span class="badge bg-success">Complete</span>
                                @else
                                    <span class="badge bg-warning">Incomplete</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- PWD Profile Information -->
                    @if($user->isPwd() && $pwdProfile)
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-wheelchair me-2 text-primary"></i>
                                PWD Information
                            </h5>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Disability Type</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->disability_type ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Disability Level</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->disability_severity ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Assistive Devices</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->assistive_devices ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Medical Conditions</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->special_needs ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Skills</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->skills ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Interests</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->qualifications ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">Accommodation Needs</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->accessibility_needs ?? 'Not provided' }}</p>
                        </div>

                        @if($pwdProfile->birthdate)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Birthdate</label>
                            <p class="form-control-plaintext">
                                {{ \Carbon\Carbon::parse($pwdProfile->birthdate)->format('F d, Y') }}
                            </p>
                        </div>
                        @endif

                        @if($pwdProfile->gender)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Gender</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->gender }}</p>
                        </div>
                        @endif

                        <!-- Emergency Contact -->
                        <div class="col-12 mt-3">
                            <h6 class="fw-bold border-bottom pb-2">
                                <i class="fas fa-phone-emergency me-2 text-danger"></i>
                                Emergency Contact
                            </h6>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Contact Name</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->emergency_contact_name ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Contact Phone</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->emergency_contact_phone ?? 'Not provided' }}</p>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Relationship</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->emergency_contact_relationship ?? 'Not provided' }}</p>
                        </div>

                        @if($pwdProfile->pwd_id_number)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">PWD ID Number</label>
                            <p class="form-control-plaintext">{{ $pwdProfile->pwd_id_number }}</p>
                        </div>
                        @endif

                        <!-- Profile Photos -->
                        @if($pwdProfile->profile_photo || $pwdProfile->pwd_id_photo)
                        <div class="col-12 mt-3">
                            <h6 class="fw-bold border-bottom pb-2">
                                <i class="fas fa-images me-2 text-info"></i>
                                Documents & Photos
                            </h6>
                        </div>

                        @if($pwdProfile->profile_photo)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Profile Photo</label>
                            <div>
                                <img src="{{ Storage::url($pwdProfile->profile_photo) }}"
                                     alt="Profile Photo"
                                     class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                        @endif

                        @if($pwdProfile->pwd_id_photo)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">PWD ID Photo</label>
                            <div>
                                <img src="{{ Storage::url($pwdProfile->pwd_id_photo) }}"
                                     alt="PWD ID Photo"
                                     class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                    @elseif($user->isPwd() && !$pwdProfile)
                    <!-- Prompt to complete PWD profile -->
                    <div class="alert alert-warning">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>PWD Profile Incomplete</strong>
                                <p class="mb-0">Please complete your PWD profile to access all features.</p>
                            </div>
                            <a href="{{ route('profile.pwd-complete-form') }}" class="btn btn-warning">
                                Complete Profile
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                                </a>
                                <div>
                                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-1"></i> Edit Profile
                                    </a>
                                    @if($user->isPwd() && !$user->isProfileComplete())
                                        <a href="{{ route('profile.pwd-complete-form') }}" class="btn btn-warning">
                                            <i class="fas fa-clipboard-check me-1"></i> Complete PWD Profile
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
