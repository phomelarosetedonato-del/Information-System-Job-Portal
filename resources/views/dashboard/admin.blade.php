@extends('layouts.admin')

@section('title', 'Admin Dashboard - PWD System')

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
                        Admin Dashboard - PWD System Alaminos City
                    </h1>
                    <p class="mb-0 text-muted">System overview and management tools</p>
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

            <!-- Main Statistics Cards -->
            <div class="row mb-4" role="region" aria-labelledby="stats-heading">
                <h2 id="stats-heading" class="sr-only">System Statistics</h2>

                <!-- Job Postings Card -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-primary h-100 shadow-sm" role="group" aria-labelledby="jobs-card-heading">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="h6 card-title text-muted" id="jobs-card-heading">Job Postings</h3>
                                    <p class="card-text display-6 text-primary mb-1" aria-live="polite">{{ $stats['job_postings'] }}</p>
                                    <small class="text-muted">Active: {{ $stats['active_jobs'] }}</small>
                                </div>
                                <i class="fas fa-briefcase fa-2x text-primary opacity-75" aria-hidden="true"></i>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.job-postings.index') }}" class="btn btn-outline-primary btn-sm w-100" aria-describedby="jobs-help">
                                    Manage Job Postings
                                </a>
                                <div id="jobs-help" class="sr-only">Navigate to job postings management page</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Skill Trainings Card -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-success h-100 shadow-sm" role="group" aria-labelledby="trainings-card-heading">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="h6 card-title text-muted" id="trainings-card-heading">Skill Trainings</h3>
                                    <p class="card-text display-6 text-success mb-1" aria-live="polite">{{ $stats['skill_trainings'] }}</p>
                                    <small class="text-muted">Active: {{ $stats['active_trainings_count'] }}</small>
                                </div>
                                <i class="fas fa-graduation-cap fa-2x text-success opacity-75" aria-hidden="true"></i>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.skill-trainings.index') }}" class="btn btn-outline-success btn-sm w-100" aria-describedby="trainings-help">
                                    Manage Trainings
                                </a>
                                <div id="trainings-help" class="sr-only">Navigate to skill trainings management page</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Card -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-info h-100 shadow-sm" role="group" aria-labelledby="users-card-heading">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="h6 card-title text-muted" id="users-card-heading">Total Users</h3>
                                    <p class="card-text display-6 text-info mb-1" aria-live="polite">{{ $stats['total_users'] }}</p>
                                    <small class="text-muted">PWD: {{ $stats['pwd_users'] }}</small>
                                </div>
                                <i class="fas fa-users fa-2x text-info opacity-75" aria-hidden="true"></i>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info btn-sm w-100" aria-describedby="users-help">
                                    Manage Users
                                </a>
                                <div id="users-help" class="sr-only">Navigate to users management page</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Card -->
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="card border-warning h-100 shadow-sm" role="group" aria-labelledby="security-card-heading">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h3 class="h6 card-title text-muted" id="security-card-heading">Security Alerts</h3>
                                    <p class="card-text display-6 text-warning mb-1" aria-live="polite">{{ $stats['users_needing_security'] }}</p>
                                    <small class="text-muted">Locked: {{ $stats['locked_users'] }}</small>
                                </div>
                                <i class="fas fa-shield-alt fa-2x text-warning opacity-75" aria-hidden="true"></i>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('admin.security.report') }}" class="btn btn-outline-warning btn-sm w-100" aria-describedby="security-help">
                                    View Report
                                </a>
                                <div id="security-help" class="sr-only">Navigate to security report page</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="row mb-4" role="navigation" aria-labelledby="quick-actions-heading">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h2 class="h5 mb-0 text-dark" id="quick-actions-heading">
                                <i class="fas fa-bolt me-2 text-primary"></i>Quick Actions
                            </h2>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-xl-3 col-md-6">
                                    <a href="{{ route('admin.job-postings.create') }}" class="btn btn-primary w-100 h-100 py-3 text-decoration-none" aria-describedby="create-job-help">
                                        <i class="fas fa-plus me-2" aria-hidden="true"></i>
                                        <div>Create Job Posting</div>
                                    </a>
                                    <div id="create-job-help" class="sr-only">Open job posting creation form</div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <a href="{{ route('admin.skill-trainings.create') }}" class="btn btn-success w-100 h-100 py-3 text-decoration-none" aria-describedby="create-training-help">
                                        <i class="fas fa-plus me-2" aria-hidden="true"></i>
                                        <div>Create Training</div>
                                    </a>
                                    <div id="create-training-help" class="sr-only">Open training program creation form</div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <a href="{{ route('admin.announcements.create') }}" class="btn btn-info w-100 h-100 py-3 text-decoration-none" aria-describedby="create-announcement-help">
                                        <i class="fas fa-plus me-2" aria-hidden="true"></i>
                                        <div>Create Announcement</div>
                                    </a>
                                    <div id="create-announcement-help" class="sr-only">Open announcement creation form</div>
                                </div>
                                <div class="col-xl-3 col-md-6">
                                    <a href="{{ route('admin.users.create') }}" class="btn btn-warning w-100 h-100 py-3 text-decoration-none" aria-describedby="create-user-help">
                                        <i class="fas fa-user-plus me-2" aria-hidden="true"></i>
                                        <div>Create User</div>
                                    </a>
                                    <div id="create-user-help" class="sr-only">Open user creation form</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- System Statistics -->
            <div class="row mb-4" role="region" aria-labelledby="system-overview-heading">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <h2 class="h5 mb-0 text-dark" id="system-overview-heading">
                                <i class="fas fa-chart-bar me-2 text-primary"></i>System Overview
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-2 text-center mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-users fa-2x text-primary mb-2" aria-hidden="true"></i>
                                        <h3 class="h6 text-muted">Total Users</h3>
                                        <p class="fs-4 fw-bold text-dark" aria-live="polite">{{ $stats['total_users'] }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-user-check fa-2x text-success mb-2" aria-hidden="true"></i>
                                        <h3 class="h6 text-muted">PWD Users</h3>
                                        <p class="fs-4 fw-bold text-dark" aria-live="polite">{{ $stats['pwd_users'] }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-crown fa-2x text-warning mb-2" aria-hidden="true"></i>
                                        <h3 class="h6 text-muted">Admin Users</h3>
                                        <p class="fs-4 fw-bold text-dark" aria-live="polite">{{ $stats['admin_users'] }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-file-upload fa-2x text-info mb-2" aria-hidden="true"></i>
                                        <h3 class="h6 text-muted">Total Documents</h3>
                                        <p class="fs-4 fw-bold text-dark" aria-live="polite">{{ $stats['total_documents'] }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-tasks fa-2x text-warning mb-2" aria-hidden="true"></i>
                                        <h3 class="h6 text-muted">Pending Applications</h3>
                                        <p class="fs-4 fw-bold text-dark" aria-live="polite">{{ $stats['pending_applications'] }}</p>
                                    </div>
                                </div>
                                <div class="col-md-2 text-center mb-3">
                                    <div class="p-3">
                                        <i class="fas fa-lock fa-2x text-danger mb-2" aria-hidden="true"></i>
                                        <h3 class="h6 text-muted">Locked Accounts</h3>
                                        <p class="fs-4 fw-bold text-dark" aria-live="polite">{{ $stats['locked_users'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity & Security Alerts -->
            <div class="row mb-4">
                <!-- Recent Applications -->
                <div class="col-xl-3 col-lg-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h5 mb-0 text-dark">Recent Job Applications</h2>
                                <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
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
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h5 mb-0 text-dark">Recent Training Enrollments</h2>
                                <a href="{{ route('admin.enrollments.index') }}" class="btn btn-sm btn-outline-success">View All</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
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
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-header bg-white border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h5 mb-0 text-dark">Recent Users</h2>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-info">View All</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
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
                    <div class="card h-100 shadow-sm border-warning">
                        <div class="card-header bg-warning text-dark border-bottom py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="h5 mb-0">Security Alerts</h2>
                                <a href="{{ route('admin.security.report') }}" class="btn btn-sm btn-warning">View Report</a>
                            </div>
                        </div>
                        <div class="card-body p-0">
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

    // Auto-refresh security alerts every 60 seconds
    setInterval(() => {
        console.log('Security alerts refresh check - implement AJAX if needed');
    }, 60000);
});
</script>
@endsection
