@extends('layouts.admin')

@section('title', 'User Management - Admin Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users"></i> User Management
        </h1>
        <div class="btn-group">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="{{ route('admin.security.report') }}" class="btn btn-warning btn-sm">
                <i class="fas fa-shield-alt"></i> Security Report
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $users->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                PWD Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('role', 'pwd')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wheelchair fa-2x text-gray-300"></i>
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
                                Active Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('is_active', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Locked Accounts
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $users->where('account_locked_until', '>', now())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-lock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filters & Search
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="role" class="font-weight-bold">Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="">All Roles</option>
                                @foreach($roles as $roleValue => $roleLabel)
                                    <option value="{{ $roleValue }}" {{ request('role') == $roleValue ? 'selected' : '' }}>
                                        {{ $roleLabel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="activity" class="font-weight-bold">Activity Status</label>
                            <select class="form-control" id="activity" name="activity">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('activity') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('activity') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="locked" {{ request('activity') == 'locked' ? 'selected' : '' }}>Locked</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="security" class="font-weight-bold">Security Status</label>
                            <select class="form-control" id="security" name="security">
                                <option value="">All Security Levels</option>
                                <option value="needs_attention" {{ request('security') == 'needs_attention' ? 'selected' : '' }}>Needs Attention</option>
                                <option value="strong" {{ request('security') == 'strong' ? 'selected' : '' }}>Strong Security</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="search" class="font-weight-bold">Search Users</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Name, email, phone...">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-filter"></i> Apply Filters
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> User Accounts
            </h6>
            <span class="badge badge-primary badge-pill">{{ $users->total() }} users</span>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>User Information</th>
                                <th>Role & Status</th>
                                <th>Security</th>
                                <th>Last Activity</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="mr-3">
                                                <img class="rounded-circle"
                                                     src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                                     alt="{{ $user->name }}" width="45" height="45">
                                            </div>
                                            <div>
                                                <strong class="d-block">{{ $user->name }}</strong>
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope"></i> {{ $user->email }}
                                                </small>
                                                @if($user->phone)
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone"></i> {{ $user->phone }}
                                                    </small>
                                                @endif
                                                <br>
                                                <small class="text-muted">
                                                    ID: {{ $user->id }} | Registered: {{ $user->created_at->format('M j, Y') }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-2">
                                            @if($user->role === 'admin')
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-user-shield"></i> Admin
                                                </span>
                                            @elseif($user->role === 'pwd')
                                                <span class="badge badge-success">
                                                    <i class="fas fa-wheelchair"></i> PWD
                                                </span>
                                            @else
                                                <span class="badge badge-info">
                                                    <i class="fas fa-user"></i> User
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mb-1">
                                            @if($user->is_active)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i> Active
                                                </span>
                                            @else
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-times"></i> Inactive
                                                </span>
                                            @endif
                                        </div>

                                        @if($user->account_locked_until && $user->account_locked_until->isFuture())
                                            <span class="badge badge-danger">
                                                <i class="fas fa-lock"></i> Locked
                                            </span>
                                        @endif

                                        @if(!$user->email_verified_at)
                                            <span class="badge badge-warning mt-1">
                                                <i class="fas fa-envelope"></i> Unverified
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="security-indicators">
                                            @if($user->password_meets_current_standards)
                                                <span class="badge badge-success mb-1">
                                                    <i class="fas fa-shield-alt"></i> Strong Password
                                                </span>
                                            @else
                                                <span class="badge badge-warning mb-1">
                                                    <i class="fas fa-shield-alt"></i> Weak Password
                                                </span>
                                            @endif

                                            @if($user->two_factor_secret)
                                                <span class="badge badge-info mb-1">
                                                    <i class="fas fa-mobile-alt"></i> 2FA Enabled
                                                </span>
                                            @endif

                                            @if($user->failed_login_attempts >= 3)
                                                <span class="badge badge-danger">
                                                    <i class="fas fa-exclamation-triangle"></i> Failed Logins
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($user->last_login_at)
                                            <div class="text-sm">
                                                <strong>Last Login:</strong>
                                                {{ $user->last_login_at->diffForHumans() }}
                                            </div>
                                            <div class="text-xs text-muted">
                                                {{ $user->last_login_at->format('M j, Y g:i A') }}
                                            </div>
                                        @else
                                            <span class="text-muted">Never logged in</span>
                                        @endif

                                        @if($user->last_admin_action_at)
                                            <div class="text-xs text-muted mt-1">
                                                <strong>Last Admin Action:</strong>
                                                {{ $user->last_admin_action_at->diffForHumans() }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group-vertical btn-group-sm" role="group">
                                            <!-- View Button -->
                                            <a href="{{ route('admin.users.show', $user->id) }}"
                                               class="btn btn-info btn-sm mb-1" title="View Details">
                                                <i class="fas fa-eye"></i> View
                                            </a>

                                            <!-- Activate/Deactivate Button -->
                                            @if($user->is_active)
                                                <form action="{{ route('admin.users.deactivate', $user->id) }}"
                                                      method="POST" class="d-inline mb-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning btn-sm w-100"
                                                            title="Deactivate User"
                                                            onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                        <i class="fas fa-pause"></i> Deactivate
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.users.activate', $user->id) }}"
                                                      method="POST" class="d-inline mb-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm w-100"
                                                            title="Activate User">
                                                        <i class="fas fa-play"></i> Activate
                                                    </button>
                                                </form>
                                            @endif

                                            <!-- Unlock Button -->
                                            @if($user->account_locked_until && $user->account_locked_until->isFuture())
                                                <form action="{{ route('admin.users.unlock', $user->id) }}"
                                                      method="POST" class="d-inline mb-1">
                                                    @csrf
                                                    <button type="submit" class="btn btn-secondary btn-sm w-100"
                                                            title="Unlock Account">
                                                        <i class="fas fa-unlock"></i> Unlock
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h3 class="text-muted">No Users Found</h3>
                    <p class="text-muted">No users match your current filters.</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Clear Filters
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>


<!-- Accessibility Widget -->
@include('partials.accessibility-widget')
@endsection

@section('scripts')
<script>
    // Auto-submit form when filters change
    document.addEventListener('DOMContentLoaded', function() {
        const filters = ['role', 'activity', 'security'];
        filters.forEach(filter => {
            const element = document.getElementById(filter);
            if (element) {
                element.addEventListener('change', function() {
                    this.form.submit();
                });
            }
        });

        // Remove DataTables initialization since we're using Laravel pagination
        // DataTables conflicts with server-side Laravel pagination
    });
</script>
@endsection
