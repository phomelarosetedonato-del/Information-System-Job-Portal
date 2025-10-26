@extends('layouts.admin')

@section('title', $jobPosting->title . ' - Admin Panel')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye"></i> Job Posting Details
        </h1>
        <div class="btn-group">
            <a href="{{ route('job-postings.edit', $jobPosting->id) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
            <form action="{{ route('job-postings.toggle-status', $jobPosting->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm">
                    <i class="fas fa-power-off"></i> {{ $jobPosting->is_active ? 'Deactivate' : 'Activate' }}
                </button>
            </form>
            <form action="{{ route('job-postings.duplicate', $jobPosting->id) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-info btn-sm">
                    <i class="fas fa-copy"></i> Duplicate
                </button>
            </form>
            <form action="{{ route('job-postings.destroy', $jobPosting->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"
                        onclick="return confirm('Are you sure you want to delete this job posting?')">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </form>
            <a href="{{ route('job-postings.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
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
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Job Header Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Job Information</h6>
                    <div class="status-badges">
                        @if($jobPosting->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-secondary">Inactive</span>
                        @endif
                        @if($jobPosting->application_deadline)
                            @if($jobPosting->application_deadline->isPast())
                                <span class="badge badge-warning">Expired</span>
                            @else
                                <span class="badge badge-info">Open</span>
                            @endif
                        @else
                            <span class="badge badge-info">No Deadline</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <h3 class="text-primary">{{ $jobPosting->title }}</h3>
                    <h5 class="text-gray-800">{{ $jobPosting->company }}</h5>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold" width="40%">Location:</td>
                                    <td>{{ $jobPosting->location }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Employment Type:</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $jobPosting->employment_type }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Job Category:</td>
                                    <td>{{ $jobPosting->job_category ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Experience Level:</td>
                                    <td>{{ $jobPosting->experience_level ?? 'Not specified' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless table-sm">
                                <tr>
                                    <td class="font-weight-bold" width="40%">Salary:</td>
                                    <td>
                                        @if($jobPosting->salary)
                                            <span class="text-success font-weight-bold">{{ $jobPosting->salary }}</span>
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Application Deadline:</td>
                                    <td>
                                        @if($jobPosting->application_deadline)
                                            <span class="{{ $jobPosting->application_deadline->isPast() ? 'text-danger' : 'text-success' }}">
                                                {{ $jobPosting->application_deadline->format('F j, Y') }}
                                                ({{ $jobPosting->application_deadline->diffForHumans() }})
                                            </span>
                                        @else
                                            <span class="text-info">No deadline</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Contact Email:</td>
                                    <td>
                                        @if($jobPosting->contact_email)
                                            <a href="mailto:{{ $jobPosting->contact_email }}">{{ $jobPosting->contact_email }}</a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="font-weight-bold">Contact Phone:</td>
                                    <td>
                                        @if($jobPosting->contact_phone)
                                            <a href="tel:{{ $jobPosting->contact_phone }}">{{ $jobPosting->contact_phone }}</a>
                                        @else
                                            <span class="text-muted">Not provided</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Description Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Job Description</h6>
                </div>
                <div class="card-body">
                    <div class="job-content">
                        {!! nl2br(e($jobPosting->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Requirements Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Requirements</h6>
                </div>
                <div class="card-body">
                    <div class="requirements-content">
                        {!! nl2br(e($jobPosting->requirements)) !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Statistics Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <div class="text-primary font-weight-bold h4">{{ $jobPosting->views }}</div>
                                    <div class="text-muted small">Total Views</div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="border rounded p-3">
                                    <div class="text-success font-weight-bold h4">{{ $jobPosting->applications->count() }}</div>
                                    <div class="text-muted small">Applications</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Applications by Status -->
                    <h6 class="font-weight-bold mt-4">Applications by Status</h6>
                    <div class="mt-3">
                        @foreach($applicationsByStatus as $status => $count)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-capitalize">{{ $status }}</span>
                                <span class="badge badge-primary">{{ $count }}</span>
                            </div>
                        @endforeach
                        @if($applicationsByStatus->isEmpty())
                            <p class="text-muted text-center">No applications yet</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Job Meta Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Job Meta</h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="font-weight-bold">Created:</td>
                            <td>{{ $jobPosting->created_at->format('M j, Y g:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Last Updated:</td>
                            <td>{{ $jobPosting->updated_at->format('M j, Y g:i A') }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Created By:</td>
                            <td>{{ $jobPosting->creator->name ?? 'System' }}</td>
                        </tr>
                        <tr>
                            <td class="font-weight-bold">Posting ID:</td>
                            <td><code>#{{ $jobPosting->id }}</code></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
