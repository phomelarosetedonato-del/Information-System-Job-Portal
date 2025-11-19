@extends('layouts.admin')

@section('title', 'Admin Dashboard - PWD System')

@section('content')
<!-- Skip Navigation for Accessibility -->
<a href="#main-content" class="sr-only sr-only-focusable bg-primary text-white p-2 position-absolute top-0 start-0" style="z-index: 9999; transform: translateY(-100%); transition: transform 0.3s;">
    Skip to main content
</a>

<div class="dashboard-container" id="main-content" tabindex="-1" role="main" aria-labelledby="dashboard-heading">
    <!-- Dashboard Header -->
    <div class="dashboard-header bg-white border-bottom py-3 sticky-top shadow-sm">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h4 mb-1 text-dark" id="dashboard-heading">
                        <i class="fas fa-universal-access me-2 text-primary" aria-hidden="true"></i>
                        Admin Dashboard - PWD System Alaminos City
                    </h1>
                    <p class="mb-0 text-muted small">System overview and management tools</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group">
                        <a href="{{ route('admin.security.report') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-shield-alt me-1"></i> Security Report
                        </a>
                        <a href="{{ route('admin.statistics') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-chart-bar me-1"></i> Analytics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="dashboard-content bg-light">
        <div class="container-fluid py-4">
            <!-- Session Messages & Alerts -->
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" aria-live="polite">
                    <i class="fas fa-check-circle me-2" aria-hidden="true"></i>
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close success message"></button>
                </div>
            @endif

            <!-- Notifications Widget -->
            @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-bell me-2 text-warning"></i>
                                    <strong>Recent Notifications</strong>
                                </div>
                                <div>
                                    <form method="POST" action="{{ route('notifications.mark-all-read') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">Mark all read</button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body p-2">
                                <ul class="list-group list-group-flush">
                                    @foreach(auth()->user()->unreadNotifications->take(6) as $notification)
                                        <li class="list-group-item d-flex justify-content-between align-items-start">
                                            <div>
                                                <div class="small">{{ $notification->data['message'] ?? (string) \Illuminate\Support\Str::limit(json_encode($notification->data), 80) }}</div>
                                                <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div>
                                                @if(!empty($notification->data['url']))
                                                    <a href="{{ $notification->data['url'] }}" class="btn btn-sm btn-outline-primary">View</a>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
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

            <!-- Welcome Banner -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="card-body py-4">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h2 class="text-white mb-2">
                                        <i class="fas fa-sun me-2"></i>Welcome back, {{ auth()->user()->name }}!
                                    </h2>
                                    <p class="text-white-50 mb-0">
                                        <i class="fas fa-calendar-day me-2"></i>{{ now()->format('l, F j, Y') }} |
                                        <i class="fas fa-clock ms-2 me-2"></i>{{ now()->format('g:i A') }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                    <div class="d-flex justify-content-md-end gap-2">
                                        <a href="{{ route('admin.statistics') }}" class="btn btn-light btn-sm">
                                            <i class="fas fa-chart-line me-1"></i> Full Analytics
                                        </a>
                                        <a href="{{ route('admin.security.report') }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-shield-alt me-1"></i> Security
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Statistics Cards -->
            <div class="row mb-4" role="region" aria-labelledby="stats-heading">
                <div class="col-12 mb-3">
                    <h3 class="h5 text-dark font-weight-bold">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>System Overview
                    </h3>
                </div>

                <!-- Job Postings Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="icon-circle bg-primary bg-opacity-10">
                                    <i class="fas fa-briefcase text-primary fa-2x"></i>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary">Active</span>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $stats['job_postings'] }}</h4>
                            <p class="text-muted mb-2 small">Job Postings</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-success">
                                    <i class="fas fa-check-circle me-1"></i>{{ $stats['active_jobs'] }} Active
                                </small>
                                <a href="{{ route('admin.job-postings.index') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Skill Trainings Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="icon-circle bg-success bg-opacity-10">
                                    <i class="fas fa-graduation-cap text-success fa-2x"></i>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">Programs</span>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $stats['skill_trainings'] }}</h4>
                            <p class="text-muted mb-2 small">Skill Trainings</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-info">
                                    <i class="fas fa-users me-1"></i>{{ $stats['active_trainings'] }} Enrolled
                                </small>
                                <a href="{{ route('admin.skill-trainings.index') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="icon-circle bg-info bg-opacity-10">
                                    <i class="fas fa-users text-info fa-2x"></i>
                                </div>
                                <span class="badge bg-info bg-opacity-10 text-info">Total</span>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $stats['total_users'] }}</h4>
                            <p class="text-muted mb-2 small">Total Users</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-primary">
                                    <i class="fas fa-wheelchair me-1"></i>{{ $stats['pwd_users'] }} PWD
                                </small>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Applications Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="icon-circle bg-warning bg-opacity-10">
                                    <i class="fas fa-file-alt text-warning fa-2x"></i>
                                </div>
                                <span class="badge bg-warning bg-opacity-10 text-warning">Pending</span>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $stats['pending_applications'] }}</h4>
                            <p class="text-muted mb-2 small">Pending Applications</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-success">
                                    <i class="fas fa-check me-1"></i>{{ $stats['approved_applications'] }} Approved
                                </small>
                                <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visual Analytics Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="h5 text-dark font-weight-bold mb-0">
                            <i class="fas fa-chart-line me-2 text-success"></i>Visual Analytics
                        </h3>
                        <a href="{{ route('admin.statistics') }}" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-expand me-1"></i>Full Report
                        </a>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <!-- System Overview Chart -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                            <h4 class="h6 mb-0 text-dark">
                                <i class="fas fa-chart-bar me-2 text-primary"></i>System Overview
                            </h4>
                        </div>
                        <div class="card-body p-2">
                            <canvas id="systemOverviewChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- User Distribution Pie Chart -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                            <h4 class="h6 mb-0 text-dark">
                                <i class="fas fa-users me-2 text-info"></i>User Distribution
                            </h4>
                        </div>
                        <div class="card-body p-2 d-flex align-items-center justify-content-center">
                            <canvas id="userDistributionChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Application Status Chart -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                            <h4 class="h6 mb-0 text-dark">
                                <i class="fas fa-file-alt me-2 text-success"></i>Applications
                            </h4>
                        </div>
                        <div class="card-body p-2">
                            <canvas id="applicationStatusChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Training Enrollments Chart -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-2">
                            <h4 class="h6 mb-0 text-dark">
                                <i class="fas fa-graduation-cap me-2 text-warning"></i>Training
                            </h4>
                        </div>
                        <div class="card-body p-2">
                            <canvas id="trainingEnrollmentsChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Metrics Section -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h3 class="h5 text-dark font-weight-bold">
                        <i class="fas fa-tasks me-2 text-info"></i>Key Performance Indicators
                    </h3>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-primary bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-briefcase fa-2x text-primary"></i>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $stats['active_jobs'] }}</h4>
                            <p class="text-muted mb-3 small">Active Job Postings</p>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ $stats['job_postings'] > 0 ? ($stats['active_jobs'] / $stats['job_postings'] * 100) : 0 }}%"
                                     aria-valuenow="{{ $stats['active_jobs'] }}"
                                     aria-valuemin="0"
                                     aria-valuemax="{{ $stats['job_postings'] }}">
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                {{ $stats['job_postings'] > 0 ? round(($stats['active_jobs'] / $stats['job_postings'] * 100)) : 0 }}% of total
                            </small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-warning bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $stats['pending_applications'] }}</h4>
                            <p class="text-muted mb-3 small">Pending Applications</p>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar"
                                     style="width: {{ ($stats['pending_applications'] + $stats['approved_applications']) > 0 ? ($stats['pending_applications'] / ($stats['pending_applications'] + $stats['approved_applications']) * 100) : 0 }}%"
                                     aria-valuenow="{{ $stats['pending_applications'] }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">Requires Review</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-success bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-check-circle fa-2x text-success"></i>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $stats['approved_applications'] }}</h4>
                            <p class="text-muted mb-3 small">Approved Applications</p>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: 90%"
                                     aria-valuenow="{{ $stats['approved_applications'] }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">Successfully Processed</small>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body text-center">
                            <div class="icon-circle bg-info bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                <i class="fas fa-user-graduate fa-2x text-info"></i>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $stats['active_trainings'] }}</h4>
                            <p class="text-muted mb-3 small">Active Enrollments</p>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" role="progressbar"
                                     style="width: 85%"
                                     aria-valuenow="{{ $stats['active_trainings'] }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">Currently Training</small>
                        </div>
                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Section -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h3 class="h5 text-dark font-weight-bold">
                        <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
                    </h3>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <a href="{{ route('admin.job-postings.create') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center py-4">
                                <div class="icon-circle bg-primary bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-briefcase fa-2x text-primary"></i>
                                </div>
                                <h5 class="text-dark mb-2">Create Job</h5>
                                <p class="text-muted small mb-0">Post a new job opportunity</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <a href="{{ route('admin.skill-trainings.create') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center py-4">
                                <div class="icon-circle bg-success bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-graduation-cap fa-2x text-success"></i>
                                </div>
                                <h5 class="text-dark mb-2">Create Training</h5>
                                <p class="text-muted small mb-0">Add new training program</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <a href="{{ route('admin.announcements.create') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center py-4">
                                <div class="icon-circle bg-info bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-bullhorn fa-2x text-info"></i>
                                </div>
                                <h5 class="text-dark mb-2">New Announcement</h5>
                                <p class="text-muted small mb-0">Broadcast system message</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <a href="{{ route('admin.users.create') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center py-4">
                                <div class="icon-circle bg-warning bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-user-plus fa-2x text-warning"></i>
                                </div>
                                <h5 class="text-dark mb-2">Create User</h5>
                                <p class="text-muted small mb-0">Add new system user</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="row mb-4">
                <div class="col-12 mb-3">
                    <h3 class="h5 text-dark font-weight-bold">
                        <i class="fas fa-history me-2 text-danger"></i>Recent Activity
                    </h3>
                </div>
            </div>

            <!-- Recent Activity & Security Alerts -->
            <div class="row mb-4">
                <!-- Recent Applications -->
                <div class="col-xl-3 col-lg-6 mb-4">
                    <div class="card shadow-sm border-0" style="max-height: 400px;">
                        <div class="card-header bg-white border-bottom py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h6 mb-0 text-dark">Recent Applications</h2>
                                <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-outline-primary">All</a>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 320px; overflow-y: auto;">
                            @if($recentApplications->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentApplications as $application)
                                        <div class="list-group-item border-0 p-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h3 class="h6 mb-1 text-dark">{{ $application->user->name ?? 'Unknown User' }}</h3>
                                                    <p class="mb-1 small text-muted">Applied for: {{ $application->jobPosting->title ?? 'Unknown Job' }}</p>
                                                    <span class="badge bg-{{ $application->status == 'pending' ? 'warning' : ($application->status == 'approved' ? 'success' : 'danger') }}">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-1 mx-3">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No recent job applications.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Enrollments -->
                <div class="col-xl-3 col-lg-6 mb-4">
                    <div class="card shadow-sm border-0" style="max-height: 400px;">
                        <div class="card-header bg-white border-bottom py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h6 mb-0 text-dark">Recent Enrollments</h2>
                                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-sm btn-outline-success">All</a>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 320px; overflow-y: auto;">
                            @if($recentEnrollments->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentEnrollments as $enrollment)
                                        <div class="list-group-item border-0 p-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h3 class="h6 mb-1 text-dark">{{ $enrollment->user->name ?? 'Unknown User' }}</h3>
                                                    <p class="mb-1 small text-muted">Enrolled in: {{ $enrollment->skillTraining->title ?? 'Unknown Training' }}</p>
                                                    <span class="badge bg-{{ $enrollment->status == 'enrolled' ? 'success' : ($enrollment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                        {{ ucfirst($enrollment->status) }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">{{ $enrollment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-1 mx-3">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-graduation-cap fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No recent enrollments.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="col-xl-3 col-lg-6 mb-4">
                    <div class="card shadow-sm border-0" style="max-height: 400px;">
                        <div class="card-header bg-white border-bottom py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h6 mb-0 text-dark">Recent Users</h2>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-info">All</a>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 320px; overflow-y: auto;">
                            @if($recentUsers->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentUsers as $user)
                                        <div class="list-group-item border-0 p-3">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h3 class="h6 mb-1 text-dark">{{ $user->name }}</h3>
                                                    <p class="mb-1 small text-muted">{{ $user->email }}</p>
                                                    <span class="badge {{ $user->role === 'admin' ? 'bg-warning' : ($user->role === 'pwd' ? 'bg-success' : 'bg-secondary') }}">
                                                        {{ ucfirst($user->role) }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-1 mx-3">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-users fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No recent users.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Security Alerts -->
                <div class="col-xl-3 col-lg-6 mb-4">
                    <div class="card shadow-sm border-warning" style="max-height: 400px;">
                        <div class="card-header bg-warning text-dark border-bottom py-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h6 mb-0">Security Alerts</h2>
                                <a href="{{ route('admin.security.report') }}" class="btn btn-sm btn-warning">Report</a>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 320px; overflow-y: auto;">
                            @if($securityAlerts->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($securityAlerts as $user)
                                        <div class="list-group-item border-0 p-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h3 class="h6 mb-1 text-danger">{{ $user->name }}</h3>
                                                    <p class="mb-1 small">{{ $user->email }}</p>
                                                    @if($user->failed_login_attempts >= 3)
                                                        <small class="text-muted d-block">Failed logins: {{ $user->failed_login_attempts }}</small>
                                                    @endif
                                                    @if(!$user->password_meets_current_standards)
                                                        <small class="text-muted d-block">Weak password</small>
                                                    @endif
                                                    @if($user->account_locked_until)
                                                        <small class="text-muted d-block">Account locked</small>
                                                    @endif
                                                </div>
                                                <span class="badge bg-danger">Alert</span>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-1 mx-3">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                    <p class="text-muted mb-0">No security alerts</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Admin Tools -->
            <div class="row" role="navigation" aria-labelledby="admin-tools-heading">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h2 class="h5 mb-0 text-dark" id="admin-tools-heading">
                                <i class="fas fa-tools me-2 text-primary"></i>Admin Management Tools
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <!-- User Management -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-hover border-primary h-100 text-center">
                                        <div class="card-body py-4">
                                            <i class="fas fa-users fa-2x text-primary mb-3" aria-hidden="true"></i>
                                            <h3 class="h6 text-dark mb-2">User Management</h3>
                                            <p class="small text-muted mb-3">Manage all system users and roles</p>
                                            <div class="d-grid">
                                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-cog me-1"></i> Manage Users
                                                </a>
                                            </div>
                                            <div class="mt-3 small text-muted">
                                                <span class="badge bg-primary">{{ $stats['total_users'] }} Total</span>
                                                <span class="badge bg-success">{{ $stats['pwd_users'] }} PWD</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Center -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-hover border-warning h-100 text-center">
                                        <div class="card-body py-4">
                                            <i class="fas fa-shield-alt fa-2x text-warning mb-3" aria-hidden="true"></i>
                                            <h3 class="h6 text-dark mb-2">Security Center</h3>
                                            <p class="small text-muted mb-3">Monitor security and user activity</p>
                                            <div class="d-grid">
                                                <a href="{{ route('admin.security.report') }}" class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-chart-bar me-1"></i> Security Report
                                                </a>
                                            </div>
                                            <div class="mt-3 small text-muted">
                                                <span class="badge bg-warning">{{ $stats['users_needing_security'] }} Alerts</span>
                                                <span class="badge bg-danger">{{ $stats['locked_users'] }} Locked</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Analytics & Reports -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-hover border-success h-100 text-center">
                                        <div class="card-body py-4">
                                            <i class="fas fa-chart-bar fa-2x text-success mb-3" aria-hidden="true"></i>
                                            <h3 class="h6 text-dark mb-2">Analytics & Reports</h3>
                                            <p class="small text-muted mb-3">View system analytics and reports</p>
                                            <div class="d-grid">
                                                <a href="{{ route('admin.statistics') }}" class="btn btn-outline-success btn-sm">
                                                    <i class="fas fa-chart-line me-1"></i> View Analytics
                                                </a>
                                            </div>
                                            <div class="mt-3 small text-muted">
                                                <span class="badge bg-info">{{ $stats['pending_applications'] }} Pending</span>
                                                <span class="badge bg-success">{{ $stats['active_trainings'] }} Active</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Application Management -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="card card-hover border-info h-100 text-center">
                                        <div class="card-body py-4">
                                            <i class="fas fa-clipboard-list fa-2x text-info mb-3" aria-hidden="true"></i>
                                            <h3 class="h6 text-dark mb-2">Applications</h3>
                                            <p class="small text-muted mb-3">Manage job applications</p>
                                            <div class="d-grid">
                                                <a href="{{ route('admin.applications.index') }}" class="btn btn-outline-info btn-sm">
                                                    <i class="fas fa-list me-1"></i> Manage Applications
                                                </a>
                                            </div>
                                            <div class="mt-3 small text-muted">
                                                <span class="badge bg-warning">{{ $stats['pending_applications'] }} Pending</span>
                                                <span class="badge bg-success">{{ $stats['approved_applications'] }} Approved</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accessibility Widget -->
    <div class="accessibility-widget" role="complementary" aria-label="Accessibility Tools">
        <button class="accessibility-toggle"
                id="accessibility-toggle"
                aria-label="Toggle accessibility menu"
                aria-expanded="false"
                aria-controls="accessibility-menu">
            <i class="fas fa-universal-access fa-2x"></i>
        </button>

        <div class="accessibility-menu"
             id="accessibility-menu"
             role="menu"
             aria-labelledby="accessibility-toggle">
            <h3 class="h6 mb-3 fw-bold text-primary">
                <i class="fas fa-universal-access me-2"></i>Accessibility Options
            </h3>

            <div class="accessibility-option"
                 id="increase-font"
                 role="menuitem"
                 tabindex="0"
                 aria-label="Increase text size">
                <div>
                    <i class="fas fa-text-height me-2"></i>
                    <span>Increase Text Size</span>
                </div>
                <i class="fas fa-plus"></i>
            </div>

            <div class="accessibility-option"
                 id="decrease-font"
                 role="menuitem"
                 tabindex="0"
                 aria-label="Decrease text size">
                <div>
                    <i class="fas fa-text-height me-2"></i>
                    <span>Decrease Text Size</span>
                </div>
                <i class="fas fa-minus"></i>
            </div>

            <div class="accessibility-option"
                 id="high-contrast"
                 role="menuitem"
                 tabindex="0"
                 aria-label="Toggle high contrast mode">
                <div>
                    <i class="fas fa-adjust me-2"></i>
                    <span>High Contrast</span>
                </div>
                <i class="fas fa-toggle-off toggle-icon"></i>
            </div>

            <div class="accessibility-option"
                 id="dyslexic-font"
                 role="menuitem"
                 tabindex="0"
                 aria-label="Toggle dyslexic friendly font">
                <div>
                    <i class="fas fa-font me-2"></i>
                    <span>Dyslexic Friendly Font</span>
                </div>
                <i class="fas fa-toggle-off toggle-icon"></i>
            </div>

            <div class="accessibility-option"
                 id="reset-accessibility"
                 role="menuitem"
                 tabindex="0"
                 aria-label="Reset all accessibility settings">
                <div>
                    <i class="fas fa-undo me-2"></i>
                    <span>Reset All Settings</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card-hover {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
    }

    .card-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .dashboard-content {
        min-height: calc(100vh - 200px);
    }

    .border-bottom {
        border-bottom: 1px solid #e9ecef !important;
    }

    .dashboard-header.sticky-top {
        position: sticky;
        top: 0;
        z-index: 1020;
        background-color: #fff;
    }

    /* Scrollbar styling for recent activity cards */
    .card-body::-webkit-scrollbar {
        width: 6px;
    }

    .card-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .card-body::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .card-body::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Green color scheme for PWD system */
    .text-primary { color: #2E8B57 !important; }
    .text-success { color: #1A5D34 !important; }
    .text-info { color: #4CAF50 !important; }
    .text-warning { color: #1C3D2A !important; }

    .border-primary { border-color: #2E8B57 !important; }
    .border-success { border-color: #1A5D34 !important; }
    .border-info { border-color: #4CAF50 !important; }
    .border-warning { border-color: #1C3D2A !important; }

    .btn-outline-primary {
        border-color: #2E8B57;
        color: #2E8B57;
    }
    .btn-outline-primary:hover {
        background-color: #2E8B57;
        border-color: #2E8B57;
        color: white;
    }

    .btn-outline-success {
        border-color: #1A5D34;
        color: #1A5D34;
    }
    .btn-outline-success:hover {
        background-color: #1A5D34;
        border-color: #1A5D34;
        color: white;
    }

    .btn-outline-info {
        border-color: #4CAF50;
        color: #4CAF50;
    }
    .btn-outline-info:hover {
        background-color: #4CAF50;
        border-color: #4CAF50;
        color: white;
    }

    .btn-outline-warning {
        border-color: #1C3D2A;
        color: #1C3D2A;
    }
    .btn-outline-warning:hover {
        background-color: #1C3D2A;
        border-color: #1C3D2A;
        color: white;
    }

    /* ====================================
       ACCESSIBILITY WIDGET STYLES
       ==================================== */

    .accessibility-widget {
        position: fixed !important;
        right: 20px !important;
        bottom: 20px !important;
        z-index: 9999 !important;
        display: block !important;
        visibility: visible !important;
        opacity: 1 !important;
    }

    .accessibility-toggle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #2E8B57 !important;
        color: white !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3) !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        cursor: pointer !important;
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
        padding: 0 !important;
    }

    .accessibility-toggle:hover,
    .accessibility-toggle:focus {
        background-color: #1A5D34 !important;
        transform: scale(1.1);
        animation: none;
        outline: 3px solid #2E8B57;
        outline-offset: 3px;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        50% {
            box-shadow: 0 4px 20px rgba(46, 139, 87, 0.6);
        }
    }

    .accessibility-menu {
        position: absolute;
        bottom: 75px;
        right: 0;
        background-color: white;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        padding: 1.5rem;
        min-width: 300px;
        max-width: 320px;
        display: none;
        border: 2px solid #2E8B57;
        z-index: 10000 !important;
        pointer-events: auto !important;
    }

    .accessibility-menu.active {
        display: block !important;
        animation: slideUpFadeIn 0.3s ease;
        pointer-events: auto !important;
    }

    @keyframes slideUpFadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .accessibility-option {
        padding: 0.875rem;
        margin-bottom: 0.5rem;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer !important;
        pointer-events: auto !important;
        transition: all 0.2s ease;
        border: 1px solid #e9ecef;
        background-color: white;
        user-select: none;
        -webkit-tap-highlight-color: rgba(46, 139, 87, 0.2);
    }

    .accessibility-option:hover {
        background-color: #f0f8f5;
        border-color: #2E8B57;
        transform: translateX(5px);
    }

    .accessibility-option:active {
        background-color: #c3e6cb;
        transform: scale(0.98);
    }

    .accessibility-option.active {
        background-color: #d4edda;
        border-color: #2E8B57;
        font-weight: 600;
    }

    .accessibility-option i {
        transition: transform 0.2s ease;
        pointer-events: none;
    }

    .accessibility-option:hover i {
        transform: scale(1.2);
    }

    .accessibility-option div {
        pointer-events: none;
    }

    .accessibility-option span {
        pointer-events: none;
    }

    /* Font Size Classes */
    body.font-large {
        font-size: 18px !important;
    }

    body.font-xlarge {
        font-size: 20px !important;
    }

    body.font-xxlarge {
        font-size: 22px !important;
    }

    /* High Contrast Mode */
    body.high-contrast {
        background-color: #000000 !important;
        color: #FFFFFF !important;
    }

    body.high-contrast .card,
    body.high-contrast .card-header,
    body.high-contrast .card-body,
    body.high-contrast .list-group-item,
    body.high-contrast .dashboard-header {
        background-color: #1A1A1A !important;
        color: #FFFFFF !important;
        border-color: #FFFFFF !important;
    }

    body.high-contrast .text-muted,
    body.high-contrast .text-secondary,
    body.high-contrast small {
        color: #CCCCCC !important;
    }

    body.high-contrast .btn {
        border: 2px solid #FFFFFF !important;
        color: #FFFFFF !important;
    }

    body.high-contrast a {
        color: #4DA6FF !important;
        text-decoration: underline;
    }

    /* Dyslexic Font */
    body.dyslexic-font,
    body.dyslexic-font * {
        font-family: 'Comic Sans MS', 'OpenDyslexic', 'Arial', sans-serif !important;
        letter-spacing: 0.05em;
        line-height: 1.6;
    }

    /* Focus Indicators */
    .accessibility-option:focus {
        outline: 3px solid #2E8B57;
        outline-offset: 2px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .accessibility-widget {
            right: 10px;
            bottom: 10px;
        }

        .accessibility-toggle {
            width: 50px;
            height: 50px;
        }

        .accessibility-toggle i {
            font-size: 1.5rem;
        }

        .accessibility-menu {
            min-width: 280px;
            right: -10px;
        }
    }

    /* Screen Reader Only */
    .sr-only {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .sr-only-focusable:focus {
        position: static;
        width: auto;
        height: auto;
        overflow: visible;
        clip: auto;
        white-space: normal;
    }

    /* HIGH CONTRAST MODE STYLES */
    body.high-contrast {
        background-color: #000000 !important;
        color: #FFFFFF !important;
    }

    body.high-contrast .card,
    body.high-contrast .card-body,
    body.high-contrast .card-header {
        background-color: #000000 !important;
        color: #FFFF00 !important;
        border: 2px solid #FFFFFF !important;
    }

    body.high-contrast .btn {
        background-color: #000000 !important;
        color: #FFFF00 !important;
        border: 2px solid #FFFF00 !important;
    }

    body.high-contrast .btn:hover,
    body.high-contrast .btn:focus {
        background-color: #FFFF00 !important;
        color: #000000 !important;
        border: 2px solid #FFFF00 !important;
    }

    body.high-contrast a {
        color: #00FFFF !important;
        text-decoration: underline !important;
    }

    body.high-contrast .text-muted,
    body.high-contrast .text-secondary {
        color: #FFFFFF !important;
    }

    body.high-contrast .bg-primary,
    body.high-contrast .bg-success,
    body.high-contrast .bg-info,
    body.high-contrast .bg-warning,
    body.high-contrast .bg-danger {
        background-color: #000000 !important;
        color: #FFFF00 !important;
        border: 2px solid #FFFFFF !important;
    }

    body.high-contrast .badge {
        background-color: #FFFF00 !important;
        color: #000000 !important;
        border: 2px solid #FFFFFF !important;
    }

    body.high-contrast .navbar,
    body.high-contrast .sidebar {
        background-color: #000000 !important;
        border: 2px solid #FFFFFF !important;
    }

    body.high-contrast .table {
        color: #FFFFFF !important;
        border: 2px solid #FFFFFF !important;
    }

    body.high-contrast .table th,
    body.high-contrast .table td {
        border: 1px solid #FFFFFF !important;
        background-color: #000000 !important;
    }

    body.high-contrast input,
    body.high-contrast select,
    body.high-contrast textarea {
        background-color: #000000 !important;
        color: #FFFF00 !important;
        border: 2px solid #FFFFFF !important;
    }

    /* DYSLEXIC FRIENDLY FONT */
    body.dyslexic-font,
    body.dyslexic-font * {
        font-family: 'Comic Sans MS', 'OpenDyslexic', 'Arial', sans-serif !important;
        letter-spacing: 0.05em !important;
        word-spacing: 0.16em !important;
        line-height: 1.8 !important;
    }

    body.dyslexic-font h1,
    body.dyslexic-font h2,
    body.dyslexic-font h3,
    body.dyslexic-font h4,
    body.dyslexic-font h5,
    body.dyslexic-font h6 {
        font-weight: 600 !important;
    }

    body.dyslexic-font p {
        text-align: left !important;
    }

    /* Active state for accessibility options */
    .accessibility-option.active {
        background-color: #d4edda !important;
        border-left: 4px solid #28a745 !important;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss success alerts after 8 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-success').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 8000);

    // Fix skip link functionality
    const skipLink = document.querySelector('.sr-only-focusable');
    if (skipLink) {
        skipLink.addEventListener('focus', function() {
            this.style.transform = 'translateY(0)';
        });

        skipLink.addEventListener('blur', function() {
            this.style.transform = 'translateY(-100%)';
        });
    }

    // Add accessibility enhancements
    document.querySelectorAll('.card-hover').forEach(card => {
        card.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                const link = this.querySelector('a');
                if (link) link.click();
            }
        });
    });

    // ==========================================
    // ACCESSIBILITY WIDGET FUNCTIONALITY
    // ==========================================

    console.log('=== ACCESSIBILITY WIDGET INITIALIZATION START ===');

    const accessibilityToggle = document.getElementById('accessibility-toggle');
    const accessibilityMenu = document.getElementById('accessibility-menu');
    let currentFontSize = 16; // Default font size in pixels

    console.log('Toggle button:', accessibilityToggle ? 'FOUND' : 'NOT FOUND');
    console.log('Menu:', accessibilityMenu ? 'FOUND' : 'NOT FOUND');

    if (!accessibilityToggle || !accessibilityMenu) {
        console.error('CRITICAL: Accessibility widget elements missing!');
        return;
    }

    // Load saved accessibility preferences on page load
    loadAccessibilityPreferences();

    // Toggle accessibility menu open/close with multiple event types
    const toggleMenu = function(e) {
        e.preventDefault();
        e.stopPropagation();

        console.log('Toggle button CLICKED!');

        const isExpanded = accessibilityMenu.classList.toggle('active');
        accessibilityToggle.setAttribute('aria-expanded', isExpanded);

        console.log('Menu state changed to:', isExpanded ? 'OPEN' : 'CLOSED');

        // Visual confirmation
        if (isExpanded) {
            accessibilityMenu.style.display = 'block';
        }

        announceToScreenReader(isExpanded ? 'Accessibility menu opened' : 'Accessibility menu closed');
    };

    accessibilityToggle.addEventListener('click', toggleMenu, true);
    accessibilityToggle.addEventListener('touchend', function(e) {
        e.preventDefault();
        toggleMenu(e);
    }, true);

    console.log('Toggle button listeners attached');

    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.accessibility-widget')) {
            accessibilityMenu.classList.remove('active');
            accessibilityToggle.setAttribute('aria-expanded', 'false');
        }
    }, true);

    // Prevent menu from closing when clicking inside
    accessibilityMenu.addEventListener('click', function(e) {
        e.stopPropagation();
    }, true);

    // Increase font size
    const increaseFontBtn = document.getElementById('increase-font');
    if (increaseFontBtn) {
        console.log('✓ Increase font button found');

        const increaseFontHandler = function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('>>> INCREASE FONT CLICKED! Current:', currentFontSize);

            currentFontSize = Math.min(currentFontSize + 2, 24); // Max 24px
            document.body.style.fontSize = currentFontSize + 'px';
            localStorage.setItem('accessibilityFontSize', currentFontSize);

            console.log('>>> Font size changed to:', currentFontSize);
            announceToScreenReader('Text size increased to ' + currentFontSize + ' pixels');

            // Strong visual feedback
            increaseFontBtn.style.transform = 'scale(1.1)';
            increaseFontBtn.style.backgroundColor = '#28a745';
            increaseFontBtn.style.color = 'white';
            setTimeout(() => {
                increaseFontBtn.style.transform = '';
                increaseFontBtn.style.backgroundColor = '';
                increaseFontBtn.style.color = '';
            }, 300);
        };

        increaseFontBtn.addEventListener('click', increaseFontHandler, true);
        increaseFontBtn.addEventListener('touchstart', increaseFontHandler, true);

        // Also add mousedown for better responsiveness
        increaseFontBtn.addEventListener('mousedown', function(e) {
            console.log('>>> Increase font MOUSEDOWN detected');
        });
    } else {
        console.error('✗ Increase font button NOT found');
    }

    // Decrease font size
    const decreaseFontBtn = document.getElementById('decrease-font');
    if (decreaseFontBtn) {
        console.log('✓ Decrease font button found');

        const decreaseFontHandler = function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('>>> DECREASE FONT CLICKED! Current:', currentFontSize);

            currentFontSize = Math.max(currentFontSize - 2, 12); // Min 12px
            document.body.style.fontSize = currentFontSize + 'px';
            localStorage.setItem('accessibilityFontSize', currentFontSize);

            console.log('>>> Font size changed to:', currentFontSize);
            announceToScreenReader('Text size decreased to ' + currentFontSize + ' pixels');

            // Strong visual feedback
            decreaseFontBtn.style.transform = 'scale(1.1)';
            decreaseFontBtn.style.backgroundColor = '#28a745';
            decreaseFontBtn.style.color = 'white';
            setTimeout(() => {
                decreaseFontBtn.style.transform = '';
                decreaseFontBtn.style.backgroundColor = '';
                decreaseFontBtn.style.color = '';
            }, 300);
        };

        decreaseFontBtn.addEventListener('click', decreaseFontHandler, true);
        decreaseFontBtn.addEventListener('touchstart', decreaseFontHandler, true);
    } else {
        console.error('✗ Decrease font button NOT found');
    }

    // High contrast toggle
    const highContrastBtn = document.getElementById('high-contrast');
    if (highContrastBtn) {
        console.log('✓ High contrast button found');

        const highContrastHandler = function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('>>> HIGH CONTRAST CLICKED!');

            const isActive = document.body.classList.toggle('high-contrast');
            highContrastBtn.classList.toggle('active', isActive);

            const toggleIcon = highContrastBtn.querySelector('.toggle-icon');
            if (toggleIcon) {
                if (isActive) {
                    toggleIcon.classList.remove('fa-toggle-off');
                    toggleIcon.classList.add('fa-toggle-on');
                } else {
                    toggleIcon.classList.remove('fa-toggle-on');
                    toggleIcon.classList.add('fa-toggle-off');
                }
            }

            localStorage.setItem('accessibilityHighContrast', isActive);
            console.log('>>> High contrast mode:', isActive ? 'ENABLED' : 'DISABLED');
            announceToScreenReader('High contrast mode ' + (isActive ? 'enabled' : 'disabled'));
        };

        highContrastBtn.addEventListener('click', highContrastHandler, true);
        highContrastBtn.addEventListener('touchstart', highContrastHandler, true);
    } else {
        console.error('✗ High contrast button NOT found');
    }

    // Dyslexic font toggle
    const dyslexicFontBtn = document.getElementById('dyslexic-font');
    if (dyslexicFontBtn) {
        console.log('✓ Dyslexic font button found');

        const dyslexicFontHandler = function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('>>> DYSLEXIC FONT CLICKED!');

            const isActive = document.body.classList.toggle('dyslexic-font');
            dyslexicFontBtn.classList.toggle('active', isActive);

            const toggleIcon = dyslexicFontBtn.querySelector('.toggle-icon');
            if (toggleIcon) {
                if (isActive) {
                    toggleIcon.classList.remove('fa-toggle-off');
                    toggleIcon.classList.add('fa-toggle-on');
                } else {
                    toggleIcon.classList.remove('fa-toggle-on');
                    toggleIcon.classList.add('fa-toggle-off');
                }
            }

            localStorage.setItem('accessibilityDyslexicFont', isActive);
            console.log('>>> Dyslexic font:', isActive ? 'ENABLED' : 'DISABLED');
            announceToScreenReader('Dyslexic friendly font ' + (isActive ? 'enabled' : 'disabled'));
        };

        dyslexicFontBtn.addEventListener('click', dyslexicFontHandler, true);
        dyslexicFontBtn.addEventListener('touchstart', dyslexicFontHandler, true);
    } else {
        console.error('✗ Dyslexic font button NOT found');
    }

    // Reset all accessibility settings
    const resetBtn = document.getElementById('reset-accessibility');
    if (resetBtn) {
        console.log('✓ Reset button found');

        const resetHandler = function(e) {
            e.preventDefault();
            e.stopPropagation();

            console.log('>>> RESET ALL CLICKED!');

            // Reset font size
            currentFontSize = 16;
            document.body.style.fontSize = '16px';

            // Remove all classes
            document.body.classList.remove('high-contrast', 'dyslexic-font', 'font-large', 'font-xlarge');

            // Reset all toggle icons and active states
            document.querySelectorAll('.accessibility-option').forEach(option => {
                option.classList.remove('active');
                const toggleIcon = option.querySelector('.toggle-icon');
                if (toggleIcon) {
                    toggleIcon.classList.remove('fa-toggle-on');
                    toggleIcon.classList.add('fa-toggle-off');
                }
            });

            // Clear localStorage
            localStorage.removeItem('accessibilityFontSize');
            localStorage.removeItem('accessibilityHighContrast');
            localStorage.removeItem('accessibilityDyslexicFont');

            console.log('>>> All settings RESET to default');
            announceToScreenReader('All accessibility settings have been reset to default');

            // Strong visual feedback
            resetBtn.style.background = '#28a745';
            resetBtn.style.color = 'white';
            setTimeout(() => {
                resetBtn.style.background = '';
                resetBtn.style.color = '';
            }, 500);
        };

        resetBtn.addEventListener('click', resetHandler, true);
        resetBtn.addEventListener('touchstart', resetHandler, true);
    } else {
        console.error('✗ Reset button NOT found');
    }

    // Keyboard navigation for accessibility menu
    const allOptions = document.querySelectorAll('.accessibility-option');
    allOptions.forEach(option => {
        option.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                console.log('>>> Keyboard activation for:', this.id);
                this.click();
            }
        });
    });

    // Final summary
    console.log('===========================================');
    console.log('ACCESSIBILITY WIDGET SUMMARY:');
    console.log('Total options found:', allOptions.length);
    console.log('Button IDs:', Array.from(allOptions).map(o => o.id));
    console.log('===========================================');
    console.log('✓ Accessibility widget READY!');
    console.log('=== INITIALIZATION COMPLETE ===');

    // Add global test function for debugging
    window.testAccessibilityWidget = function() {
        console.log('=== TESTING ACCESSIBILITY WIDGET ===');
        console.log('Toggle button:', accessibilityToggle);
        console.log('Menu:', accessibilityMenu);
        console.log('Increase font:', increaseFontBtn);
        console.log('Decrease font:', decreaseFontBtn);
        console.log('High contrast:', highContrastBtn);
        console.log('Dyslexic font:', dyslexicFontBtn);
        console.log('Reset:', resetBtn);
        console.log('=== END TEST ===');
        console.log('Try clicking: document.getElementById("increase-font").click()');
    };

    console.log('💡 TIP: Run testAccessibilityWidget() in console to debug');

    // Function to load saved preferences
    function loadAccessibilityPreferences() {
        // Load font size
        const savedFontSize = localStorage.getItem('accessibilityFontSize');
        if (savedFontSize) {
            currentFontSize = parseInt(savedFontSize);
            document.body.style.fontSize = currentFontSize + 'px';
        }

        // Load high contrast
        const highContrast = localStorage.getItem('accessibilityHighContrast') === 'true';
        if (highContrast) {
            document.body.classList.add('high-contrast');
            const option = document.getElementById('high-contrast');
            if (option) {
                option.classList.add('active');
                const toggleIcon = option.querySelector('.toggle-icon');
                if (toggleIcon) {
                    toggleIcon.classList.remove('fa-toggle-off');
                    toggleIcon.classList.add('fa-toggle-on');
                }
            }
        }

        // Load dyslexic font
        const dyslexicFont = localStorage.getItem('accessibilityDyslexicFont') === 'true';
        if (dyslexicFont) {
            document.body.classList.add('dyslexic-font');
            const option = document.getElementById('dyslexic-font');
            if (option) {
                option.classList.add('active');
                const toggleIcon = option.querySelector('.toggle-icon');
                if (toggleIcon) {
                    toggleIcon.classList.remove('fa-toggle-off');
                    toggleIcon.classList.add('fa-toggle-on');
                }
            }
        }
    }

    // Screen reader announcements
    function announceToScreenReader(message) {
        const announcement = document.createElement('div');
        announcement.setAttribute('role', 'status');
        announcement.setAttribute('aria-live', 'polite');
        announcement.classList.add('sr-only');
        announcement.textContent = message;
        document.body.appendChild(announcement);

        // Remove after announcement
        setTimeout(() => {
            if (announcement.parentNode) {
                document.body.removeChild(announcement);
            }
        }, 1000);
    }

    // Auto-refresh security alerts every 60 seconds
    setInterval(() => {
        console.log('Security alerts refresh check - implement AJAX if needed');
    }, 60000);
});
</script>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
// Chart.js Configuration and Data Visualization
document.addEventListener('DOMContentLoaded', function() {
    // Chart.js global configuration
    Chart.defaults.font.family = "'Segoe UI', 'Helvetica Neue', 'Arial', sans-serif";
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.padding = 15;

    // Color palette
    const colors = {
        primary: 'rgba(78, 115, 223, 0.8)',
        success: 'rgba(28, 200, 138, 0.8)',
        info: 'rgba(54, 185, 204, 0.8)',
        warning: 'rgba(246, 194, 62, 0.8)',
        danger: 'rgba(231, 74, 59, 0.8)',
        secondary: 'rgba(133, 135, 150, 0.8)',
    };

    // 1. System Overview Bar Chart
    const systemOverviewCtx = document.getElementById('systemOverviewChart');
    if (systemOverviewCtx) {
        new Chart(systemOverviewCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jobs', 'Active', 'Training', 'Users', 'PWD', 'Apps'],
                datasets: [{
                    label: 'Count',
                    data: [
                        {{ $stats['job_postings'] }},
                        {{ $stats['active_jobs'] }},
                        {{ $stats['skill_trainings'] }},
                        {{ $stats['total_users'] }},
                        {{ $stats['pwd_users'] }},
                        {{ $stats['pending_applications'] + $stats['approved_applications'] }}
                    ],
                    backgroundColor: [
                        colors.primary,
                        colors.info,
                        colors.success,
                        colors.warning,
                        colors.secondary,
                        colors.danger
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(246, 194, 62, 1)',
                        'rgba(133, 135, 150, 1)',
                        'rgba(231, 74, 59, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 10 }
                        }
                    },
                    x: {
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. User Distribution Pie Chart
    const userDistributionCtx = document.getElementById('userDistributionChart');
    if (userDistributionCtx) {
        new Chart(userDistributionCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['PWD', 'Admin', 'Other'],
                datasets: [{
                    data: [
                        {{ $stats['pwd_users'] }},
                        {{ $stats['admin_users'] }},
                        {{ $stats['total_users'] - $stats['pwd_users'] - $stats['admin_users'] }}
                    ],
                    backgroundColor: [
                        colors.success,
                        colors.danger,
                        colors.info
                    ],
                    borderColor: [
                        'rgba(28, 200, 138, 1)',
                        'rgba(231, 74, 59, 1)',
                        'rgba(54, 185, 204, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 5,
                            font: {
                                size: 9
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    }

    // 3. Application Status Chart
    const applicationStatusCtx = document.getElementById('applicationStatusChart');
    if (applicationStatusCtx) {
        new Chart(applicationStatusCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Pending', 'Approved'],
                datasets: [{
                    label: 'Applications',
                    data: [
                        {{ $stats['pending_applications'] }},
                        {{ $stats['approved_applications'] }}
                    ],
                    backgroundColor: [
                        colors.warning,
                        colors.success
                    ],
                    borderColor: [
                        'rgba(246, 194, 62, 1)',
                        'rgba(28, 200, 138, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: { size: 10 }
                        }
                    },
                    y: {
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.parsed.x;
                            }
                        }
                    }
                }
            }
        });
    }

    // 4. Training Enrollments Chart
    const trainingEnrollmentsCtx = document.getElementById('trainingEnrollmentsChart');
    if (trainingEnrollmentsCtx) {
        new Chart(trainingEnrollmentsCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Available'],
                datasets: [{
                    data: [
                        {{ $stats['active_trainings'] }},
                        {{ $stats['skill_trainings'] * 30 - $stats['active_trainings'] }}
                    ],
                    backgroundColor: [
                        colors.info,
                        'rgba(220, 220, 220, 0.5)'
                    ],
                    borderColor: [
                        'rgba(54, 185, 204, 1)',
                        'rgba(200, 200, 200, 1)'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 5,
                            font: {
                                size: 9
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.parsed || 0;
                                return label + ': ' + value;
                            }
                        }
                    }
                }
            }
        });
    }

    console.log('Dashboard charts initialized successfully');
});
</script>

<style>
    /* Custom Dashboard Styles */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-header h4, .card-header h5 {
        font-weight: 600;
    }

    /* Progress bar animations */
    .progress-bar {
        transition: width 1s ease-in-out;
    }

    /* Badge improvements */
    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
    }

    /* Card shadows */
    .shadow-sm {
        box-shadow: 0 0.125rem 0.375rem rgba(0, 0, 0, 0.075) !important;
    }

    /* Text weight */
    .font-weight-bold {
        font-weight: 700 !important;
    }

    /* Smooth transitions for interactive elements */
    .btn, .card, a {
        transition: all 0.3s ease;
    }

    /* Welcome banner gradient */
    .card[style*="linear-gradient"] {
        position: relative;
        overflow: hidden;
    }

    .card[style*="linear-gradient"]::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        background: rgba(255, 255, 255, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .card[style*="linear-gradient"]:hover::before {
        opacity: 1;
    }
</style>
@endsection
