@extends('employer.layouts.employer')

@section('title', 'Apply for Employer Verification')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Apply for Employer Verification</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('employer.verification.requirements') }}" class="btn btn-outline-info">
            <i class="fas fa-list-alt"></i> View Requirements
        </a>
    </div>
</div>

<!-- Profile Completion Alert -->
@if($profileCompletion < 70)
<div class="alert alert-warning">
    <h5><i class="fas fa-exclamation-triangle"></i> Profile Incomplete</h5>
    <p>Your profile is only {{ $profileCompletion }}% complete. Please complete your profile before applying for verification.</p>
    <a href="{{ route('employer.profile.edit') }}" class="btn btn-warning">Complete Profile</a>
</div>
@endif

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Verification Application Form</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('employer.verification.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf

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
                                      id="description" name="description" rows="4"
                                      placeholder="Describe your company, mission, and values..." required>{{ old('description', $user->description) }}</textarea>
                            <div class="form-text">Minimum 100 characters. Describe your company's background and operations.</div>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Document Upload -->
                    <div class="mb-4">
                        <h6 class="border-bottom pb-2">Required Documents</h6>

                        <div class="mb-3">
                            <label for="business_registration" class="form-label">Business Registration Certificate *</label>
                            <input type="file" class="form-control @error('business_registration') is-invalid @enderror"
                                   id="business_registration" name="business_registration"
                                   accept=".pdf,.jpg,.png" required>
                            <div class="form-text">Upload your business registration certificate (PDF, JPG, PNG, max 5MB)</div>
                            @error('business_registration')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tax_clearance" class="form-label">Tax Clearance Certificate</label>
                            <input type="file" class="form-control @error('tax_clearance') is-invalid @enderror"
                                   id="tax_clearance" name="tax_clearance"
                                   accept=".pdf,.jpg,.png">
                            <div class="form-text">Upload your tax clearance certificate (optional but recommended)</div>
                            @error('tax_clearance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="additional_documents" class="form-label">Additional Supporting Documents</label>
                            <input type="file" class="form-control @error('additional_documents') is-invalid @enderror"
                                   id="additional_documents" name="additional_documents[]"
                                   accept=".pdf,.jpg,.png" multiple>
                            <div class="form-text">Upload any additional documents that support your verification (multiple files allowed)</div>
                            @error('additional_documents')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @error('additional_documents.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input @error('agree_terms') is-invalid @enderror"
                                   type="checkbox" id="agree_terms" name="agree_terms"
                                   {{ old('agree_terms') ? 'checked' : '' }} required>
                            <label class="form-check-label" for="agree_terms">
                                I agree to the <a href="{{ route('terms') }}" target="_blank">Terms and Conditions</a>
                                and confirm that all information provided is accurate and truthful.
                            </label>
                            @error('agree_terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="{{ route('employer.dashboard') }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary"
                                {{ $profileCompletion < 70 ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar Information -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Verification Process</h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Submit Application</h6>
                            <p class="text-muted mb-0">Complete this form with accurate information</p>
                        </div>
                    </div>
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-secondary"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Document Review</h6>
                            <p class="text-muted mb-0">Our team reviews your documents (1-2 business days)</p>
                        </div>
                    </div>
                    <div class="timeline-item mb-3">
                        <div class="timeline-marker bg-success"></div>
                        <div class="timeline-content">
                            <h6 class="mb-1">Verification Complete</h6>
                            <p class="text-muted mb-0">Start posting jobs and accessing all features</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-question-circle"></i> Need Help?</h6>
            </div>
            <div class="card-body">
                <p>If you need assistance with the verification process:</p>
                <ul class="list-unstyled">
                    <li><i class="fas fa-envelope text-info me-2"></i> Email: support@example.com</li>
                    <li><i class="fas fa-phone text-info me-2"></i> Phone: +1 (555) 123-4567</li>
                </ul>
                <a href="{{ route('contact') }}" class="btn btn-outline-info btn-sm">Contact Support</a>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}
.timeline-item {
    position: relative;
}
.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.5rem;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
}
.timeline-content {
    margin-left: 0;
}
</style>
@endsection
