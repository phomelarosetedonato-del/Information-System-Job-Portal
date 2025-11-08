@extends('layouts.app')

@section('title', 'Available Jobs - PWD System')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-briefcase"></i>
                        Available Job Opportunities
                    </h4>
                </div>
                <div class="card-body">
                    {{-- Filter form for public job listings --}}
                    <form id="public-filters-form" method="GET" action="{{ route('job-postings.public') }}" class="mb-4" aria-label="Filter job postings">
                        <div class="row g-3 align-items-end">
                            {{-- Search Filter --}}
                            <div class="col-md-3">
                                <label class="form-label small mb-1 fw-semibold" for="q">
                                    <i class="fas fa-search me-1"></i>Search Jobs
                                </label>
                                <input type="text" id="q" name="q" class="form-control form-control-sm"
                                       placeholder="Job title, company, location..."
                                       value="{{ request('q') }}">
                            </div>

                            {{-- Disability Type Filter - Single Select Dropdown --}}
                            <div class="col-md-3">
                                <label class="form-label small mb-1 fw-semibold" for="disability_type_id">
                                    <i class="fas fa-wheelchair me-1"></i>Disability Type
                                </label>
                                <select id="disability_type_id" name="disability_type_id" class="form-select form-select-sm">
                                    <option value="">Any disability type</option>
                                    @foreach($disabilityTypes ?? [] as $dt)
                                        @php $label = trim($dt->type ?? ''); @endphp
                                        @if($label !== '')
                                            <option value="{{ $dt->id }}" {{ request('disability_type_id') == $dt->id ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            {{-- Job Type Filter - Single Select Dropdown --}}
                            <div class="col-md-3">
                                <label class="form-label small mb-1 fw-semibold" for="employment_type">
                                    <i class="fas fa-clock me-1"></i>Job Type
                                </label>
                                <select id="employment_type" name="employment_type" class="form-select form-select-sm">
                                    <option value="">Any type</option>
                                    <option value="Full-time" {{ request('employment_type') == 'Full-time' ? 'selected' : '' }}>
                                        Full-time
                                    </option>
                                    <option value="Part-time" {{ request('employment_type') == 'Part-time' ? 'selected' : '' }}>
                                        Part-time
                                    </option>
                                    <option value="Contract" {{ request('employment_type') == 'Contract' ? 'selected' : '' }}>
                                        Contract
                                    </option>
                                    <option value="Temporary" {{ request('employment_type') == 'Temporary' ? 'selected' : '' }}>
                                        Temporary
                                    </option>
                                    <option value="Internship" {{ request('employment_type') == 'Internship' ? 'selected' : '' }}>
                                        Internship
                                    </option>
                                    <option value="Freelance" {{ request('employment_type') == 'Freelance' ? 'selected' : '' }}>
                                        Freelance
                                    </option>
                                </select>
                            </div>

                            {{-- Location Filter --}}
                            <div class="col-md-3">
                                <label class="form-label small mb-1 fw-semibold" for="location">
                                    <i class="fas fa-map-marker-alt me-1"></i>Location
                                </label>
                                <input type="text" id="location" name="location" class="form-control form-control-sm"
                                       placeholder="Enter city or region" value="{{ request('location') }}">
                            </div>
                        </div>

                        {{-- Second Row Filters --}}
                        <div class="row g-3 align-items-end mt-2">
                            {{-- Sorting --}}
                            <div class="col-md-3">
                                <label class="form-label small mb-1 fw-semibold" for="sort_by">
                                    <i class="fas fa-sort me-1"></i>Sort By
                                </label>
                                <select id="sort_by" name="sort_by" class="form-select form-select-sm">
                                    <option value="newest" {{ request('sort_by', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="deadline" {{ request('sort_by') == 'deadline' ? 'selected' : '' }}>Application Deadline</option>
                                </select>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="col-md-6">
                                <div class="d-flex gap-2 mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-filter me-1"></i>Apply Filters
                                    </button>
                                    <a href="{{ route('job-postings.public') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-refresh me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Active Filters Display --}}
                        @php
                            $activeFilters = array_filter([
                                'Search' => !empty(request('q')) ? '"' . request('q') . '"' : null,
                                'Disability Type' => !empty(request('disability_type_id')) ?
                                    ($disabilityTypes->firstWhere('id', request('disability_type_id'))->type ?? 'Selected') : null,
                                'Job Type' => !empty(request('employment_type')) ? request('employment_type') : null,
                                'Location' => !empty(request('location')) ? request('location') : null,
                                'Sort By' => !empty(request('sort_by')) ? ucfirst(request('sort_by')) : null,
                            ]);
                        @endphp

                        @if(!empty($activeFilters))
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-info py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="fw-semibold"><i class="fas fa-filter me-1"></i>Active Filters:</small>
                                                @foreach($activeFilters as $filterName => $filterValue)
                                                    <span class="badge bg-primary ms-2">{{ $filterName }}: {{ $filterValue }}</span>
                                                @endforeach
                                            </div>
                                            <a href="{{ route('job-postings.public') }}" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times me-1"></i>Clear All
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>

                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Results Count --}}
                    @if(isset($jobPostings))
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <p class="text-muted mb-0">
                                        <strong>{{ $jobPostings->total() }}</strong>
                                        job{{ $jobPostings->total() !== 1 ? 's' : '' }} found
                                        @if(!empty($activeFilters))
                                            <span class="text-primary">(filtered)</span>
                                        @endif
                                    </p>

                                    @if($jobPostings->total() > 0)
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-muted small">Showing {{ $jobPostings->firstItem() }}-{{ $jobPostings->lastItem() }} of {{ $jobPostings->total() }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Job Listings --}}
                    @if($jobPostings->count() > 0)
                        <div class="row g-4">
                            @foreach($jobPostings as $job)
                                @php
                                    $hasApplied = auth()->user() && auth()->user()->role === 'pwd'
                                        ? auth()->user()->jobApplications()->where('job_posting_id', $job->id)->exists()
                                        : false;
                                    $isExpired = $job->application_deadline && $job->application_deadline->isPast();
                                    $closingSoon = $job->application_deadline && $job->application_deadline->between(now(), now()->addWeek());
                                    $isActive = $job->is_active && !$isExpired;
                                @endphp

                                <div class="col-12">
                                    <div class="card job-card h-100 {{ !$isActive ? 'border-warning' : '' }}">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <!-- Job Header -->
                                                    <div class="d-flex align-items-start mb-3">
                                                        <div class="flex-grow-1">
                                                            <h4 class="job-title mb-1">
                                                                <a href="{{ route('job-postings.public.show', $job) }}"
                                                                   class="text-decoration-none text-dark">
                                                                    {{ $job->title }}
                                                                </a>
                                                            </h4>
                                                            <div class="company-info mb-2">
                                                                <i class="fas fa-building text-muted me-1"></i>
                                                                <strong class="text-primary">{{ $job->company }}</strong>
                                                                <span class="text-muted mx-2">•</span>
                                                                <i class="fas fa-map-marker-alt text-muted me-1"></i>
                                                                <span>{{ $job->location }}</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            @if($hasApplied)
                                                                <span class="badge bg-success applied-badge">
                                                                    <i class="fas fa-check me-1"></i>Applied
                                                                </span>
                                                            @endif
                                                            @if(!$job->is_active)
                                                                <span class="badge bg-secondary">
                                                                    <i class="fas fa-pause me-1"></i>Inactive
                                                                </span>
                                                            @elseif($isExpired)
                                                                <span class="badge bg-warning expired-badge">
                                                                    <i class="fas fa-clock me-1"></i>Expired
                                                                </span>
                                                            @elseif($closingSoon)
                                                                <span class="badge bg-danger closing-soon-badge">
                                                                    <i class="fas fa-exclamation-circle me-1"></i>Closing Soon
                                                                </span>
                                                            @else
                                                                <span class="badge bg-success">
                                                                    <i class="fas fa-play me-1"></i>Active
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Job Details -->
                                                    <div class="job-meta mb-3">
                                                        <span class="badge bg-primary employment-type">
                                                            {{ $job->employment_type }}
                                                        </span>
                                                        @if($job->salary)
                                                            <span class="badge bg-success salary-badge">
                                                                <i class="fas fa-money-bill-wave me-1"></i>{{ $job->salary }}
                                                            </span>
                                                        @endif
                                                        @if($job->job_category && $job->job_category !== 'General')
                                                            <span class="badge bg-info">
                                                                {{ $job->job_category }}
                                                            </span>
                                                        @endif
                                                        @if($job->experience_level && $job->experience_level !== 'Not Specified')
                                                            <span class="badge bg-dark">
                                                                {{ $job->experience_level }}
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Job Description -->
                                                    <div class="job-description mb-3">
                                                        <p class="text-muted mb-0">
                                                            {{ Str::limit(strip_tags($job->description), 200) }}
                                                        </p>
                                                    </div>

                                                    <!-- Deadline & Posted Info -->
                                                    <div class="job-footer text-muted">
                                                        <small>
                                                            <i class="fas fa-calendar me-1"></i>
                                                            @if($job->application_deadline)
                                                                <span class="{{ $isExpired ? 'text-danger' : ($closingSoon ? 'text-warning' : 'text-success') }}">
                                                                    Apply by {{ $job->application_deadline->format('M d, Y') }}
                                                                </span>
                                                            @else
                                                                <span class="text-info">No deadline</span>
                                                            @endif
                                                            <span class="mx-2">•</span>
                                                            <i class="fas fa-clock me-1"></i>
                                                            Posted {{ $job->created_at->diffForHumans() }}
                                                            <span class="mx-2">•</span>
                                                            <i class="fas fa-eye me-1"></i>
                                                            {{ $job->views }} views
                                                            @if($job->applications_count > 0)
                                                                <span class="mx-2">•</span>
                                                                <i class="fas fa-users me-1"></i>
                                                                {{ $job->applications_count }} applications
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <!-- Action Buttons -->
                                                    <div class="job-actions h-100 d-flex flex-column justify-content-between">
                                                        <div class="text-end">
                                                            <a href="{{ route('job-postings.public.show', $job) }}"
                                                               class="btn btn-outline-primary btn-sm w-100 mb-2">
                                                                <i class="fas fa-eye me-1"></i>View Details
                                                            </a>

                                                            @if(auth()->user() && auth()->user()->role === 'pwd')
                                                                @if($hasApplied)
                                                                    <button class="btn btn-success btn-sm w-100" disabled>
                                                                        <i class="fas fa-check me-1"></i>Already Applied
                                                                    </button>
                                                                @elseif($isActive)
                                                                    <form action="{{ route('job.apply', $job) }}" method="POST" class="d-inline w-100">
                                                                        @csrf
                                                                        <button type="submit" class="btn btn-primary btn-sm w-100 apply-btn"
                                                                                data-job-title="{{ $job->title }}"
                                                                                data-job-company="{{ $job->company }}">
                                                                            <i class="fas fa-paper-plane me-1"></i>Apply Now
                                                                        </button>
                                                                    </form>
                                                                @else
                                                                    <button class="btn btn-secondary btn-sm w-100" disabled>
                                                                        <i class="fas fa-times me-1"></i>Application Closed
                                                                    </button>
                                                                @endif
                                                            @elseif(!auth()->user())
                                                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">
                                                                    <i class="fas fa-sign-in-alt me-1"></i>Login to Apply
                                                                </a>
                                                            @elseif(auth()->user()->role !== 'pwd')
                                                                <button class="btn btn-secondary btn-sm w-100" disabled>
                                                                    <i class="fas fa-info-circle me-1"></i>PWD Users Only
                                                                </button>
                                                            @endif
                                                        </div>

                                                        @if($job->application_deadline && !$isExpired && $job->is_active)
                                                            <div class="deadline-countdown text-center">
                                                                <small class="text-muted">
                                                                    <i class="fas fa-hourglass-half me-1"></i>
                                                                    {{ $job->application_deadline->diffForHumans() }} left
                                                                </small>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                            <h3 class="text-muted">No Job Opportunities Found</h3>
                            <p class="text-muted mb-4">We couldn't find any jobs matching your criteria. Try adjusting your filters.</p>
                            <a href="{{ route('job-postings.public') }}" class="btn btn-primary">
                                <i class="fas fa-refresh me-1"></i>Clear All Filters
                            </a>
                        </div>
                    @endif

                    {{-- Pagination --}}
                    @if(isset($jobPostings) && $jobPostings->hasPages())
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-center">
                                    {{ $jobPostings->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Application Requirements Modal -->
@if(auth()->user() && auth()->user()->role === 'pwd' && !auth()->user()->pwdProfile)
<div class="modal fade" id="profileRequiredModal" tabindex="-1" aria-labelledby="profileRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="profileRequiredModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Profile Completion Required
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Before you can apply for jobs, you need to complete your PWD profile information.</p>
                <p>This helps employers understand your specific needs and qualifications.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Later</button>
                <a href="{{ route('profile.pwd-complete-form') }}" class="btn btn-primary">
                    <i class="fas fa-user-edit me-1"></i>Complete Profile Now
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Application Confirmation Modal -->
<div class="modal fade" id="applicationConfirmModal" tabindex="-1" aria-labelledby="applicationConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="applicationConfirmModalLabel">
                    <i class="fas fa-paper-plane me-2"></i>Confirm Application
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to apply for the position:</p>
                <p class="fw-bold" id="jobTitleConfirm"></p>
                <p class="text-muted">at <span id="companyNameConfirm"></span>?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Please ensure your PWD profile is complete before applying.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmApply">Yes, Apply Now</button>
            </div>
        </div>
    </div>
</div>

<style>
.job-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    border: 1px solid #e9ecef;
}
.job-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    border-color: #007bff;
}
.job-description {
    max-height: 80px;
    overflow: hidden;
    position: relative;
}
.job-description::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 20px;
    background: linear-gradient(transparent, white);
}
.filter-label {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}
.badge-filter {
    font-size: 0.75rem;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const publicForm = document.getElementById('public-filters-form');

    // Enhanced filter submission with loading state
    if (publicForm) {
        publicForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Filtering...';
                submitBtn.disabled = true;
            }
        });
    }

    // Clear filters functionality
    const clearButtons = document.querySelectorAll('a[href="{{ route('job-postings.public') }}"]');
    clearButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            window.location.href = this.href;
        });
    });

    // Application confirmation modal
    const applyButtons = document.querySelectorAll('.apply-btn');
    const applicationConfirmModal = new bootstrap.Modal(document.getElementById('applicationConfirmModal'));
    let currentForm = null;

    applyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const jobTitle = this.getAttribute('data-job-title');
            const companyName = this.getAttribute('data-job-company');

            document.getElementById('jobTitleConfirm').textContent = jobTitle;
            document.getElementById('companyNameConfirm').textContent = companyName;

            currentForm = this.closest('form');
            applicationConfirmModal.show();
        });
    });

    // Confirm application
    document.getElementById('confirmApply').addEventListener('click', function() {
        if (currentForm) {
            // Add loading state
            const originalText = this.innerHTML;
            this.disabled = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Applying...';

            // Submit the form
            currentForm.submit();

            // Close modal after a delay
            setTimeout(() => {
                applicationConfirmModal.hide();
                this.disabled = false;
                this.innerHTML = originalText;
            }, 2000);
        }
    });
});
</script>
@endsection
