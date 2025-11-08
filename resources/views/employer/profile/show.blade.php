@extends('employer.layouts.employer')

@section('title', 'Employer Profile')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Employer Profile</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('employer.profile.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- Profile Overview -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Company Overview</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Company Name:</th>
                                <td>{{ $user->company_name ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Company Size:</th>
                                <td>{{ $user->company_size ? ucfirst($user->company_size) . ' employees' : 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Company Type:</th>
                                <td>{{ $user->company_type ? ucfirst($user->company_type) : 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Website:</th>
                                <td>
                                    @if($user->website)
                                        <a href="{{ $user->website }}" target="_blank">{{ $user->website }}</a>
                                    @else
                                        Not provided
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Contact Person:</th>
                                <td>{{ $user->name ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $user->phone ?? 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $user->address ?? 'Not provided' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($user->description)
                <div class="mt-4">
                    <h6>Company Description</h6>
                    <p class="text-muted">{{ $user->description }}</p>
                </div>
                @endif

                @if($user->hasResume())
                <div class="mt-4">
                    <h6>Company Resume</h6>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Resume uploaded
                        <a href="{{ $user->resume_url }}" target="_blank" class="btn btn-sm btn-outline-primary ms-2">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Statistics (for verified employers) -->
        @if($user->isEmployerVerified() && isset($stats))
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <h3 class="text-primary">{{ $stats['total_jobs'] ?? 0 }}</h3>
                            <small class="text-muted">Total Jobs</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <h3 class="text-success">{{ $stats['active_jobs'] ?? 0 }}</h3>
                            <small class="text-muted">Active Jobs</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <h3 class="text-info">{{ $stats['total_applications'] ?? 0 }}</h3>
                            <small class="text-muted">Total Applications</small>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <h3 class="text-warning">{{ $stats['response_rate'] ?? 0 }}%</h3>
                            <small class="text-muted">Response Rate</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Verification Status -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Verification Status</h6>
            </div>
            <div class="card-body text-center">
                @if($user->isEmployerVerified())
                    <div class="text-success mb-2">
                        <i class="fas fa-check-circle fa-3x"></i>
                    </div>
                    <h5 class="text-success">Verified</h5>
                    <p class="text-muted small">Your account is fully verified</p>
                @elseif($user->isEmployerPendingVerification())
                    <div class="text-warning mb-2">
                        <i class="fas fa-clock fa-3x"></i>
                    </div>
                    <h5 class="text-warning">Pending</h5>
                    <p class="text-muted small">Verification under review</p>
                @else
                    <div class="text-secondary mb-2">
                        <i class="fas fa-user-shield fa-3x"></i>
                    </div>
                    <h5 class="text-secondary">Unverified</h5>
                    <p class="text-muted small">Apply for verification</p>
                    <a href="{{ route('employer.verification.apply') }}" class="btn btn-sm btn-primary">Apply Now</a>
                @endif
            </div>
        </div>

        <!-- Profile Completion -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Profile Completion</h6>
            </div>
            <div class="card-body">
                <div class="progress mb-3" style="height: 20px;">
                    <div class="progress-bar {{ $user->getEmployerProfileCompletion() == 100 ? 'bg-success' : 'bg-primary' }}"
                         role="progressbar" style="width: {{ $user->getEmployerProfileCompletion() }}%;">
                        {{ $user->getEmployerProfileCompletion() }}%
                    </div>
                </div>
                <a href="{{ route('employer.profile.edit') }}" class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-edit"></i> Improve Profile
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Quick Links</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($user->isEmployerVerified())
                        <a href="{{ route('employer.job-postings.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> Post New Job
                        </a>
                        <a href="{{ route('employer.applications.index') }}" class="btn btn-outline-info">
                            <i class="fas fa-users"></i> View Applications
                        </a>
                    @else
                        <a href="{{ route('employer.verification.requirements') }}" class="btn btn-outline-info">
                            <i class="fas fa-list-alt"></i> View Requirements
                        </a>
                        <a href="{{ route('employer.job-drafts.create') }}" class="btn btn-outline-success">
                            <i class="fas fa-file-alt"></i> Create Job Draft
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
