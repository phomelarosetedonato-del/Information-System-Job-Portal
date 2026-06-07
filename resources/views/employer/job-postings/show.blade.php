@extends('employer.layouts.employer')

@section('content')
<div class="row mb-4">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2 gap-md-0">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-briefcase text-primary"></i> Job Posting Details
        </h2>
        <a href="{{ route('employer.job-postings.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Job Postings
        </a>
    </div>
</div>
<div class="card shadow-sm mt-3 mt-md-0">
    <div class="card-body">
        <h3 class="fw-bold mb-2">{{ $job->title }}</h3>
        <p class="mb-1"><strong>Company:</strong> {{ $job->company }}</p>
        <p class="mb-1"><strong>Location:</strong> {{ $job->location ? $job->location->name : 'N/A' }}</p>
        <p class="mb-1"><strong>Employment Type:</strong> {{ $job->employment_type }}</p>
        <p class="mb-1"><strong>Salary:</strong> {{ $job->salary ? '₱' . number_format($job->salary, 2) : 'Negotiable' }}</p>
        <p class="mb-1"><strong>Application Deadline:</strong> {{ $job->application_deadline ? $job->application_deadline->format('M d, Y') : 'No deadline' }}</p>
        <p class="mb-1"><strong>Status:</strong> <span class="badge bg-{{ $job->is_active ? 'success' : 'secondary' }}">{{ $job->is_active ? 'Active' : 'Inactive' }}</span></p>
        <hr>
        <h5>Description</h5>
        <p>{{ $job->description }}</p>
        <h5>Requirements</h5>
        <p>{{ $job->requirements }}</p>
        <h5>Qualified Disability Types</h5>
        <ul>
            @forelse($job->suitableDisabilityTypes as $type)
                <li>{{ $type->name }}</li>
            @empty
                <li>No specific disability type required.</li>
            @endforelse
        </ul>
        <hr>
        <div class="d-flex gap-2">
            <a href="{{ route('employer.job-postings.edit', $job->id) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('employer.job-postings.destroy', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this job posting?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
