@extends('employer.layouts.employer')

@section('title', 'Manage Applications')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">
                <i class="fas fa-inbox text-primary"></i> Job Applications
            </h2>
            <p class="text-muted">Manage applications received for your job postings</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Applications</p>
                            <h3 class="mb-0">{{ $stats['total'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-inbox text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Pending Review</p>
                            <h3 class="mb-0">{{ $stats['pending'] }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-clock text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Shortlisted</p>
                            <h3 class="mb-0">{{ $stats['shortlisted'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-star text-info fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Approved</p>
                            <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('employer.applications.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Search applicant name or email" value="{{ request('search') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label small">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="hired" {{ request('status') == 'hired' ? 'selected' : '' }}>Hired</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label small">Job Posting</label>
                    <select name="job_posting_id" class="form-select">
                        <option value="">All Job Postings</option>
                        @foreach($jobPostings as $jobPosting)
                            <option value="{{ $jobPosting->id }}" {{ request('job_posting_id') == $jobPosting->id ? 'selected' : '' }}>
                                {{ $jobPosting->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications List -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-4">
                <i class="fas fa-list text-primary"></i> Applications List
            </h5>

            @if($applications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Applicant</th>
                                <th>Job Position</th>
                                <th>Applied Date</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($applications as $application)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $application->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $application->user->email }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $application->jobPosting->title }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            @if($application->jobPosting->location && is_object($application->jobPosting->location))
                                                {{ $application->jobPosting->location->name }}
                                            @else
                                                {{ $application->jobPosting->location ?? 'N/A' }}
                                            @endif
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <small>{{ $application->created_at->format('M d, Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $application->created_at->diffForHumans() }}</small>
                                </td>
                                <td>
                                    @if($application->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($application->status == 'shortlisted')
                                        <span class="badge bg-info">Shortlisted</span>
                                    @elseif($application->status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($application->status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($application->status == 'hired')
                                        <span class="badge bg-primary">Hired</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($application->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('applications.show', $application->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Applications Found</h5>
                    <p class="text-muted">
                        @if(request()->has('search') || request()->has('status') || request()->has('job_posting_id'))
                            Try adjusting your filters
                        @else
                            You haven't received any applications yet
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
