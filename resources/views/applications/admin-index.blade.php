<!-- resources/views/applications/admin-index.blade.php -->

@extends('layouts.app')

@section('title', 'Manage Job Applications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Job Applications Management</h4>
                    <div class="d-flex">
                        <form action="{{ route('applications.index') }}" method="GET" class="me-2">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ request('search') }}">
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                <button class="btn btn-primary" type="submit">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Job Position</th>
                                        <th>Company</th>
                                        <th>Applied Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="ms-3">
                                                        <h6 class="mb-0">{{ $application->user->name }}</h6>
                                                        <small class="text-muted">{{ $application->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ $application->jobPosting->title }}</strong>
                                            </td>
                                            <td>{{ $application->jobPosting->company }}</td>
                                            <td>{{ $application->created_at->format('M j, Y') }}</td>
                                            <td>
                                                <span class="badge
                                                    @if($application->status == 'pending') bg-warning
                                                    @elseif($application->status == 'shortlisted') bg-info
                                                    @elseif($application->status == 'approved') bg-success
                                                    @elseif($application->status == 'rejected') bg-danger
                                                    @else bg-secondary @endif">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('applications.show', $application) }}"
                                                       class="btn btn-sm btn-outline-primary">View</a>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <span class="visually-hidden">Toggle Dropdown</span>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <form action="{{ route('applications.status', $application) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="status" value="shortlisted">
                                                                <button type="submit" class="dropdown-item">Shortlist</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('applications.status', $application) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="status" value="approved">
                                                                <button type="submit" class="dropdown-item">Approve</button>
                                                            </form>
                                                        </li>
                                                        <li>
                                                            <form action="{{ route('applications.status', $application) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="status" value="rejected">
                                                                <button type="submit" class="dropdown-item text-danger">Reject</button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            {{ $applications->links() }}
                        </div>
                    @else
                        <p class="text-muted text-center">No job applications found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
