@extends('layouts.admin')

@section('title', $user->name . ' - User Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user"></i> User Details: {{ $user->name }}
        </h1>
        <div class="btn-group">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column - User Information -->
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                    <div class="status-badges">
                        @if($user->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                        @if($user->is_locked)
                            <span class="badge badge-danger">Locked</span>
                        @endif
                    </div>
                </div>
                <div class="card-body text-center">
                    <img class="img-profile rounded-circle mb-3"
                         src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                         alt="{{ $user->name }}" width="120" height="120">

                    <h4 class="text-primary">{{ $user->name }}</h4>

                    <div class="mb-3">
                        @if($user->role === 'admin')
                            <span class="badge badge-danger badge-lg p-2">
                                <i class="fas fa-user-shield"></i> Administrator
                            </span>
                        @elseif($user->role === 'pwd')
                            <span class="badge badge-success badge-lg p-2">
                                <i class="fas fa-wheelchair"></i> PWD User
                            </span>
                        @else
                            <span class="badge badge-info badge-lg p-2">
                                <i class="fas fa-user"></i> Regular User
                            </span>
                        @endif
                    </div>

                    <div class="text-left">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="font-weight-bold text-right" width="40%">Email:</td>
                                <td>
                                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                                    @if(!$user->email_verified_at)
                                        <span class="badge badge-warning ml-1">Unverified</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-right">Phone:</td>
                                <td>
                                    @if($user->phone)
                                        <a href="tel:{{ $user->phone }}">{{ $user->phone }}</a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-right">User ID:</td>
                                <td><code>#{{ $user->id }}</code></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-right">Registered:</td>
                                <td>{{ $user->created_at->format('F j, Y g:i A') }}</td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold text-right">Last Updated:</td>
                                <td>{{ $user->updated_at->format('F j, Y g:i A') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Security Overview Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Security Overview</h6>
                </div>
                <div class="card-body">
                    <div class="security-metrics">
                        <div class="mb-3">
                            <strong>Security Score:</strong>
                            <div class="progress mt-1">
                                <div class="progress-bar bg-{{ $securityOverview['score_class'] }}"
                                     role="progressbar"
                                     style="width: {{ $securityOverview['score_percentage'] }}%"
                                     aria-valuenow="{{ $securityOverview['score_percentage'] }}"
                                     aria-valuemin="0"
                                     aria-valuemax="100">
                                    {{ $securityOverview['score_percentage'] }}%
                                </div>
                            </div>
                        </div>

                        <div class="security-features">
                            @foreach($securityOverview['features'] as $feature)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>{{ $feature['label'] }}:</span>
                                    <span class="badge badge-{{ $feature['status_class'] }}">
                                        <i class="fas fa-{{ $feature['icon'] }}"></i>
                                        {{ $feature['status'] }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        @if(!empty($securityOverview['recommendations']))
                            <div class="mt-3">
                                <strong>Recommendations:</strong>
                                <ul class="list-unstyled mt-1">
                                    @foreach($securityOverview['recommendations'] as $recommendation)
                                        <li class="text-sm text-danger">
                                            <i class="fas fa-exclamation-circle"></i> {{ $recommendation }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="btn-group-vertical w-100" role="group">
                        @if($user->is_active)
                            <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" class="w-100 mb-2">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm w-100"
                                        onclick="return confirm('Are you sure you want to deactivate this user?')">
                                    <i class="fas fa-pause"></i> Deactivate User
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="w-100 mb-2">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="fas fa-play"></i> Activate User
                                </button>
                            </form>
                        @endif

                        @if($user->is_locked)
                            <form action="{{ route('admin.users.unlock', $user->id) }}" method="POST" class="w-100 mb-2">
                                @csrf
                                <button type="submit" class="btn btn-secondary btn-sm w-100">
                                    <i class="fas fa-unlock"></i> Unlock Account
                                </button>
                            </form>
                        @endif

                        <!-- Role Change Form -->
                        <form action="{{ route('admin.users.update-role', $user->id) }}" method="POST" class="w-100">
                            @csrf
                            <div class="form-group mb-2">
                                <label for="role" class="small font-weight-bold">Change Role:</label>
                                <select name="role" id="role" class="form-control form-control-sm" onchange="this.form.submit()">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Regular User</option>
                                    <option value="pwd" {{ $user->role == 'pwd' ? 'selected' : '' }}>PWD User</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Administrator</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - User Activity & Details -->
        <div class="col-lg-8">
            <!-- Activity Statistics -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Job Applications
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $applicationStats['total'] }}
                                    </div>
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
                                        Training Enrollments
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $trainingStats['total'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-graduation-cap fa-2x text-gray-300"></i>
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
                                        Login Sessions
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        {{ $loginStats['total_logins'] }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
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
                                        Last Activity
                                    </div>
                                    <div class="h6 mb-0 font-weight-bold text-gray-800">
                                        @if($user->last_activity_at)
                                            {{ $user->last_activity_at->diffForHumans() }}
                                        @else
                                            Never
                                        @endif
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clock fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PWD Profile Information -->
            @if($user->pwdProfile)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">PWD Profile Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="font-weight-bold" width="40%">Disability Type:</td>
                                    <td>{{ $user->pwdProfile->disability_type ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Disability Degree:</td>
                                    <td>{{ $user->pwdProfile->disability_degree ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Birth Date:</td>
                                    <td>{{ $user->pwdProfile->birth_date ? $user->pwdProfile->birth_date->format('F j, Y') : 'Not specified' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="font-weight-bold" width="40%">PWD ID:</td>
                                    <td>{{ $user->pwdProfile->pwd_id ?? 'Not provided' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Contact Person:</td>
                                    <td>{{ $user->pwdProfile->contact_person ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Contact Number:</td>
                                    <td>{{ $user->pwdProfile->contact_number ?? 'Not specified' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Job Applications -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Job Applications</h6>
                    <span class="badge badge-primary">{{ $user->jobApplications->count() }} total</span>
                </div>
                <div class="card-body">
                    @if($user->jobApplications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Company</th>
                                        <th>Status</th>
                                        <th>Applied Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->jobApplications->take(5) as $application)
                                        <tr>
                                            <td>{{ $application->jobPosting->title ?? 'N/A' }}</td>
                                            <td>{{ $application->jobPosting->company ?? 'N/A' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $application->status === 'approved' ? 'success' : ($application->status === 'rejected' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $application->created_at->format('M j, Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($user->jobApplications->count() > 5)
                            <div class="text-center mt-2">
                                <a href="#" class="btn btn-sm btn-outline-primary">View All Applications</a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center mb-0">No job applications found.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Training Enrollments -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Training Enrollments</h6>
                    <span class="badge badge-primary">{{ $user->trainingEnrollments->count() }} total</span>
                </div>
                <div class="card-body">
                    @if($user->trainingEnrollments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Training Program</th>
                                        <th>Enrollment Date</th>
                                        <th>Status</th>
                                        <th>Completion Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->trainingEnrollments->take(5) as $enrollment)
                                        <tr>
                                            <td>{{ $enrollment->skillTraining->title ?? 'N/A' }}</td>
                                            <td>{{ $enrollment->created_at->format('M j, Y') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $enrollment->status === 'completed' ? 'success' : ($enrollment->status === 'cancelled' ? 'danger' : 'info') }}">
                                                    {{ ucfirst($enrollment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($enrollment->completion_date)
                                                    {{ $enrollment->completion_date->format('M j, Y') }}
                                                @else
                                                    <span class="text-muted">Not completed</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($user->trainingEnrollments->count() > 5)
                            <div class="text-center mt-2">
                                <a href="#" class="btn btn-sm btn-outline-primary">View All Enrollments</a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted text-center mb-0">No training enrollments found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
