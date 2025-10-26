@extends('layouts.app')

@section('title', 'Available Jobs - PWD System')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-briefcase"></i>
                        Available Job Opportunities
                    </h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($jobPostings->count() > 0)
                        <div class="row">
                            @foreach($jobPostings as $job)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 job-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">{{ $job->title }}</h5>
                                            @if(auth()->user() && auth()->user()->role === 'pwd')
                                                @php
                                                    $hasApplied = auth()->user()->jobApplications()
                                                        ->where('job_posting_id', $job->id)
                                                        ->exists();
                                                @endphp
                                                @if($hasApplied)
                                                    <span class="badge bg-success">Applied</span>
                                                @else
                                                    <form action="{{ route('job.apply', $job) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">
                                                            <i class="fas fa-paper-plane"></i> Apply Now
                                                        </button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong><i class="fas fa-building"></i> Company:</strong>
                                                {{ $job->company }}
                                            </div>
                                            <div class="mb-2">
                                                <strong><i class="fas fa-map-marker-alt"></i> Location:</strong>
                                                {{ $job->location }}
                                            </div>
                                            <div class="mb-2">
                                                <strong><i class="fas fa-clock"></i> Employment Type:</strong>
                                                {{ $job->employment_type }}
                                            </div>
                                            @if($job->salary)
                                                <div class="mb-2">
                                                    <strong><i class="fas fa-money-bill-wave"></i> Salary:</strong>
                                                    {{ $job->salary }}
                                                </div>
                                            @endif
                                            @if($job->application_deadline)
    <div class="mb-3">
        <strong><i class="fas fa-calendar-times"></i> Application Deadline:</strong>
        <span class="{{ $job->application_deadline->isPast() ? 'text-danger' : 'text-success' }}">
            {{ $job->formatted_deadline }}
        </span>
    </div>
@else
    <div class="mb-3">
        <strong><i class="fas fa-calendar-times"></i> Application Deadline:</strong>
        <span class="text-info">No deadline</span>
    </div>
@endif
                                            <div class="job-description">
                                                <strong>Description:</strong>
                                                <p class="mt-1">{{ Str::limit($job->description, 150) }}</p>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <a href="{{ route('job-postings.public.show', $job) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                            @if(auth()->user() && auth()->user()->role === 'pwd' && !$hasApplied)
                                                <form action="{{ route('job.apply', $job) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-paper-plane"></i> Quick Apply
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4">
                            {{ $jobPostings->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                            <h3 class="text-muted">No Job Opportunities Available</h3>
                            <p class="text-muted">There are currently no active job postings. Please check back later.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.job-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.job-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}
.job-description {
    max-height: 80px;
    overflow: hidden;
}
</style>
@endsection
