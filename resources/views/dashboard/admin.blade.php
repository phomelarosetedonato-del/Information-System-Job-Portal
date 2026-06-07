@extends('layouts.admin')

@section('title', 'Admin Dashboard - PWD System')

@section('content')
<!-- Skip Navigation for Accessibility -->
<a href="#main-content" class="sr-only sr-only-focusable bg-primary text-white p-2 position-absolute top-0 start-0" style="z-index: 9999; transform: translateY(-100%); transition: transform 0.3s;">
    Skip to main content
</a>

<div class="dashboard-container" id="main-content" tabindex="-1" role="main" aria-labelledby="dashboard-heading">
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
                                        <i class="fas fa-calendar-day me-2"></i><span id="dashboard-date">{{ now()->format('l, F j, Y') }}</span> |
                                        <i class="fas fa-clock ms-2 me-2"></i><span id="dashboard-time">{{ now()->format('g:i A') }}</span>
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

                <!-- Qualified Applicants Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="icon-circle bg-success bg-opacity-10">
                                    <i class="fas fa-check-double text-success fa-2x"></i>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success">Verified</span>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $stats['qualified_applicants'] ?? 0 }}</h4>
                            <p class="text-muted mb-2 small">Qualified Applicants</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-info">
                                    <i class="fas fa-briefcase me-1"></i>{{ $stats['available_qualified'] ?? 0 }} Available
                                </small>
                                <a href="{{ route('admin.qualifications.index') }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Community PWD Statistics Card -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100 hover-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="icon-circle bg-info bg-opacity-10">
                                    <i class="fas fa-chart-pie text-info fa-2x"></i>
                                </div>
                                <span class="badge bg-info bg-opacity-10 text-info">{{ $currentYear }}</span>
                            </div>
                            <h4 class="h2 mb-1 text-dark">{{ $communityTotals['total_pwd'] ?? 0 }}</h4>
                            <p class="text-muted mb-2 small">Community PWD</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-danger">
                                    <i class="fas fa-users me-1"></i>{{ $communityTotals['total_unemployed'] ?? 0 }} Unemployed
                                </small>
                                <a href="{{ route('admin.community-statistics.index') }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
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

                <div class="col-xl-3 col-md-6 mb-3">
                    <a href="{{ route('admin.qualifications.index') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center py-4">
                                <div class="icon-circle bg-success bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </div>
                                <h5 class="text-dark mb-2">Qualified Report</h5>
                                <p class="text-muted small mb-0">Manage qualified applicants</p>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-xl-3 col-md-6 mb-3">
                    <a href="{{ route('admin.community-statistics.index') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm h-100 hover-lift">
                            <div class="card-body text-center py-4">
                                <div class="icon-circle bg-info bg-opacity-10 mx-auto mb-3" style="width: 60px; height: 60px;">
                                    <i class="fas fa-chart-bar fa-2x text-info"></i>
                                </div>
                                <h5 class="text-dark mb-2">Community Stats</h5>
                                <p class="text-muted small mb-0">Manage PWD statistics</p>
                            </div>
                        </div>
                    </a>
                </div>
                        </div>
                    </a>
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
            <div class="row mb-2" style="gap: 0.5rem; display: flex; flex-wrap: wrap; overflow-x: auto; -webkit-overflow-scrolling: touch;">
                <style>
                    @media (min-width: 1920px) {
                        .recent-activity-card { flex: 0 0 calc(25% - 0.375rem); }
                    }
                    @media (min-width: 1366px) and (max-width: 1919px) {
                        .recent-activity-card { flex: 0 0 calc(25% - 0.375rem); }
                    }
                    @media (min-width: 1024px) and (max-width: 1365px) {
                        .recent-activity-card { flex: 0 0 calc(50% - 0.25rem); }
                    }
                    @media (min-width: 768px) and (max-width: 1023px) {
                        .recent-activity-card { flex: 0 0 calc(50% - 0.25rem); }
                    }
                    @media (max-width: 767px) {
                        .recent-activity-card { flex: 0 0 calc(100% - 0rem); }
                    }
                </style>
                <!-- Recent Applications -->
                <div class="recent-activity-card">
                    <div class="card shadow-sm border-0" style="max-height: 200px; height: 100%;">
                        <div class="card-header bg-white border-bottom py-0 px-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="text-sm mb-0 text-dark" style="font-size: 0.8rem;">Recent Applications</h2>
                                <a href="{{ route('admin.applications.index') }}" class="btn btn-xs btn-outline-primary" style="font-size: 0.65rem; padding: 0.25rem 0.5rem;">All</a>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 160px; overflow-y: auto;">
                            @if($recentApplications->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentApplications as $application)
                                        <div class="list-group-item border-0 p-1" style="font-size: 0.75rem;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h3 class="mb-0 text-dark" style="font-size: 0.75rem;">{{ $application->user->name ?? 'Unknown User' }}</h3>
                                                    <p class="mb-0" style="font-size: 0.65rem; color: #6c757d;">Applied for: {{ $application->jobPosting->title ?? 'Unknown Job' }}</p>
                                                    <span class="badge bg-{{ $application->status == 'pending' ? 'warning' : ($application->status == 'approved' ? 'success' : 'danger') }}" style="font-size: 0.6rem;">{{ ucfirst($application->status) }}</span>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.6rem;">{{ $application->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-1 mx-3">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <i class="fas fa-clipboard-list text-muted" style="font-size: 1rem;"></i>
                                    <p class="text-muted mb-0" style="font-size: 0.65rem;">No recent applications.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Enrollments -->
                <div class="recent-activity-card">
                    <div class="card shadow-sm border-0" style="max-height: 200px; height: 100%;">
                        <div class="card-header bg-white border-bottom py-0 px-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="text-sm mb-0 text-dark" style="font-size: 0.8rem;">Recent Enrollments</h2>
                                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-xs btn-outline-success" style="font-size: 0.65rem; padding: 0.25rem 0.5rem;">All</a>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 160px; overflow-y: auto;">
                            @if($recentEnrollments->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentEnrollments as $enrollment)
                                        <div class="list-group-item border-0 p-1" style="font-size: 0.75rem;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h3 class="mb-0 text-dark" style="font-size: 0.75rem;">{{ $enrollment->user->name ?? 'Unknown User' }}</h3>
                                                    <p class="mb-0" style="font-size: 0.65rem; color: #6c757d;">Enrolled in: {{ $enrollment->skillTraining->title ?? 'Unknown Training' }}</p>
                                                    <span class="badge bg-{{ $enrollment->status == 'enrolled' ? 'success' : ($enrollment->status == 'pending' ? 'warning' : 'secondary') }}" style="font-size: 0.6rem;">{{ ucfirst($enrollment->status) }}</span>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.6rem;">{{ $enrollment->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-0 mx-1" style="margin: 0.25rem 0.5rem;">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <i class="fas fa-graduation-cap text-muted" style="font-size: 1rem;\"></i>
                                    <p class="text-muted mb-0" style="font-size: 0.65rem;">No recent enrollments.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Recent Users -->
                <div class="recent-activity-card">
                    <div class="card shadow-sm border-0" style="max-height: 200px; height: 100%;">
                        <div class="card-header bg-white border-bottom py-0 px-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="text-sm mb-0 text-dark" style="font-size: 0.8rem;">Recent Users</h2>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-xs btn-outline-info" style="font-size: 0.65rem; padding: 0.25rem 0.5rem;">All</a>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 160px; overflow-y: auto;">
                            @if($recentUsers->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($recentUsers as $user)
                                        <div class="list-group-item border-0 p-1" style="font-size: 0.75rem;">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h3 class="mb-0 text-dark" style="font-size: 0.75rem;">{{ $user->name }}</h3>
                                                    <p class="mb-0" style="font-size: 0.65rem; color: #6c757d;">{{ $user->email }}</p>
                                                    <span class="badge {{ $user->role === 'admin' ? 'bg-warning' : ($user->role === 'pwd' ? 'bg-success' : 'bg-secondary') }}" style="font-size: 0.6rem;">{{ ucfirst($user->role) }}</span>
                                                </div>
                                                <small class="text-muted" style="font-size: 0.6rem;">{{ $user->created_at->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-0 mx-1" style="margin: 0.25rem 0.5rem;">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <i class="fas fa-users text-muted" style="font-size: 1rem;"></i>
                                    <p class="text-muted mb-0" style="font-size: 0.65rem;">No recent users.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Security Alerts -->
                <div class="recent-activity-card">
                    <div class="card shadow-sm border-warning" style="max-height: 200px; height: 100%;">
                        <div class="card-header bg-warning text-dark border-bottom py-0 px-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="mb-0" style="font-size: 0.8rem;">Security Alerts</h2>
                                <a href="{{ route('admin.security.report') }}" class="btn btn-xs btn-warning" style="font-size: 0.65rem; padding: 0.25rem 0.5rem;">Report</a>
                            </div>
                        </div>
                        <div class="card-body p-0" style="max-height: 160px; overflow-y: auto;">
                            @if($securityAlerts->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($securityAlerts as $user)
                                        <div class="list-group-item border-0 p-1" style="font-size: 0.75rem;">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h3 class="mb-0 text-danger" style="font-size: 0.75rem;">{{ $user->name }}</h3>
                                                    <p class="mb-0" style="font-size: 0.65rem;">{{ $user->email }}</p>
                                                    @if($user->failed_login_attempts >= 3)
                                                        <small class="text-muted d-block" style="font-size: 0.6rem;">Failed: {{ $user->failed_login_attempts }}</small>
                                                    @endif
                                                    @if(!$user->password_meets_current_standards)
                                                        <small class="text-muted d-block" style="font-size: 0.6rem;">Weak pwd</small>
                                                    @endif
                                                    @if($user->account_locked_until)
                                                        <small class="text-muted d-block" style="font-size: 0.6rem;">Locked</small>
                                                    @endif
                                                </div>
                                                <span class="badge bg-danger" style="font-size: 0.6rem; white-space: nowrap;">Alert</span>
                                            </div>
                                        </div>
                                        @if(!$loop->last)
                                            <hr class="my-0 mx-1" style="margin: 0.25rem 0.5rem;">
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <i class="fas fa-check-circle text-success" style="font-size: 1rem;"></i>
                                    <p class="text-muted mb-0" style="font-size: 0.65rem;">No alerts</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
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

    <!-- Accessibility Widget is now provided by layout (included from partials.accessibility-widget) -->

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

    /* Screen Reader Only - Keep this for skip links functionality */
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
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real-time clock update
    function updateClock() {
        const now = new Date();

        // Format date: Monday, December 10, 2024
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const dateString = now.toLocaleDateString('en-US', dateOptions);

        // Format time: 2:30 PM
        const timeOptions = { hour: 'numeric', minute: '2-digit', hour12: true };
        const timeString = now.toLocaleTimeString('en-US', timeOptions);

        // Update the display
        const dateElement = document.getElementById('dashboard-date');
        const timeElement = document.getElementById('dashboard-time');

        if (dateElement) dateElement.textContent = dateString;
        if (timeElement) timeElement.textContent = timeString;
    }

    // Update clock immediately on load
    updateClock();

    // Update clock every second
    setInterval(updateClock, 1000);

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

    // Note: Accessibility widget is now handled by partials.accessibility-widget included in the layout
    // All accessibility functionality (toggle, font size, contrast, etc.) is managed there
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
