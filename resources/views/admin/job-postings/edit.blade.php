@extends('layouts.admin')

@section('title', 'Edit Job Posting - Admin Panel')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit"></i> Edit Job Posting
        </h1>
        <div>
            <a href="{{ route('job-postings.show', $jobPosting->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> View
            </a>
            <a href="{{ route('job-postings.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Edit Job Posting: {{ $jobPosting->title }}</h6>
                    <div class="btn-group">
                        <form action="{{ route('job-postings.duplicate', $jobPosting->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary btn-sm"
                                    onclick="return confirm('Duplicate this job posting?')">
                                <i class="fas fa-copy"></i> Duplicate
                            </button>
                        </form>
                        @if($jobPosting->application_deadline && $jobPosting->application_deadline->isFuture())
                        <button type="button" class="btn btn-outline-warning btn-sm" data-toggle="modal" data-target="#extendDeadlineModal">
                            <i class="fas fa-calendar-plus"></i> Extend Deadline
                        </button>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('job-postings.update', $jobPosting->id) }}" id="jobPostingForm">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="font-weight-bold">Job Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title', $jobPosting->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="company" class="font-weight-bold">Company *</label>
                                    <input type="text" class="form-control @error('company') is-invalid @enderror"
                                           id="company" name="company" value="{{ old('company', $jobPosting->company) }}" required>
                                    @error('company')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="location" class="font-weight-bold">Location *</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                           id="location" name="location" value="{{ old('location', $jobPosting->location) }}" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="employment_type" class="font-weight-bold">Employment Type *</label>
                                    <select class="form-control @error('employment_type') is-invalid @enderror"
                                            id="employment_type" name="employment_type" required>
                                        <option value="">Select Employment Type</option>
                                        @foreach($employmentTypes as $key => $value)
                                            <option value="{{ $key }}" {{ old('employment_type', $jobPosting->employment_type) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="job_category" class="font-weight-bold">Job Category</label>
                                    <select class="form-control @error('job_category') is-invalid @enderror"
                                            id="job_category" name="job_category">
                                        <option value="">Select Category</option>
                                        @foreach($jobCategories as $key => $value)
                                            <option value="{{ $key }}" {{ old('job_category', $jobPosting->job_category) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('job_category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="experience_level" class="font-weight-bold">Experience Level</label>
                                    <select class="form-control @error('experience_level') is-invalid @enderror"
                                            id="experience_level" name="experience_level">
                                        <option value="">Select Experience Level</option>
                                        @foreach($experienceLevels as $key => $value)
                                            <option value="{{ $key }}" {{ old('experience_level', $jobPosting->experience_level) == $key ? 'selected' : '' }}>
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('experience_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="salary_min" class="font-weight-bold">Minimum Salary (₱)</label>
                                            <input type="number" class="form-control @error('salary_min') is-invalid @enderror"
                                                   id="salary_min" name="salary_min" value="{{ old('salary_min', $jobPosting->salary_min) }}"
                                                   min="0" step="0.01">
                                            @error('salary_min')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="salary_max" class="font-weight-bold">Maximum Salary (₱)</label>
                                            <input type="number" class="form-control @error('salary_max') is-invalid @enderror"
                                                   id="salary_max" name="salary_max" value="{{ old('salary_max', $jobPosting->salary_max) }}"
                                                   min="0" step="0.01">
                                            @error('salary_max')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="application_deadline" class="font-weight-bold">Application Deadline</label>
                                    <input type="date" class="form-control @error('application_deadline') is-invalid @enderror"
                                           id="application_deadline" name="application_deadline"
                                           value="{{ old('application_deadline', $jobPosting->application_deadline ? $jobPosting->application_deadline->format('Y-m-d') : '') }}"
                                           min="{{ date('Y-m-d') }}">
                                    @error('application_deadline')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty for no deadline</small>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_email" class="font-weight-bold">Contact Email</label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                                           id="contact_email" name="contact_email" value="{{ old('contact_email', $jobPosting->contact_email) }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone" class="font-weight-bold">Contact Phone</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror"
                                           id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $jobPosting->contact_phone) }}">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="form-group mt-3">
                            <label for="description" class="font-weight-bold">Job Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="6" required>{{ old('description', $jobPosting->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Describe the role, responsibilities, and expectations (minimum 50 characters)</small>
                        </div>

                        <!-- Requirements -->
                        <div class="form-group mt-3">
                            <label for="requirements" class="font-weight-bold">Requirements *</label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror"
                                      id="requirements" name="requirements" rows="6" required>{{ old('requirements', $jobPosting->requirements) }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">List the qualifications, skills, and experience required (minimum 50 characters)</small>
                        </div>

                        <!-- Accessibility / PWD Fields -->
                        <div class="card mt-3 p-3 border">
                            <h5 class="mb-2">Accessibility & PWD Options</h5>

                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="is_remote" name="is_remote" value="1" {{ old('is_remote', $jobPosting->is_remote) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_remote">Is this role remote?</label>
                            </div>

                            <div class="form-check mb-2">
                                <input type="checkbox" class="form-check-input" id="provides_accommodations" name="provides_accommodations" value="1" {{ old('provides_accommodations', $jobPosting->provides_accommodations) ? 'checked' : '' }}>
                                <label class="form-check-label" for="provides_accommodations">Provides accommodations / accessibility</label>
                            </div>

                            <div class="form-group mt-2">
                                <label for="accessibility_features" class="font-weight-bold">Accessibility features (optional)</label>
                                <textarea id="accessibility_features" name="accessibility_features" class="form-control" rows="3">{{ old('accessibility_features', $jobPosting->accessibility_features) }}</textarea>
                                <small class="form-text text-muted">e.g. wheelchair access, screen reader compatible documentation, flexible hours</small>
                            </div>

                            <div class="form-group mt-2">
                                <label for="assistive_technology" class="font-weight-bold">Assistive technology (optional)</label>
                                <input type="text" id="assistive_technology" name="assistive_technology" class="form-control" value="{{ old('assistive_technology', $jobPosting->assistive_technology) }}" placeholder="e.g. JAWS, NVDA, speech-to-text tools">
                            </div>

                            <div class="form-group mt-2">
                                <label class="font-weight-bold">Suitable disability types (optional)</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($disabilityTypes as $dt)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="disability_type_ids[]" value="{{ $dt->id }}" id="dt-{{ $dt->id }}"
                                                {{ in_array($dt->id, old('disability_type_ids', $jobPosting->suitableDisabilityTypes->pluck('id')->toArray())) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="dt-{{ $dt->id }}">{{ $dt->type }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Status and Actions -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                           {{ old('is_active', $jobPosting->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label font-weight-bold" for="is_active">
                                        Active (Visible to users)
                                    </label>
                                </div>

                                <!-- Job Statistics -->
                                <div class="mt-3 p-3 bg-light rounded">
                                    <h6 class="font-weight-bold">Job Statistics</h6>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="text-primary font-weight-bold">{{ $jobPosting->views }}</div>
                                            <small class="text-muted">Views</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-success font-weight-bold">{{ $jobPosting->applications->count() }}</div>
                                            <small class="text-muted">Applications</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="text-info font-weight-bold">
                                                @if($jobPosting->application_deadline)
                                                    {{ $jobPosting->application_deadline->diffForHumans() }}
                                                @else
                                                    No deadline
                                                @endif
                                            </div>
                                            <small class="text-muted">Deadline</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save"></i> Update Job Posting
                                </button>
                                <a href="{{ route('admin.job-postings.index') }}" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Extend Deadline Modal -->
@if($jobPosting->application_deadline && $jobPosting->application_deadline->isFuture())
<div class="modal fade" id="extendDeadlineModal" tabindex="-1" role="dialog" aria-labelledby="extendDeadlineModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="extendDeadlineModalLabel">Extend Application Deadline</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.job-postings.extend-deadline', $jobPosting->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="new_deadline">New Deadline</label>
                        <input type="date" class="form-control" id="new_deadline" name="new_deadline"
                               value="{{ $jobPosting->application_deadline->format('Y-m-d') }}"
                               min="{{ date('Y-m-d') }}" required>
                        <small class="form-text text-muted">Current deadline: {{ $jobPosting->application_deadline->format('F j, Y') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Extend Deadline</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character count validation
    const description = document.getElementById('description');
    const requirements = document.getElementById('requirements');

    function validateMinLength(textarea, minLength) {
        const value = textarea.value.trim();
        if (value.length < minLength && value.length > 0) {
            textarea.classList.add('is-invalid');
        } else {
            textarea.classList.remove('is-invalid');
        }
    }

    description.addEventListener('blur', () => validateMinLength(description, 50));
    requirements.addEventListener('blur', () => validateMinLength(requirements, 50));

    // Salary validation
    const salaryMin = document.getElementById('salary_min');
    const salaryMax = document.getElementById('salary_max');

    function validateSalary() {
        const min = parseFloat(salaryMin.value) || 0;
        const max = parseFloat(salaryMax.value) || 0;

        if (max > 0 && min > max) {
            salaryMax.classList.add('is-invalid');
        } else {
            salaryMax.classList.remove('is-invalid');
        }
    }

    salaryMin.addEventListener('blur', validateSalary);
    salaryMax.addEventListener('blur', validateSalary);

    // Form submission validation
    document.getElementById('jobPostingForm').addEventListener('submit', function(e) {
        let valid = true;

        // Validate description length
        if (description.value.trim().length < 50) {
            description.classList.add('is-invalid');
            valid = false;
        }

        // Validate requirements length
        if (requirements.value.trim().length < 50) {
            requirements.classList.add('is-invalid');
            valid = false;
        }

        // Validate salary range
        const min = parseFloat(salaryMin.value) || 0;
        const max = parseFloat(salaryMax.value) || 0;
        if (max > 0 && min > max) {
            salaryMax.classList.add('is-invalid');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            alert('Please fix the validation errors before submitting.');
        }
    });
});
</script>
@endsection
