@extends('layouts.app')

@section('title', $jobPosting->title . ' - PWD System')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('job-postings.public') }}">Job Opportunities</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Job Details</li>
                </ol>
            </nav>

            <!-- Job Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h2 mb-2">{{ $jobPosting->title }}</h1>
                            <h2 class="h5 text-primary mb-3">{{ $jobPosting->company }}</h2>
                        </div>
                        <div class="text-end">
                            @if($jobPosting->application_deadline && $jobPosting->application_deadline->isFuture())
                                <div class="badge bg-success mb-2">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $jobPosting->application_deadline->diffForHumans() }} left
                                </div>
                            @endif
                            <div class="text-muted small">
                                <i class="fas fa-eye me-1"></i>{{ $jobPosting->views }} views
                            </div>
                        </div>
                    </div>

                    <!-- Job Meta -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                <span>{{ $jobPosting->location }}</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock text-muted me-2"></i>
                                <span>{{ $jobPosting->employment_type }}</span>
                            </div>
                            @if($jobPosting->salary)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-money-bill-wave text-muted me-2"></i>
                                <span class="fw-semibold">{{ $jobPosting->salary }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($jobPosting->job_category)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-tag text-muted me-2"></i>
                                <span>{{ $jobPosting->job_category }}</span>
                            </div>
                            @endif
                            @if($jobPosting->experience_level)
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-chart-line text-muted me-2"></i>
                                <span>{{ $jobPosting->experience_level }}</span>
                            </div>
                            @endif
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-calendar text-muted me-2"></i>
                                <span>
                                    @if($jobPosting->application_deadline)
                                        Apply by {{ $jobPosting->application_deadline->format('F j, Y') }}
                                    @else
                                        No deadline
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mb-4">
                        @if(auth()->user() && auth()->user()->role === 'pwd')
                            @php
                                $hasApplied = auth()->user()->jobApplications()
                                    ->where('job_posting_id', $jobPosting->id)
                                    ->exists();
                            @endphp

                            @if($hasApplied)
                                <button class="btn btn-success" disabled>
                                    <i class="fas fa-check me-1"></i>Already Applied
                                </button>
                            @elseif(!$jobPosting->application_deadline || $jobPosting->application_deadline->isFuture())
                                <form action="{{ route('job-postings.apply', $jobPosting) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-1"></i>Apply Now
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="fas fa-times me-1"></i>Application Closed
                                </button>
                            @endif
                        @elseif(!auth()->user())
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-1"></i>Login to Apply
                            </a>
                        @endif

                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="fas fa-print me-1"></i>Print
                        </button>
                    </div>
                </div>
            </div>

            <!-- Job Description -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h3 class="h5 mb-0"><i class="fas fa-file-alt me-2"></i>Job Description</h3>
                </div>
                <div class="card-body">
                    <div class="job-content">
                        {!! nl2br(e($jobPosting->description)) !!}
                    </div>
                </div>
            </div>

            <!-- Requirements -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h3 class="h5 mb-0"><i class="fas fa-list-check me-2"></i>Requirements</h3>
                </div>
                <div class="card-body">
                    <div class="requirements-content">
                        {!! nl2br(e($jobPosting->requirements)) !!}
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            @if($jobPosting->contact_email || $jobPosting->contact_phone)
            <div class="card">
                <div class="card-header bg-light">
                    <h3 class="h5 mb-0"><i class="fas fa-envelope me-2"></i>Contact Information</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($jobPosting->contact_email)
                        <div class="col-md-6">
                            <strong>Email:</strong>
                            <a href="mailto:{{ $jobPosting->contact_email }}">{{ $jobPosting->contact_email }}</a>
                        </div>
                        @endif
                        @if($jobPosting->contact_phone)
                        <div class="col-md-6">
                            <strong>Phone:</strong>
                            <a href="tel:{{ $jobPosting->contact_phone }}">{{ $jobPosting->contact_phone }}</a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Company Info -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h4 class="h6 mb-0"><i class="fas fa-building me-2"></i>About the Company</h4>
                </div>
                <div class="card-body">
                    <h5 class="text-primary">{{ $jobPosting->company }}</h5>
                    <p class="text-muted mb-0">
                        Located in {{ $jobPosting->location }}
                    </p>
                </div>
            </div>

            <!-- Job Summary -->
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h4 class="h6 mb-0"><i class="fas fa-info-circle me-2"></i>Job Summary</h4>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Employment Type:</span>
                            <strong>{{ $jobPosting->employment_type }}</strong>
                        </div>
                        @if($jobPosting->salary)
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Salary:</span>
                            <strong>{{ $jobPosting->salary }}</strong>
                        </div>
                        @endif
                        @if($jobPosting->job_category)
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Category:</span>
                            <strong>{{ $jobPosting->job_category }}</strong>
                        </div>
                        @endif
                        @if($jobPosting->experience_level)
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Experience:</span>
                            <strong>{{ $jobPosting->experience_level }}</strong>
                        </div>
                        @endif
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Deadline:</span>
                            <strong>
                                @if($jobPosting->application_deadline)
                                    {{ $jobPosting->application_deadline->format('M j, Y') }}
                                @else
                                    No deadline
                                @endif
                            </strong>
                        </div>
                        <div class="list-group-item d-flex justify-content-between px-0">
                            <span>Posted:</span>
                            <strong>{{ $jobPosting->created_at->diffForHumans() }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Jobs -->
            @if($relatedJobs->count() > 0)
            <div class="card">
                <div class="card-header bg-light">
                    <h4 class="h6 mb-0"><i class="fas fa-briefcase me-2"></i>Related Jobs</h4>
                </div>
                <div class="card-body">
                    @foreach($relatedJobs as $relatedJob)
                    <div class="mb-3 pb-3 border-bottom">
                        <h6 class="mb-1">
                            <a href="{{ route('job-postings.public.show', $relatedJob) }}" class="text-decoration-none">
                                {{ $relatedJob->title }}
                            </a>
                        </h6>
                        <div class="small text-muted">
                            {{ $relatedJob->company }} • {{ $relatedJob->employment_type }}
                        </div>
                        @if($relatedJob->salary)
                        <div class="small text-success">
                            {{ $relatedJob->salary }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.job-content, .requirements-content {
    line-height: 1.8;
    white-space: pre-line;
}

.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card-header {
    border-bottom: 1px solid #e9ecef;
}

.list-group-item {
    border: none;
    padding: 0.75rem 0;
}

@media print {
    .breadcrumb, .btn, .card-header, .related-jobs {
        display: none !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>
@endsection
