@extends('layouts.app') <!-- We'll use the existing app layout -->

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Manage Job Postings</h4>
                </div>
                <div class="card-body">
                    <a href="{{ route('job-postings.create') }}" class="btn btn-primary mb-3">Create New Job Posting</a>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Company</th>
                                    <th>Location</th>
                                    <th>Employment Type</th>
                                    <th>Salary</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jobPostings as $job)
                                    <tr>
                                        <td>{{ $job->title }}</td>
                                        <td>{{ $job->company }}</td>
                                        <td>{{ $job->location }}</td>
                                        <td>{{ $job->employment_type }}</td>
                                        <td>{{ $job->salary ? '₱' . number_format($job->salary, 2) : 'Negotiable' }}</td>
                                        <td>{{ $job->application_deadline ? $job->application_deadline->format('M d, Y') : 'No deadline' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $job->is_active ? 'success' : 'secondary' }}">
                                                {{ $job->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('job-postings.show', $job->id) }}" class="btn btn-info btn-sm">View</a>
                                            <a href="{{ route('job-postings.edit', $job->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                            <form action="{{ route('job-postings.destroy', $job->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No job postings found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
