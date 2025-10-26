@extends('layouts.app')

@section('title', 'Admin Dashboard - PWD System')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="h4 mb-0">Admin Dashboard - PWD System Alaminos City</h1>
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

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Main Statistics Cards -->
                    <div class="row">
                        <!-- Job Postings Card -->
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-primary h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h2 class="h5 card-title">Job Postings</h2>
                                            <p class="card-text display-6">{{ $stats['job_postings'] ?? 0 }}</p>
                                            <small>Active: {{ $stats['active_jobs'] ?? 0 }}</small>
                                        </div>
                                        <i class="fas fa-briefcase fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('job-postings.index') }}" class="btn btn-light btn-sm w-100">
                                            Manage Job Postings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Skill Trainings Card -->
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-success h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h2 class="h5 card-title">Skill Trainings</h2>
                                            <p class="card-text display-6">{{ $stats['skill_trainings'] ?? 0 }}</p>
                                            <small>Active: {{ $stats['active_trainings'] ?? 0 }}</small>
                                        </div>
                                        <i class="fas fa-graduation-cap fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('skill-trainings.index') }}" class="btn btn-light btn-sm w-100">
                                            Manage Trainings
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Users Card -->
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-info h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h2 class="h5 card-title">Total Users</h2>
                                            <p class="card-text display-6">{{ $stats['total_users'] ?? 0 }}</p>
                                            <small>PWD: {{ $stats['pwd_users'] ?? 0 }}</small>
                                        </div>
                                        <i class="fas fa-users fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm w-100">
                                            Manage Users
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Card -->
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-warning h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h2 class="h5 card-title">Security Alerts</h2>
                                            <p class="card-text display-6">{{ $stats['users_needing_security'] ?? 0 }}</p>
                                            <small>Locked: {{ $stats['locked_users'] ?? 0 }}</small>
                                        </div>
                                        <i class="fas fa-shield-alt fa-2x" aria-hidden="true"></i>
                                    </div>
                                    <div class="mt-3">
                                        <a href="{{ route('admin.security.report') }}" class="btn btn-light btn-sm w-100">
                                            View Report
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white">
                                    <h2 class="h5 mb-0">Quick Actions</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('job-postings.create') }}" class="btn btn-primary w-100">
                                                <i class="fas fa-plus me-1" aria-hidden="true"></i> Create Job Posting
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('skill-trainings.create') }}" class="btn btn-success w-100">
                                                <i class="fas fa-plus me-1" aria-hidden="true"></i> Create Training
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('announcements.create') }}" class="btn btn-info w-100">
                                                <i class="fas fa-plus me-1" aria-hidden="true"></i> Create Announcement
                                            </a>
                                        </div>
                                        <div class="col-md-3 mb-2">
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-warning w-100">
                                                <i class="fas fa-users me-1" aria-hidden="true"></i> Manage Users
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Statistics -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-dark text-white">
                                    <h2 class="h5 mb-0">System Overview</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2 text-center">
                                            <div class="p-3">
                                                <i class="fas fa-users fa-2x text-primary mb-2" aria-hidden="true"></i>
                                                <h3 class="h6">Total Users</h3>
                                                <p class="fs-4 fw-bold">{{ $stats['total_users'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <div class="p-3">
                                                <i class="fas fa-user-check fa-2x text-success mb-2" aria-hidden="true"></i>
                                                <h3 class="h6">PWD Users</h3>
                                                <p class="fs-4 fw-bold">{{ $stats['pwd_users'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <div class="p-3">
                                                <i class="fas fa-crown fa-2x text-danger mb-2" aria-hidden="true"></i>
                                                <h3 class="h6">Admin Users</h3>
                                                <p class="fs-4 fw-bold">{{ $stats['admin_users'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <div class="p-3">
                                                <i class="fas fa-file-upload fa-2x text-info mb-2" aria-hidden="true"></i>
                                                <h3 class="h6">Total Documents</h3>
                                                <p class="fs-4 fw-bold">{{ $stats['total_documents'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <div class="p-3">
                                                <i class="fas fa-tasks fa-2x text-warning mb-2" aria-hidden="true"></i>
                                                <h3 class="h6">Pending Applications</h3>
                                                <p class="fs-4 fw-bold">{{ $stats['pending_applications'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-center">
                                            <div class="p-3">
                                                <i class="fas fa-lock fa-2x text-danger mb-2" aria-hidden="true"></i>
                                                <h3 class="h6">Locked Accounts</h3>
                                                <p class="fs-4 fw-bold">{{ $stats['locked_users'] ?? 0 }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity & Security Alerts -->
                    <div class="row mt-4">
                        <!-- Recent Applications -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h2 class="h5 mb-0">Recent Job Applications</h2>
                                        <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-primary">View All</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($recentApplications->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($recentApplications as $application)
                                                <div class="list-group-item px-0">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h3 class="h6 mb-1">{{ $application->user->name }}</h3>
                                                            <p class="mb-1 small text-muted">Applied for: {{ $application->jobPosting->title }}</p>
                                                            <span class="badge bg-{{ $application->status == 'pending' ? 'warning' : ($application->status == 'approved' ? 'success' : 'danger') }}">
                                                                {{ ucfirst($application->status) }}
                                                            </span>
                                                        </div>
                                                        <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No recent job applications.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Recent Users -->
                        <div class="col-md-4">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h2 class="h5 mb-0">Recent Users</h2>
                                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">View All</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($recentUsers->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($recentUsers as $user)
                                                <div class="list-group-item px-0">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div>
                                                            <h3 class="h6 mb-1">{{ $user->name }}</h3>
                                                            <p class="mb-1 small text-muted">{{ $user->email }}</p>
                                                            <span class="badge {{ $user->role_badge_class }}">
                                                                {{ $user->getRoleDisplayName() }}
                                                            </span>
                                                        </div>
                                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p class="text-muted mb-0">No recent users.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Security Alerts -->
                        <div class="col-md-4">
                            <div class="card h-100 border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h2 class="h5 mb-0">Security Alerts</h2>
                                        <a href="{{ route('admin.security.report') }}" class="btn btn-sm btn-warning">View Report</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($securityAlerts->count() > 0)
                                        <div class="list-group list-group-flush">
                                            @foreach($securityAlerts as $user)
                                                <div class="list-group-item px-0">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <h3 class="h6 mb-1 text-danger">{{ $user->name }}</h3>
                                                            <p class="mb-1 small">Security Score: {{ $user->security_score }}/100</p>
                                                            @if($user->getSecurityRecommendations())
                                                                <small class="text-muted">
                                                                    {{ $user->getSecurityRecommendations()[0] }}
                                                                </small>
                                                            @endif
                                                        </div>
                                                        <span class="badge bg-danger">High Risk</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                            <p class="mb-0">No security alerts</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Tools -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-primary text-white">
                                    <h2 class="h5 mb-0">Admin Management Tools</h2>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- User Management -->
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100 border-primary">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-users fa-2x text-primary mb-3" aria-hidden="true"></i>
                                                    <h3 class="h6">User Management</h3>
                                                    <p class="small text-muted">Manage all system users and roles</p>
                                                    <div class="d-grid gap-2">
                                                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-cog me-1"></i> Manage Users
                                                        </a>
                                                    </div>
                                                    <div class="mt-2 small text-muted">
                                                        <span class="badge bg-primary">{{ $stats['total_users'] ?? 0 }} Total</span>
                                                        <span class="badge bg-success">{{ $stats['pwd_users'] ?? 0 }} PWD</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Security Center -->
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100 border-warning">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-shield-alt fa-2x text-warning mb-3" aria-hidden="true"></i>
                                                    <h3 class="h6">Security Center</h3>
                                                    <p class="small text-muted">Monitor security and user activity</p>
                                                    <div class="d-grid gap-2">
                                                        <a href="{{ route('admin.security.report') }}" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-chart-bar me-1"></i> Security Report
                                                        </a>
                                                    </div>
                                                    <div class="mt-2 small text-muted">
                                                        <span class="badge bg-warning">{{ $stats['users_needing_security'] ?? 0 }} Alerts</span>
                                                        <span class="badge bg-danger">{{ $stats['locked_users'] ?? 0 }} Locked</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Analytics & Reports -->
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100 border-success">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-chart-bar fa-2x text-success mb-3" aria-hidden="true"></i>
                                                    <h3 class="h6">Analytics & Reports</h3>
                                                    <p class="small text-muted">View system analytics and reports</p>
                                                    <div class="d-grid gap-2">
                                                        <a href="{{ route('admin.statistics') }}" class="btn btn-success btn-sm">
                                                            <i class="fas fa-chart-line me-1"></i> View Analytics
                                                        </a>
                                                    </div>
                                                    <div class="mt-2 small text-muted">
                                                        <span class="badge bg-info">{{ $stats['pending_applications'] ?? 0 }} Pending</span>
                                                        <span class="badge bg-success">{{ $stats['active_trainings'] ?? 0 }} Active</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Application Management -->
                                        <div class="col-md-3 mb-3">
                                            <div class="card h-100 border-info">
                                                <div class="card-body text-center">
                                                    <i class="fas fa-clipboard-list fa-2x text-info mb-3" aria-hidden="true"></i>
                                                    <h3 class="h6">Applications</h3>
                                                    <p class="small text-muted">Manage job applications</p>
                                                    <div class="d-grid gap-2">
                                                        <a href="{{ route('admin.applications.index') }}" class="btn btn-info btn-sm">
                                                            <i class="fas fa-list me-1"></i> Manage Applications
                                                        </a>
                                                    </div>
                                                    <div class="mt-2 small text-muted">
                                                        <span class="badge bg-warning">{{ $stats['pending_applications'] ?? 0 }} Pending</span>
                                                        <span class="badge bg-success">{{ $stats['approved_applications'] ?? 0 }} Approved</span>
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
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin dashboard loaded');

    // Auto-refresh security alerts every 60 seconds
    setInterval(() => {
        // You can implement AJAX security alerts refresh here
        console.log('Security alerts refresh check');
    }, 60000);

    // Add hover effects to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.transition = 'transform 0.2s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection

<style>
.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.badge {
    font-size: 0.7em;
}
</style>
