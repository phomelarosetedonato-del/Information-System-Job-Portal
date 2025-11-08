@extends('employer.layouts.employer')

@section('title', 'Employer Dashboard')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Employer Dashboard</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        @if(Auth::user()->isEmployerVerified())
            <a href="{{ route('employer.job-postings.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Post New Job
            </a>
        @else
            <a href="{{ route('employer.verification.apply') }}" class="btn btn-success">
                <i class="fas fa-shield-alt"></i> Apply for Verification
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
        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
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

        <div class="col-xl-3 col-md-6 mb-4">
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
        <div class="col-md-6 mb-4">
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

        <div class="col-md-6 mb-4">
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
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Recent Applications</h6>
            </div>
            <div class="card-body">
                @if(isset($recentApplications) && $recentApplications->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentApplications as $application)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $application->user->name ?? 'Applicant' }}</h6>
                                    <small class="text-muted">Applied for {{ $application->jobPosting->title }}</small>
                                </div>
                                <span class="badge bg-{{ $application->status === 'pending' ? 'warning' : ($application->status === 'approved' ? 'success' : 'danger') }}">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('employer.applications.index') }}" class="btn btn-sm btn-primary mt-3">View All Applications</a>
                @else
                    <p class="text-muted text-center">No recent applications</p>
                    <div class="text-center">
                        <a href="{{ route('employer.job-postings.create') }}" class="btn btn-primary">Post Your First Job</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Upcoming Deadlines -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Upcoming Deadlines</h6>
            </div>
            <div class="card-body">
                @if(isset($upcomingDeadlines) && $upcomingDeadlines->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($upcomingDeadlines as $job)
                            <div class="list-group-item">
                                <h6 class="mb-1">{{ $job->title }}</h6>
                                <small class="text-muted">
                                    Deadline: {{ $job->application_deadline->format('M j, Y') }}
                                    ({{ $job->application_deadline->diffForHumans() }})
                                </small>
                                <div class="mt-2">
                                    <span class="badge bg-info">{{ $job->applications_count }} applications</span>
                                    <span class="badge bg-secondary">{{ $job->views }} views</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{ route('employer.job-postings.index') }}" class="btn btn-sm btn-primary mt-3">Manage Jobs</a>
                @else
                    <p class="text-muted text-center">No upcoming deadlines</p>
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
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('employer.job-postings.create') }}" class="btn btn-outline-primary btn-lg w-100 h-100 py-3">
                            <i class="fas fa-plus fa-2x mb-2"></i><br>
                            Post New Job
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('employer.applications.index') }}" class="btn btn-outline-success btn-lg w-100 h-100 py-3">
                            <i class="fas fa-users fa-2x mb-2"></i><br>
                            View Applications
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('employer.analytics.overview') }}" class="btn btn-outline-info btn-lg w-100 h-100 py-3">
                            <i class="fas fa-chart-bar fa-2x mb-2"></i><br>
                            View Analytics
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
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
