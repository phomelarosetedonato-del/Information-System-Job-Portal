@extends('layouts.app')

@section('title', 'PWD Dashboard - PWD Job Portal')

@section('content')
<!-- Skip Navigation for Accessibility -->
<a href="#main-content" class="sr-only sr-only-focusable bg-primary text-white p-2 position-absolute top-0 start-0" style="z-index: 9999; transform: translateY(-100%); transition: transform 0.3s;">
    Skip to main content
</a>

<div class="dashboard-container" id="main-content" tabindex="-1" role="main" aria-labelledby="dashboard-heading">
    <!-- Dashboard Header -->
    <div class="dashboard-header bg-white border-bottom py-4">
        <div class="dashboard-header-content">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2 text-dark" id="dashboard-heading">
                        <i class="fas fa-universal-access me-2 text-primary" aria-hidden="true"></i>
                        Welcome back, {{ $user->name }}! 👋
                    </h1>
                    <p class="mb-0 text-muted" ml-2>Here's your personalized job portal dashboard</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="dashboard-content bg-light">
        <div class="container-fluid py-4 pt-5">
            <!-- Session Messages & Alerts -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" aria-live="polite">
                    <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close success message"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" aria-live="polite">
                    <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close success notification"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" aria-live="assertive">
                    <i class="fas fa-exclamation-triangle me-2" aria-hidden="true"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close error message"></button>
                </div>
            @endif

            <!-- 🏠 1. Welcome & Profile Summary -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="h4 mb-3 text-dark">👋 Welcome to Your Job Portal Dashboard</h2>

                                    <!-- Profile Completion (dynamic, computed from user data) -->
                                    <div class="mb-3">
                                        @php
                                            if (method_exists($user, 'getProfileCompletionPercentage')) {
                                                $completion = (int) $user->getProfileCompletionPercentage();
                                            } else {
                                                $completion = $user->profileCompletion ?? null;
                                                if (is_null($completion)) {
                                                    $completion = ($user->pwdProfile ?? false) ? 80 : 20;
                                                }
                                            }
                                        @endphp
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Profile Completion</span>
                                            <span class="fw-bold text-primary">{{ $completion }}% Complete</span>
                                        </div>
                                        <div class="progress" style="height: 8px;" aria-hidden="false" aria-valuemin="0" aria-valuemax="100">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $completion }}%" aria-valuenow="{{ $completion }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>

                                    <p class="text-muted mb-3">Complete your profile to increase your job match rate and visibility to employers.</p>

                                    <div class="d-grid gap-2">
                                        <form action="{{ route('profile.uploadResume') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                                            @csrf
                                            <input type="file" name="resume" id="skills-upload-resume" style="display: none;" onchange="this.form.submit()">
                                            <label for="skills-upload-resume" class="btn btn-outline-info btn-sm mb-0">
                                                <i class="fas fa-upload me-1"></i> Upload New Resume
                                            </label>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-4 text-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-4 d-inline-block">
                                        <i class="fas fa-user-tie fa-3x text-primary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Left Column - Main Content -->
                <div class="col-lg-8">

                    <!-- Job Filters (PWD friendly) - compact and organized -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-filter me-2 text-muted" aria-hidden="true"></i>
                                    <h5 class="mb-0 text-dark">Search & Filters</h5>
                                </div>
                                <small class="text-muted">{{ $jobPostings->total() }} jobs found</small>
                            </div>
                        </div>
                        <div class="card-body">
                            <form id="pwd-filters-form" method="GET" action="{{ route('pwd.dashboard') }}" aria-label="Filter job postings">
                                <div class="row g-3 align-items-end">
                                    <!-- Search Input -->
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold" for="q">Search Jobs</label>
                                        <input id="q" name="q" type="search" class="form-control form-control-sm"
                                               placeholder="Job title, company, or keywords…"
                                               value="{{ $filters['q'] ?? request('q') }}"
                                               aria-label="Search by job title, company, or keywords">
                                    </div>

                                    <!-- Location Input -->
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold" for="location">Location</label>
                                        <input id="location" name="location" type="text" class="form-control form-control-sm"
                                               placeholder="City or province"
                                               value="{{ $filters['location'] ?? request('location') }}"
                                               aria-label="Filter by location">
                                    </div>

                                    <!-- Employment Type Filter - Single Select Dropdown -->
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold" for="employment_type">Job Type</label>
                                        <select id="employment_type" name="employment_type" class="form-select form-select-sm" aria-label="Employment type">
                                            <option value="">All Job Types</option>
                                            @foreach($employmentTypes as $type)
                                                @if(!empty(trim($type)))
                                                    <option value="{{ $type }}"
                                                        {{ ($filters['employment_type'] ?? request('employment_type')) == $type ? 'selected' : '' }}>
                                                        {{ $type }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Disability Type Filter -->
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold" for="disability_type_id">Disability Type</label>
                                        <select id="disability_type_id" name="disability_type_id" class="form-select form-select-sm" aria-label="Disability type">
                                            <option value="">Any disability type</option>
                                            @foreach($disabilityTypes as $dt)
                                                @php $label = trim($dt->type ?? ''); @endphp
                                                @if($label !== '')
                                                    <option value="{{ $dt->id }}"
                                                        {{ (isset($filters['disability_type_id']) && $filters['disability_type_id'] == $dt->id) ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Accommodations Filter -->
                                    <div class="col-md-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="accommodations" name="accommodations" value="1"
                                                {{ ($filters['accommodations'] ?? request('accommodations')) == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label small fw-bold" for="accommodations">Show accommodations only</label>
                                        </div>
                                    </div>

                                    <!-- Remote Work Filter -->
                                    <div class="col-md-3">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="remote" name="remote" value="1"
                                                {{ ($filters['remote'] ?? request('remote')) == '1' ? 'checked' : '' }}>
                                            <label class="form-check-label small fw-bold" for="remote">Remote jobs only</label>
                                        </div>
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="col-md-2">
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-search me-1"></i> Search
                                            </button>
                                            <a href="{{ route('pwd.dashboard') }}" class="btn btn-outline-secondary btn-sm">Clear All</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Filter Tabs -->
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <div class="d-flex flex-wrap gap-2">
                                            <small class="text-muted me-2">Quick filters:</small>
                                            <a href="{{ route('pwd.dashboard', ['employment_type' => 'Full-time']) }}" class="badge bg-primary text-decoration-none">Full-time</a>
                                            <a href="{{ route('pwd.dashboard', ['employment_type' => 'Part-time']) }}" class="badge bg-success text-decoration-none">Part-time</a>
                                            <a href="{{ route('pwd.dashboard', ['employment_type' => 'Contract']) }}" class="badge bg-info text-decoration-none">Contract</a>
                                            <a href="{{ route('pwd.dashboard', ['remote' => 1]) }}" class="badge bg-warning text-decoration-none">Remote</a>
                                            <a href="{{ route('pwd.dashboard', ['accommodations' => 1]) }}" class="badge bg-danger text-decoration-none">With Accommodations</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- 💼 2. Recommended Job Opportunities -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h5 mb-0 text-dark">
                                    <i class="fas fa-briefcase me-2 text-primary"></i>
                                    @if(request()->anyFilled(['q', 'location', 'employment_type', 'disability_type_id', 'accommodations', 'remote']))
                                        Filtered Job Opportunities
                                        <span class="badge bg-primary ms-2">Filtered</span>
                                    @else
                                        Recommended Job Opportunities
                                        <span class="badge bg-success ms-2">Personalized</span>
                                    @endif
                                </h2>
                                <small class="text-muted">Showing {{ $jobPostings->count() }} of {{ $jobPostings->total() }} jobs</small>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div id="pwd-job-results">
                                @if($jobPostings->count() > 0)
                                    @include('job-postings.partials.list', ['jobPostings' => $jobPostings])
                                @else
                                    <!-- Fallback when no jobs found -->
                                    <div class="text-center py-5">
                                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                                        <h4 class="text-dark mb-3">No jobs match your current filters</h4>
                                        <p class="text-muted mb-4">Try adjusting your search criteria or browse all available jobs.</p>
                                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                                            <a href="{{ route('pwd.dashboard') }}" class="btn btn-primary">
                                                <i class="fas fa-refresh me-2"></i>Clear Filters
                                            </a>
                                            <a href="{{ route('job-postings.public') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-briefcase me-2"></i>Browse All Jobs
                                            </a>
                                        </div>

                                        <!-- Show some fallback suggestions -->
                                        @php
                                            $fallbackJobs = \App\Models\JobPosting::active()
                                                ->open()
                                                ->with('employer')
                                                ->latest()
                                                ->take(3)
                                                ->get();
                                        @endphp

                                        @if($fallbackJobs->count() > 0)
                                            <div class="mt-5">
                                                <h5 class="text-dark mb-3">Recently Posted Jobs You Might Like:</h5>
                                                <div class="row">
                                                    @foreach($fallbackJobs as $job)
                                                        <div class="col-md-4 mb-3">
                                                            <div class="card card-hover h-100">
                                                                <div class="card-body">
                                                                    <h6 class="card-title">{{ $job->title }}</h6>
                                                                    <p class="card-text small text-muted mb-1">{{ $job->employer->company_name ?? 'Company' }}</p>
                                                                    <p class="card-text small text-muted mb-2">{{ $job->location }}</p>
                                                                    <span class="badge bg-light text-dark small">{{ $job->employment_type }}</span>
                                                                </div>
                                                                <div class="card-footer bg-transparent">
                                                                    <a href="{{ route('job-postings.public.show', $job) }}" class="btn btn-sm btn-outline-primary w-100">View Job</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if($jobPostings->hasPages() && $jobPostings->count() > 0)
                            <div class="card-footer bg-white border-top py-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div>
                                        <small class="text-muted">
                                            Showing {{ $jobPostings->firstItem() }} to {{ $jobPostings->lastItem() }} of {{ $jobPostings->total() }} results
                                        </small>
                                    </div>
                                    <div class="pagination-sm-wrapper">
                                        {{ $jobPostings->appends(request()->query())->links('pagination::bootstrap-4') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Right Column - Sidebar -->
                <div class="col-lg-4">
                    <!-- � 3. Application Summary / Stats -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h2 class="h5 mb-0 text-dark">📊 Application Summary</h2>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-primary bg-opacity-10 rounded">
                                        <div class="h4 text-primary mb-1">{{ $applicationStats['total'] }}</div>
                                        <div class="text-muted small">Total Applications</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                                        <div class="h4 text-warning mb-1">{{ $applicationStats['pending'] }}</div>
                                        <div class="text-muted small">Under Review</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-success bg-opacity-10 rounded">
                                        <div class="h4 text-success mb-1">{{ $applicationStats['approved'] }}</div>
                                        <div class="text-muted small">Shortlisted</div>
                                    </div>
                                </div>
                                <div class="col-6 mb-3">
                                    <div class="p-3 bg-info bg-opacity-10 rounded">
                                        <div class="h4 text-info mb-1">{{ $applicationStats['hired'] }}</div>
                                        <div class="text-muted small">Hired</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress Visualization -->
                            @if($applicationStats['total'] > 0)
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small">Application Progress</span>
                                        <span class="text-muted small">{{ $applicationStats['total'] }} applications</span>
                                    </div>
                                    <div class="progress" style="height: 12px;">
                                        @php
                                            $pendingWidth = ($applicationStats['pending'] / $applicationStats['total']) * 100;
                                            $approvedWidth = ($applicationStats['approved'] / $applicationStats['total']) * 100;
                                            $hiredWidth = ($applicationStats['hired'] / $applicationStats['total']) * 100;
                                        @endphp
                                        <div class="progress-bar bg-warning" style="width: {{ $pendingWidth }}%" title="Under Review: {{ $applicationStats['pending'] }}"></div>
                                        <div class="progress-bar bg-success" style="width: {{ $approvedWidth }}%" title="Shortlisted: {{ $applicationStats['approved'] }}"></div>
                                        <div class="progress-bar bg-info" style="width: {{ $hiredWidth }}%" title="Hired: {{ $applicationStats['hired'] }}"></div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 🧾 4. Recent Applications -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h2 class="h5 mb-0 text-dark">🧾 Recent Applications</h2>
                        </div>
                        <div class="card-body p-0">
                            @if($recentApplications->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentApplications->take(3) as $application)
                                        <div class="list-group-item border-0 p-3">
                                            <h6 class="mb-1 text-dark">{{ $application->jobPosting->title ?? 'N/A' }}</h6>
                                            <p class="mb-1 small text-muted">{{ $application->jobPosting->employer->company_name ?? 'N/A' }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">{{ $application->created_at->format('M d, Y') }}</small>
                                                <span class="badge bg-{{ $application->status == 'pending' ? 'warning' : ($application->status == 'approved' ? 'success' : ($application->status == 'rejected' ? 'danger' : 'info')) }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-0">
                                        @endif
                                    @endforeach
                                </div>
                                <div class="card-footer bg-white border-top py-2 text-center">
                                    <a href="{{ route('applications.index') }}" class="btn btn-sm btn-outline-primary">
                                        View All Applications <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-2 small">No applications yet</p>
                                    <a href="{{ route('job-postings.public') }}" class="btn btn-sm btn-outline-primary">Start Applying</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- �🔔 5. Notifications / Alerts -->
                    <div class="card shadow-sm border-warning mb-4">
                        <div class="card-header bg-warning text-dark border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h5 mb-0">🔔 Notifications & Alerts</h2>
                                <span class="badge bg-dark">{{ $user->unreadNotifications->count() }} New</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($user->notifications->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($user->notifications->take(5) as $notification)
                                        <div class="list-group-item border-0 p-3 {{ $notification->unread() ? 'bg-light' : '' }}">
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <p class="mb-1 small">{{ $notification->data['message'] ?? 'New notification' }}</p>
                                                    <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                                </div>
                                                @if($notification->unread())
                                                    <span class="badge bg-primary ms-2">New</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-0">
                                        @endif
                                    @endforeach
                                </div>
                                <div class="card-footer bg-white border-top py-2 text-center">
                                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-warning">View All Notifications</a>
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-bell-slash fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No notifications</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- 📅 6. Saved Jobs / Favorites -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h2 class="h5 mb-0 text-dark">📅 Saved Jobs</h2>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item border-0 p-3 text-center text-muted">
                                    <i class="fas fa-bookmark fa-2x mb-2"></i>
                                    <p class="mb-2">No saved jobs yet</p>
                                    <a href="{{ route('job-postings.public') }}" class="btn btn-sm btn-outline-primary">
                                        Browse Jobs to Save
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 🧠 7. Skill & Resume Insights -->
                    <div class="card shadow-sm border-info mb-4">
                        <div class="card-header bg-info text-white border-bottom py-3">
                            <h2 class="h5 mb-0">🧠 Skill & Resume Insights</h2>
                        </div>
                        <div class="card-body">
                            <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                                <i class="fas fa-lightbulb me-2"></i>
                                <strong>Tip:</strong> Complete your profile to get better job recommendations.
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Resume Strength</span>
                                    <span class="badge bg-{{ $user->hasResume() ? 'success' : 'warning' }}">
                                        {{ $user->hasResume() ? 'Good' : 'Needs Upload' }}
                                    </span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $user->hasResume() ? 'success' : 'warning' }}" style="width: {{ $user->hasResume() ? '75' : '25' }}%"></div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <h6 class="text-dark mb-2">Suggested Improvements:</h6>
                                <ul class="list-unstyled small text-muted">
                                    @if(!$user->hasResume())
                                        <li class="mb-1">📄 Upload your resume</li>
                                    @endif
                                    @if($profileCompletion < 80)
                                        <li class="mb-1">✅ Complete your profile</li>
                                    @endif
                                    <li class="mb-1">🎯 Keep skills updated</li>
                                    <li class="mb-1">📈 Add work experience</li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2">
                                <form action="{{ route('profile.uploadResume') }}" method="POST" enctype="multipart/form-data" class="d-inline">
                                    @csrf
                                    <input type="file" name="resume" id="sidebar-upload-resume" style="display: none;" onchange="this.form.submit()">
                                    <label for="sidebar-upload-resume" class="btn btn-outline-info btn-sm mb-0 w-100">
                                        <i class="fas fa-upload me-1"></i> Upload New Resume
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- ⚙️ 9. Account & Settings Quick Links -->
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h2 class="h5 mb-0 text-dark">⚙️ Account & Settings</h2>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action border-0">
                                    <i class="fas fa-user me-2 text-primary"></i> Profile Details
                                </a>
                                <a href="{{ route('accessibility.settings') }}" class="list-group-item list-group-item-action border-0">
                                    <i class="fas fa-universal-access me-2 text-primary"></i> Accessibility Settings
                                </a>
                                <a href="{{ route('notifications.index') }}" class="list-group-item list-group-item-action border-0">
                                    <i class="fas fa-bell me-2 text-primary"></i> Notification Preferences
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 text-danger"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

<!-- Resume Required Modal -->
<div class="modal fade" id="resumeRequiredModal" tabindex="-1" aria-labelledby="resumeRequiredModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark py-2">
                <h6 class="modal-title mb-0" id="resumeRequiredModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>Resume Required
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <div class="text-center mb-3">
                    <i class="fas fa-file-upload fa-3x text-warning mb-2"></i>
                    <p class="mb-2 fw-bold">Upload Your Resume to Apply</p>
                    <p class="text-muted small mb-0">
                        You need to have a resume on file before you can apply for jobs.
                    </p>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-warning btn-sm" id="uploadResumeBtn">
                        <i class="fas fa-upload me-2"></i>Upload Resume Now
                    </button>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-user-edit me-2"></i>Go to Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Resume Upload Form Modal -->
<div class="modal fade" id="resumeUploadModal" tabindex="-1" aria-labelledby="resumeUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white py-2">
                <h6 class="modal-title mb-0" id="resumeUploadModalLabel">
                    <i class="fas fa-file-alt me-2"></i>Upload Resume
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <form action="{{ route('profile.uploadResume') }}" method="POST" enctype="multipart/form-data" id="uploadResumeForm">
                    @csrf
                    <div class="mb-3">
                        <label for="resume-file" class="form-label small">Choose Resume File</label>
                        <input type="file" class="form-control form-control-sm" id="resume-file" name="resume"
                               accept=".pdf,.doc,.docx" required>
                        <div class="form-text small">PDF, DOC, or DOCX (Max 5MB)</div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-sm" id="submitResumeBtn">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Upload Resume
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
        border: 2px solid #6b7280;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.6);
        border-color: #02710b;
        border-width: 2px;
    }

    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
    }

    .dashboard-content {
        min-height: calc(100vh - 200px);
    }

    .border-bottom {
        border-bottom: 2px solid #6b7280 !important;
    }

    .card {
        border: 2px solid #6b7280 !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.5) !important;
    }

    .card-header {
        border-bottom: 2px solid #6b7280 !important;
    }

    .card-footer {
        border-top: 2px solid #6b7280 !important;
    }

    .text-primary { color: #2E8B57 !important; }
    .text-success { color: #1A5D34 !important; }
    .text-info { color: #4CAF50 !important; }
    .text-warning { color: #1C3D2A !important; }

    .btn-outline-primary {
        border-color: #2E8B57;
        color: #2E8B57;
    }
    .btn-outline-primary:hover {
        background-color: #2E8B57;
        border-color: #2E8B57;
        color: white;
    }

    .border-primary { border-color: #2E8B57 !important; }
    .border-success { border-color: #1A5D34 !important; }
    .border-info { border-color: #4CAF50 !important; }
    .border-warning { border-color: #1C3D2A !important; }

    .table th {
        border-top: none;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .progress {
        border-radius: 10px;
    }

    .list-group-item {
        transition: background-color 0.2s ease;
    }

    .list-group-item:hover {
        background-color: #f8f9fa;
    }

    .badge {
        cursor: pointer;
    }

    /* Loading animation */
    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Compact Pagination Styles */
    .pagination-sm-wrapper .pagination {
        margin-bottom: 0;
        gap: 4px;
    }

    .pagination-sm-wrapper .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 4px;
        margin: 0 2px;
        min-width: 32px;
        text-align: center;
    }

    .pagination-sm-wrapper .page-item:first-child .page-link,
    .pagination-sm-wrapper .page-item:last-child .page-link {
        border-radius: 4px;
    }

    .pagination-sm-wrapper .page-item.active .page-link {
        background-color: #2E8B57;
        border-color: #2E8B57;
        color: white;
        font-weight: 600;
    }

    .pagination-sm-wrapper .page-item.disabled .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .pagination-sm-wrapper .page-link:hover {
        background-color: #f8f9fa;
        border-color: #2E8B57;
        color: #2E8B57;
    }

    /* Make pagination responsive */
    @media (max-width: 768px) {
        .pagination-sm-wrapper .page-link {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
            min-width: 28px;
        }

        .pagination-sm-wrapper .pagination {
            gap: 2px;
        }

        .pagination-sm-wrapper .page-link {
            margin: 0 1px;
        }
    }

    /* Ensure card footer has proper spacing */
    .card-footer {
        padding: 0.75rem 1rem;
    }

    .card-footer .d-flex {
        gap: 0.5rem;
    }

    /* Darker dividers between list items */
    hr {
        border-color: #6b6b6b !important;
        opacity: 1 !important;
    }

    .list-group-item {
        border-color: #a0a0a0 !important;
    }
</style>
@endsection

@section('scripts')
<script>
    // Wait for jQuery to be available, then initialize
    (function checkJQuery() {
        if (typeof jQuery !== 'undefined') {
            initializeResumeHandlers();
        } else {
            setTimeout(checkJQuery, 100);
        }
    })();

    function initializeResumeHandlers() {
        jQuery(document).ready(function($) {
            console.log('Document ready, initializing handlers');
            console.log('jQuery version:', $.fn.jquery);
            console.log('Upload button exists:', $('#uploadResumeBtn').length);

            // ========== RESUME UPLOAD HANDLERS ==========

            // Handle resume upload button click using jQuery
            $('#uploadResumeBtn').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Upload Resume button clicked!');
            console.log('Button element:', this);
            console.log('Button is visible:', $(this).is(':visible'));
            console.log('Button is enabled:', !$(this).prop('disabled'));

            try {
                // Hide the first modal using jQuery/Bootstrap
                $('#resumeRequiredModal').modal('hide');
                console.log('First modal hiding...');

                // Wait for first modal to fully hide, then show upload modal
                $('#resumeRequiredModal').on('hidden.bs.modal', function() {
                    // Remove this event listener after first use
                    $(this).off('hidden.bs.modal');

                    // Show the upload modal
                    $('#resumeUploadModal').modal('show');
                    console.log('Upload modal shown');
                });

            } catch (error) {
                console.error('Error in upload button handler:', error);
                alert('Error opening upload form: ' + error.message);
            }
        });

        console.log('Event listener attached to Upload Resume button');

        // Handle form submission with loading state
        $('#uploadResumeForm').on('submit', function(e) {
            const submitBtn = $('#submitResumeBtn');
            submitBtn.prop('disabled', true);
            submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i>Uploading...');
        });

        // File size validation
        $('#resume-file').on('change', function(e) {
            const file = this.files[0];
            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    alert('File size must be less than 5MB');
                    $(this).val('');
                }
            }
        });

        // ========== FILTER FORM HANDLERS ==========

        // Quick filter badges
        $('.badge[href]').on('click', function(e) {
            e.preventDefault();
            window.location.href = $(this).attr('href');
        });

        // Real-time search with debounce
        let searchTimeout;
        $('#q').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                $('#pwd-filters-form').submit();
            }, 800);
        });

        // Auto-submit form when filters change
        $('#pwd-filters-form select, #pwd-filters-form input[type="checkbox"]').on('change', function() {
            $('#pwd-filters-form').submit();
        });

        // Show loading state when form is submitting
        $('#pwd-filters-form').on('submit', function() {
            const submitBtn = $(this).find('button[type="submit"]');
            submitBtn.html('<i class="fas fa-spinner fa-spin me-1"></i> Searching...');
            submitBtn.prop('disabled', true);
        });
    });
    }
</script>
@endsection
