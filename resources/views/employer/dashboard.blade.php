@extends('employer.layouts.employer')

@section('title', 'Employer Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-1 mb-2 mb-md-3 border-bottom gap-1 gap-md-0">
    <div>
        <h1 class="h2 mb-1 mb-md-1">Employer Dashboard</h1>
        <p class="text-muted mb-0 mt-1 mt-md-0">
            Welcome back, {{ Auth::user()->name }}!
            @if(Auth::user()->isEmployerVerified())
                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Verified</span>
            @else
                <span class="badge bg-warning"><i class="fas fa-clock"></i> {{ $verificationStatus }}</span>
            @endif
        </p>
    </div>
    <div class="btn-toolbar mb-2 mb-md-0">
        @if(Auth::user()->isEmployerVerified())
            <a href="{{ route('employer.job-postings.create') }}" class="btn btn-primary me-2">
                <i class="fas fa-plus"></i> Post New Job
            </a>
            <a href="{{ route('employer.analytics.overview') }}" class="btn btn-outline-info">
                <i class="fas fa-chart-line"></i> Analytics
            </a>
        @else
            @if($stats['can_apply_verification'] ?? false)
                <a href="{{ route('employer.verification.apply') }}" class="btn btn-success me-2">
                    <i class="fas fa-shield-alt"></i> Apply for Verification
                </a>
            @endif
            <a href="{{ route('employer.profile.edit') }}" class="btn btn-outline-primary">
                <i class="fas fa-user-edit"></i> Complete Profile
            </a>
        @endif
    </div>
</div>

<!-- Alerts Section -->
@if(isset($alerts) && count($alerts) > 0)
    <div class="row mb-4">
        <div class="col-12">
            @foreach($alerts as $alert)
                <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert">
                    {!! $alert['message'] !!}
                    @if(isset($alert['action']))
                        <a href="{{ $alert['action'] }}" class="alert-link">View Details</a>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endforeach
        </div>
    </div>
@endif

<!-- Quick Stats -->
<div class="row mb-4">
    @if(Auth::user()->isEmployerVerified())
        <!-- Verified Employer Stats -->
        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Active Jobs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_jobs'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_applications'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Response Rate</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['response_rate'] ?? 0 }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Profile Completion</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['profile_completion'] ?? 0 }}%</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Unverified Employer Stats -->
        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-shield-alt fa-3x text-warning mb-3"></i>
                    <h5 class="card-title">Verification Status</h5>
                    <p class="card-text">{{ $stats['message'] ?? 'Complete verification to access all features' }}</p>
                    <div class="progress mb-3">
                        <div class="progress-bar" role="progressbar" style="width: {{ $stats['profile_completion'] ?? 0 }}%">
                            {{ $stats['profile_completion'] ?? 0 }}%
                        </div>
                    </div>
                    @if($stats['can_apply_verification'] ?? false)
                        <a href="{{ route('employer.verification.apply') }}" class="btn btn-primary">
                            Apply for Verification
                        </a>
                    @else
                        <a href="{{ route('employer.profile.edit') }}" class="btn btn-outline-primary">
                            Complete Profile
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-tasks fa-3x text-info mb-3"></i>
                    <h5 class="card-title">Quick Actions</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('employer.profile.edit') }}" class="btn btn-outline-primary">
                            <i class="fas fa-user-edit"></i> Update Profile
                        </a>
                        <a href="{{ route('employer.verification.requirements') }}" class="btn btn-outline-info">
                            <i class="fas fa-list-alt"></i> View Requirements
                        </a>
                        <a href="{{ route('employer.job-drafts.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-alt"></i> Create Job Draft
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@if(Auth::user()->isEmployerVerified())
<!-- Recent Activity Section -->
<div class="row">
    <!-- Recent Applications -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-users text-primary"></i> Recent Applications
                    </h6>
                    @if(isset($recentApplications) && $recentApplications->count() > 0)
                        <span class="badge bg-primary">{{ $recentApplications->count() }} new</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($recentApplications) && $recentApplications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentApplications as $application)
                            <a href="{{ route('employer.applications.show', $application) }}"
                               class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-2">
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                                 style="width: 40px; height: 40px; flex-shrink: 0;">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $application->user->name ?? 'Applicant' }}</h6>
                                                <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        <p class="mb-1 small text-muted">
                                            <i class="fas fa-briefcase"></i>
                                            Applied for <strong>{{ $application->jobPosting->title ?? 'Job' }}</strong>
                                        </p>
                                        @if($application->user->pwdProfile)
                                            <p class="mb-0 small text-muted">
                                                <i class="fas fa-wheelchair"></i>
                                                {{ $application->user->pwdProfile->disability_type ?? 'PWD Candidate' }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-end ms-3">
                                        <span class="badge bg-{{ $application->status === 'pending' ? 'warning' : ($application->status === 'approved' ? 'success' : ($application->status === 'shortlisted' ? 'info' : 'danger')) }}">
                                            {{ ucfirst($application->status) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="card-footer bg-white border-top-0 py-3">
                        <a href="{{ route('employer.applications.index') }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-list"></i> View All Applications
                        </a>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-3">No applications yet</p>
                        <a href="{{ route('employer.job-postings.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Post Your First Job
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div class="col-12 col-lg-6 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-dark">
                        <i class="fas fa-calendar-alt text-warning"></i> Upcoming Deadlines
                    </h6>
                    @if(isset($upcomingDeadlines) && $upcomingDeadlines->count() > 0)
                        <span class="badge bg-warning">{{ $upcomingDeadlines->count() }} jobs</span>
                    @endif
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($upcomingDeadlines) && $upcomingDeadlines->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingDeadlines as $job)
                            <a href="{{ route('employer.job-postings.show', $job) }}"
                               class="list-group-item list-group-item-action border-0 py-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-2">{{ $job->title }}</h6>
                                        <div class="mb-2">
                                            <span class="badge bg-{{ $job->application_deadline->diffInDays(now()) <= 2 ? 'danger' : 'warning' }} me-2">
                                                <i class="fas fa-clock"></i>
                                                {{ $job->application_deadline->diffForHumans() }}
                                            </span>
                                            <small class="text-muted">
                                                Deadline: {{ $job->application_deadline->format('M j, Y') }}
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <span class="badge bg-primary">
                                                <i class="fas fa-file-alt"></i>
                                                {{ $job->applications_count ?? 0 }} applications
                                            </span>
                                            <span class="badge bg-info">
                                                <i class="fas fa-eye"></i>
                                                {{ $job->views ?? 0 }} views
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-end ms-3">
                                        @if($job->application_deadline->diffInDays(now()) <= 2)
                                            <i class="fas fa-exclamation-triangle text-danger"></i>
                                        @else
                                            <i class="fas fa-chevron-right text-muted"></i>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="card-footer bg-white border-top-0 py-3">
                        <a href="{{ route('employer.job-postings.index') }}" class="btn btn-sm btn-outline-warning w-100">
                            <i class="fas fa-briefcase"></i> Manage All Jobs
                        </a>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                        <p class="text-muted mb-3">No upcoming deadlines</p>
                        <p class="small text-muted">All your job postings are up to date!</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 col-md-3 mb-3">
                        <a href="{{ route('employer.job-postings.create') }}" class="btn btn-outline-primary btn-lg w-100 h-100 py-3">
                            <i class="fas fa-plus fa-2x mb-2"></i><br>
                            Post New Job
                        </a>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <a href="{{ route('employer.applications.index') }}" class="btn btn-outline-success btn-lg w-100 h-100 py-3">
                            <i class="fas fa-users fa-2x mb-2"></i><br>
                            View Applications
                        </a>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <a href="{{ route('employer.analytics.overview') }}" class="btn btn-outline-info btn-lg w-100 h-100 py-3">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                            View Analytics
                        </a>
                    </div>
                    <div class="col-12 col-md-3 mb-3">
                        <a href="{{ route('employer.profile.edit') }}" class="btn btn-outline-warning btn-lg w-100 h-100 py-3">
                            <i class="fas fa-user-edit fa-2x mb-2"></i><br>
                            Update Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
