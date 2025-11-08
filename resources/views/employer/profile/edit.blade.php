@extends('employer.layouts.employer')

@section('title', 'Edit Employer Profile')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Employer Profile</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('employer.profile.show') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Company Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('employer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">Personal Information</h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                    <div class="form-text">Contact support to change email address</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address *</label>
                                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                                           id="address" name="address" value="{{ old('address', $user->address) }}" required>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Information -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">Company Information</h6>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_name" class="form-label">Company Name *</label>
                                    <input type="text" class="form-control @error('company_name') is-invalid @enderror"
                                           id="company_name" name="company_name"
                                           value="{{ old('company_name', $user->company_name) }}" required>
                                    @error('company_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_size" class="form-label">Company Size *</label>
                                    <select class="form-control @error('company_size') is-invalid @enderror"
                                            id="company_size" name="company_size" required>
                                        <option value="">Select Company Size</option>
                                        <option value="1-10" {{ old('company_size', $user->company_size) == '1-10' ? 'selected' : '' }}>1-10 Employees</option>
                                        <option value="11-50" {{ old('company_size', $user->company_size) == '11-50' ? 'selected' : '' }}>11-50 Employees</option>
                                        <option value="51-200" {{ old('company_size', $user->company_size) == '51-200' ? 'selected' : '' }}>51-200 Employees</option>
                                        <option value="201-500" {{ old('company_size', $user->company_size) == '201-500' ? 'selected' : '' }}>201-500 Employees</option>
                                        <option value="501-1000" {{ old('company_size', $user->company_size) == '501-1000' ? 'selected' : '' }}>501-1000 Employees</option>
                                        <option value="1000+" {{ old('company_size', $user->company_size) == '1000+' ? 'selected' : '' }}>1000+ Employees</option>
                                    </select>
                                    @error('company_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="company_type" class="form-label">Company Type *</label>
                                    <select class="form-control @error('company_type') is-invalid @enderror"
                                            id="company_type" name="company_type" required>
                                        <option value="">Select Company Type</option>
                                        <option value="private" {{ old('company_type', $user->company_type) == 'private' ? 'selected' : '' }}>Private Company</option>
                                        <option value="public" {{ old('company_type', $user->company_type) == 'public' ? 'selected' : '' }}>Public Company</option>
                                        <option value="government" {{ old('company_type', $user->company_type) == 'government' ? 'selected' : '' }}>Government</option>
                                        <option value="nonprofit" {{ old('company_type', $user->company_type) == 'nonprofit' ? 'selected' : '' }}>Non-Profit</option>
                                        <option value="educational" {{ old('company_type', $user->company_type) == 'educational' ? 'selected' : '' }}>Educational Institution</option>
                                    </select>
                                    @error('company_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="website" class="form-label">Company Website *</label>
                                    <input type="url" class="form-control @error('website') is-invalid @enderror"
                                           id="website" name="website"
                                           value="{{ old('website', $user->website) }}"
                                           placeholder="https://example.com" required>
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Company Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="5"
                                      placeholder="Describe your company, mission, values, and what makes you a great place to work..." required>{{ old('description', $user->description) }}</textarea>
                            <div class="form-text">This description will be visible to job seekers. Minimum 100 characters.</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('employer.profile.show') }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Resume Section -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Company Resume/Profile</h5>
            </div>
            <div class="card-body">
                @if($user->hasResume())
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Resume uploaded successfully.
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>File Name:</strong> {{ $user->resume_file_name }}</p>
                            <p><strong>File Size:</strong> {{ $user->resume_file_size }}</p>
                            <p><strong>Uploaded:</strong> {{ $user->updated_at->format('F j, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6 text-end">
                            <a href="{{ $user->resume_url }}" target="_blank" class="btn btn-outline-primary">
                                <i class="fas fa-eye"></i> View Resume
                            </a>
                            <form action="{{ route('employer.profile.delete-resume') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger"
                                        onclick="return confirm('Are you sure you want to delete your resume?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No resume uploaded yet.
                    </div>
                @endif

                <form action="{{ route('employer.profile.upload-resume') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                    @csrf
                    <div class="mb-3">
                        <label for="resume" class="form-label">Upload Company Resume/Profile</label>
                        <input type="file" class="form-control @error('resume') is-invalid @enderror"
                               id="resume" name="resume" accept=".pdf,.doc,.docx">
                        <div class="form-text">Upload your company profile or resume (PDF, DOC, DOCX, max 5MB)</div>
                        @error('resume')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary" {{ $user->hasResume() ? 'disabled' : '' }}>
                        <i class="fas fa-upload"></i> Upload Resume
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Profile Completion -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Profile Completion</h6>
            </div>
            <div class="card-body">
                @php
                    $completion = $user->getEmployerProfileCompletion();
                    $missingFields = [];
                    $requiredFields = ['name', 'phone', 'address', 'company_name', 'company_size', 'company_type', 'website', 'description'];

                    foreach ($requiredFields as $field) {
                        if (empty($user->$field)) {
                            $missingFields[] = $field;
                        }
                    }
                @endphp

                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar {{ $completion == 100 ? 'bg-success' : 'bg-primary' }}"
                         role="progressbar" style="width: {{ $completion }}%;">
                        {{ $completion }}%
                    </div>
                </div>

                @if($completion < 100)
                    <div class="alert alert-warning">
                        <h6>Missing Information:</h6>
                        <ul class="mb-0">
                            @foreach($missingFields as $field)
                                <li>{{ ucfirst(str_replace('_', ' ', $field)) }}</li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Your profile is complete!
                    </div>
                @endif
            </div>
        </div>

        <!-- Verification Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Verification Status</h6>
            </div>
            <div class="card-body">
                @if($user->isEmployerVerified())
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Verified Employer
                    </div>
                    <p class="small text-muted">
                        Your account is verified and you can access all features.
                    </p>
                @elseif($user->isEmployerPendingVerification())
                    <div class="alert alert-info">
                        <i class="fas fa-clock"></i> Pending Verification
                    </div>
                    <p class="small text-muted">
                        Your verification request is under review.
                    </p>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> Not Verified
                    </div>
                    <p class="small text-muted">
                        Complete your profile to apply for verification.
                    </p>
                    @if($completion >= 70)
                        <a href="{{ route('employer.verification.apply') }}" class="btn btn-success btn-sm w-100">
                            Apply for Verification
                        </a>
                    @endif
                @endif
            </div>
        </div>

        <!-- Tips -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-lightbulb"></i> Profile Tips</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="fas fa-check text-info me-2"></i> Use a professional company description</li>
                    <li class="mb-2"><i class="fas fa-check text-info me-2"></i> Keep contact information up to date</li>
                    <li class="mb-2"><i class="fas fa-check text-info me-2"></i> Upload a detailed company resume</li>
                    <li class="mb-0"><i class="fas fa-check text-info me-2"></i> Complete all fields for better visibility</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
