@extends('layouts.app')  // Change this line

@section('title', 'Manage Job Postings - Admin Panel')

@section('content')
<div class="container-fluid"></div>
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="page-header">
                <h1 class="h2 text-primary mb-2">
                    <i class="fas fa-briefcase me-2"></i>Available Job Opportunities
                </h1>
                <p class="text-muted mb-0">Discover meaningful employment opportunities tailored for Persons with Disabilities</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title">{{ $jobPostings->total() }}</h4>
                            <p class="card-text mb-0">Total Jobs</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-briefcase fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title">{{ $jobPostings->where('is_active', true)->where(function($job) {
                                return !$job->application_deadline || $job->application_deadline->isFuture();
                            })->count() }}</h4>
                            <p class="card-text mb-0">Active Listings</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-bullhorn fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title">{{ $jobPostings->where('employment_type', 'Full-time')->count() }}</h4>
                            <p class="card-text mb-0">Full-time Roles</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="card-title">{{ $jobPostings->filter(function($job) {
                                return $job->application_deadline &&
                                       $job->application_deadline->between(now(), now()->addWeek());
                            })->count() }}</h4>
                            <p class="card-text mb-0">Closing Soon</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-hourglass-half fa-2x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="card filter-card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="fas fa-filter me-2"></i>Filters
                    </h5>
                </div>
                <div class="card-body">
                    <form id="jobFilters" method="GET" action="{{ route('job-postings.public') }}">
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Search Jobs</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control"
                                       value="{{ request('search') }}"
                                       placeholder="Job title, company...">
                            </div>
                        </div>

                        <!-- Employment Type -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Employment Type</label>
                            <div class="filter-options">
                                @foreach(['Full-time', 'Part-time', 'Contract', 'Temporary', 'Internship', 'Remote'] as $type)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                               name="employment_type[]"
                                               value="{{ $type }}"
                                               id="type_{{ Str::slug($type) }}"
                                               {{ in_array($type, (array)request('employment_type', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="type_{{ Str::slug($type) }}">
                                            {{ $type }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Location</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" name="location" class="form-control"
                                       value="{{ request('location') }}"
                                       placeholder="City, province...">
                            </div>
                        </div>

                        <!-- Salary Range -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Salary Range</label>
                            <select name="salary_range" class="form-select">
                                <option value="">Any Salary</option>
                                <option value="0-15000" {{ request('salary_range') == '0-15000' ? 'selected' : '' }}>Up to ₱15,000</option>
                                <option value="15000-30000" {{ request('salary_range') == '15000-30000' ? 'selected' : '' }}>₱15,000 - ₱30,000</option>
                                <option value="30000-50000" {{ request('salary_range') == '30000-50000' ? 'selected' : '' }}>₱30,000 - ₱50,000</option>
                                <option value="50000+" {{ request('salary_range') == '50000+' ? 'selected' : '' }}>₱50,000+</option>
                            </select>
                        </div>

                        <!-- Deadline -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deadline</label>
                            <select name="deadline" class="form-select">
                                <option value="">Any Time</option>
                                <option value="today" {{ request('deadline') == 'today' ? 'selected' : '' }}>Today</option>
                                <option value="week" {{ request('deadline') == 'week' ? 'selected' : '' }}>Within a Week</option>
                                <option value="month" {{ request('deadline') == 'month' ? 'selected' : '' }}>Within a Month</option>
                                <option value="none" {{ request('deadline') == 'none' ? 'selected' : '' }}>No Deadline</option>
                            </select>
                        </div>

                        <!-- Sort Option -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="deadline" {{ request('sort') == 'deadline' ? 'selected' : '' }}>Application Deadline</option>
                                <option value="salary" {{ request('sort') == 'salary' ? 'selected' : '' }}>Salary (High to Low)</option>
                            </select>
                        </div>

                        <!-- Filter Actions -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-filter me-1"></i>Apply Filters
                            </button>
                            <a href="{{ route('job-postings.public') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-1"></i>Reset Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Tips -->
            <div class="card mt-4">
                <div class="card-header bg-light">
                    <h6 class="mb-0">
                        <i class="fas fa-lightbulb me-2"></i>Application Tips
                    </h6>
                </div>
                <div class="card-body">
                    <div class="tips-list">
                        <div class="tip-item d-flex align-items-start mb-3">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <small>Complete your PWD profile before applying</small>
                        </div>
                        <div class="tip-item d-flex align-items-start mb-3">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <small>Tailor your application to each job</small>
                        </div>
                        <div class="tip-item d-flex align-items-start">
                            <i class="fas fa-check-circle text-success mt-1 me-2"></i>
                            <small>Apply early for better chances</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Listings -->
        <div class="col-lg-9">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle me-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Results Header -->
            <div class="card mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">
                                <span class="text-primary">{{ $jobPostings->total() }}</span>
                                job{{ $jobPostings->total() !== 1 ? 's' : '' }} found
                            </h5>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="sort-options">
                                <label class="form-label me-2 mb-0">Sort by:</label>
                                <select class="form-select form-select-sm d-inline-block w-auto" onchange="window.location.href = this.value">
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}"
                                            {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'deadline']) }}"
                                            {{ request('sort') == 'deadline' ? 'selected' : '' }}>Deadline</option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'salary']) }}"
                                            {{ request('sort') == 'salary' ? 'selected' : '' }}>Salary (High to Low)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($jobPostings->count() > 0)
                <!-- Job Cards Grid -->
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

                                                    <!-- Admin Actions (if applicable) -->
                                                    @if(auth()->user() && (auth()->user()->role === 'admin' || auth()->user()->id === $job->created_by))
                                                        <div class="mt-2 pt-2 border-top">
                                                            <small class="text-muted d-block mb-1">Admin Actions:</small>
                                                            <div class="btn-group w-100" role="group">
                                                                <a href="{{ route('job-postings.edit', $job) }}"
                                                                   class="btn btn-outline-warning btn-sm" title="Edit">
                                                                    <i class="fas fa-edit"></i>
                                                                </a>
                                                                <a href="{{ route('job-postings.show', $job) }}"
                                                                   class="btn btn-outline-info btn-sm" title="View Details">
                                                                    <i class="fas fa-chart-bar"></i>
                                                                </a>
                                                            </div>
                                                        </div>
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

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    <nav aria-label="Job pagination">
                        {{ $jobPostings->withQueryString()->links() }}
                    </nav>
                </div>
            @else
                <!-- Empty State -->
                <div class="card">
                    <div class="card-body text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                            <h3 class="text-muted">No Job Opportunities Found</h3>
                            <p class="text-muted mb-4">We couldn't find any jobs matching your criteria. Try adjusting your filters.</p>
                            <a href="{{ route('job-postings.public') }}" class="btn btn-primary">
                                <i class="fas fa-refresh me-1"></i>Clear All Filters
                            </a>
                        </div>
                    </div>
                </div>
            @endif
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
.page-header {
    border-bottom: 3px solid #e9ecef;
    padding-bottom: 1rem;
}

.stat-card {
    border: none;
    border-radius: 10px;
    transition: transform 0.2s ease-in-out;
}

.stat-card:hover {
    transform: translateY(-3px);
}

.filter-card {
    position: sticky;
    top: 20px;
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.filter-options {
    max-height: 200px;
    overflow-y: auto;
}

.job-card {
    border: none;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border-left: 4px solid #007bff;
}

.job-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
}

.job-card.border-warning {
    border-left-color: #ffc107;
}

.job-title {
    font-weight: 600;
    line-height: 1.3;
}

.job-title a:hover {
    color: #007bff !important;
}

.company-info {
    font-size: 0.95rem;
}

.job-meta .badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
    margin-right: 0.5em;
    margin-bottom: 0.5em;
}

.job-description {
    line-height: 1.6;
}

.applied-badge, .closing-soon-badge, .expired-badge {
    font-size: 0.7rem;
    padding: 0.4em 0.8em;
}

.employment-type {
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.salary-badge {
    background: linear-gradient(135deg, #28a745, #1e7e34);
}

.job-actions .btn {
    border-radius: 6px;
    font-weight: 500;
}

.deadline-countdown {
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 6px;
    margin-top: 1rem;
}

.tips-list .tip-item {
    font-size: 0.85rem;
}

.empty-state {
    padding: 2rem 0;
}

.sort-options {
    display: inline-flex;
    align-items: center;
}

@media (max-width: 768px) {
    .job-actions {
        margin-top: 1rem;
        border-top: 1px solid #e9ecef;
        padding-top: 1rem;
    }

    .sort-options {
        margin-top: 1rem;
        justify-content: flex-start;
    }

    .stat-card .card-body {
        padding: 1rem;
    }

    .stat-card h4 {
        font-size: 1.5rem;
    }
}

/* Loading animation */
.loading-spinner {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #007bff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if profile completion is required
    @if(auth()->user() && auth()->user()->role === 'pwd' && !auth()->user()->pwdProfile)
    const applyButtons = document.querySelectorAll('.apply-btn');
    applyButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const modal = new bootstrap.Modal(document.getElementById('profileRequiredModal'));
            modal.show();
        });
    });
    @else
    // Application confirmation modal for users with complete profiles
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
            this.innerHTML = '<span class="loading-spinner me-2"></span> Applying...';

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
    @endif

    // Auto-submit form when sort changes
    const sortSelect = document.querySelector('select[name="sort"]');
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            document.getElementById('jobFilters').submit();
        });
    }

    // Add loading state to filter buttons
    const filterForm = document.getElementById('jobFilters');
    const applyFiltersBtn = filterForm.querySelector('button[type="submit"]');

    filterForm.addEventListener('submit', function() {
        applyFiltersBtn.disabled = true;
        applyFiltersBtn.innerHTML = '<span class="loading-spinner me-2"></span> Applying Filters...';
    });

    // Reset loading state when page loads
    window.addEventListener('pageshow', function() {
        if (applyFiltersBtn) {
            applyFiltersBtn.disabled = false;
            applyFiltersBtn.innerHTML = '<i class="fas fa-filter me-1"></i>Apply Filters';
        }
    });

    // Dynamic filter counts (AJAX)
    function updateFilterCounts() {
        const formData = new FormData(document.getElementById('jobFilters'));
        const searchParams = new URLSearchParams(formData);

        fetch(`{{ route('api.job-postings.stats') }}?${searchParams.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Update stats cards with new counts if available
                if (data.total !== undefined) {
                    document.querySelector('.stat-card.bg-primary .card-title').textContent = data.total;
                }
                if (data.active !== undefined) {
                    document.querySelector('.stat-card.bg-success .card-title').textContent = data.active;
                }
                // Add more stat updates as needed
            })
            .catch(error => console.error('Error updating filter counts:', error));
    }

    // Initialize filter counts on page load
    updateFilterCounts();

    // Update counts when filters change (debounced)
    let updateTimeout;
    const filterInputs = document.querySelectorAll('#jobFilters input, #jobFilters select');

    filterInputs.forEach(input => {
        input.addEventListener('change', function() {
            clearTimeout(updateTimeout);
            updateTimeout = setTimeout(updateFilterCounts, 500);
        });
    });

    // Print functionality
    window.printJobListings = function() {
        const originalContents = document.body.innerHTML;
        const printContents = document.querySelector('.col-lg-9').innerHTML;

        document.body.innerHTML = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Job Opportunities - {{ config('app.name') }}</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { font-family: Arial, sans-serif; }
                    .job-card { border: 1px solid #ddd; margin-bottom: 1rem; padding: 1rem; }
                    .badge { margin-right: 0.5rem; }
                    @media print {
                        .btn { display: none; }
                        a { text-decoration: none; color: inherit; }
                        .filter-card { display: none; }
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <h1 class="text-center mb-4">Job Opportunities - {{ config('app.name') }}</h1>
                    <div class="text-muted text-center mb-4">Printed on ${new Date().toLocaleDateString()}</div>
                    ${printContents}
                </div>
            </body>
            </html>
        `;

        window.print();
        document.body.innerHTML = originalContents;
        window.location.reload();
    }

    // Enhance accessibility
    document.querySelectorAll('.job-card').forEach(card => {
        card.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                const link = this.querySelector('a[href]');
                if (link) {
                    link.click();
                }
            }
        });

        // Add tabindex for keyboard navigation
        card.setAttribute('tabindex', '0');
    });
});
</script>
@endsection
