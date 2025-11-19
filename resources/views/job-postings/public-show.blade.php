@extends('layouts.app')

@section('title', $jobPosting->title . ' - PWD Job Portal')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('job-postings.public') }}">Job Postings</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $jobPosting->title }}</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4 border-0" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="h3 mb-2" style="color: #2E8B57;">{{ $jobPosting->title }}</h1>
                            <h5 class="text-dark mb-3">
                                <i class="fas fa-building me-2"></i>{{ $jobPosting->company }}
                            </h5>
                        </div>
                        @if($jobPosting->is_featured)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i> Featured
                            </span>
                        @endif
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                <span>{{ $jobPosting->location }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-briefcase text-muted me-2"></i>
                                <span class="badge" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%);">{{ $jobPosting->employment_type }}</span>
                            </div>
                        </div>
                    </div>

                    @auth
                        @if(auth()->user()->role === 'pwd')
                            @php
                                $hasApplied = auth()->user()->jobApplications()
                                    ->where('job_posting_id', $jobPosting->id)
                                    ->exists();
                            @endphp

                            @if($hasApplied)
                                <div class="alert alert-success" role="alert">
                                    <i class="fas fa-check-circle me-2"></i>
                                    You have already applied for this position.
                                </div>
                            @else
                                <form action="{{ route('job.apply', $jobPosting) }}" method="POST" class="mb-4">
                                    @csrf
                                    <button type="submit" class="btn btn-lg w-100" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); color: white; border: none;">
                                        <i class="fas fa-paper-plane me-2"></i> Apply Now
                                    </button>
                                </form>
                            @endif
                        @endif
                    @else
                        <div class="alert alert-info" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Please <a href="{{ route('login') }}" class="alert-link">login</a> to apply for this job.
                        </div>
                    @endauth
                </div>
            </div>

            <div class="card mb-4 border-0" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important;">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-file-alt me-2"></i>Job Description
                    </h5>
                </div>
                <div class="card-body">
                    <div class="job-description">
                        {!! nl2br(e($jobPosting->description)) !!}
                    </div>
                </div>
            </div>

            @if($jobPosting->requirements)
                <div class="card mb-4 border-0" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-dark">
                            <i class="fas fa-list-check me-2"></i>Requirements
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="requirements">
                            {!! nl2br(e($jobPosting->requirements)) !!}
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card mb-4 border-0" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important;">
                <div class="card-header text-white py-3" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%);">,
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Quick Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @if($jobPosting->application_deadline)
                            <div class="list-group-item border-0 px-0">
                                <small class="text-muted d-block mb-1">Application Deadline</small>
                                <strong>{{ $jobPosting->application_deadline->format('M d, Y') }}</strong>
                            </div>
                        @endif

                        <div class="list-group-item border-0 px-0">
                            <small class="text-muted d-block mb-1">Posted On</small>
                            <strong>{{ $jobPosting->created_at->format('M d, Y') }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            @if($relatedJobs->count() > 0)
                <div class="card border-0" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important;">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 text-dark">
                            <i class="fas fa-briefcase me-2"></i>Related Jobs
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @foreach($relatedJobs as $relatedJob)
                                <a href="{{ route('job-postings.public.show', $relatedJob) }}"
                                   class="list-group-item list-group-item-action border-0">
                                    <h6 class="mb-1">{{ $relatedJob->title }}</h6>
                                    <small class="text-muted d-block">{{ $relatedJob->company }}</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Mobile Responsive Styles for Job Details */
@media (max-width: 992px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }

    .col-lg-4 {
        margin-top: 1.5rem;
    }
}

@media (max-width: 768px) {
    .container.py-4 {
        padding-top: 1.5rem !important;
        padding-bottom: 1.5rem !important;
    }

    /* Breadcrumb responsive */
    .breadcrumb {
        font-size: 0.875rem;
        padding: 0.5rem 0;
    }

    .breadcrumb-item + .breadcrumb-item::before {
        padding-right: 0.3rem;
        padding-left: 0.3rem;
    }

    /* Job title responsive */
    .h3 {
        font-size: 1.25rem !important;
    }

    h5 {
        font-size: 1rem !important;
    }

    /* Card padding responsive */
    .card-body.p-4 {
        padding: 1.5rem !important;
    }

    .card-header.py-3 {
        padding: 0.75rem 1rem !important;
    }

    /* Job info grid */
    .row.mb-4 .col-md-6 {
        flex: 0 0 100%;
        max-width: 100%;
        margin-bottom: 0.75rem !important;
    }

    /* Apply button responsive */
    .btn-lg {
        font-size: 1rem;
        padding: 0.75rem 1rem;
    }

    /* Job description text */
    .job-description, .requirements {
        font-size: 0.9rem;
        line-height: 1.6;
    }

    /* List group items */
    .list-group-item {
        padding: 0.75rem 0;
    }

    .list-group-item small {
        font-size: 0.8rem;
    }

    .list-group-item strong {
        font-size: 0.95rem;
    }

    /* Related jobs */
    .list-group-item-action h6 {
        font-size: 0.9rem;
    }

    .list-group-item-action small {
        font-size: 0.8rem;
    }
}

@media (max-width: 576px) {
    .container.py-4 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }

    /* Breadcrumb compact */
    .breadcrumb {
        font-size: 0.75rem;
        flex-wrap: wrap;
    }

    /* Job title smaller */
    .h3 {
        font-size: 1.1rem !important;
    }

    h5 {
        font-size: 0.95rem !important;
    }

    /* Card padding compact */
    .card-body.p-4 {
        padding: 1rem !important;
    }

    /* Featured badge */
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }

    /* Job info compact */
    .d-flex.align-items-center {
        font-size: 0.875rem;
    }

    .d-flex.align-items-center i {
        font-size: 0.875rem;
    }

    /* Apply button full width */
    .btn-lg.w-100 {
        font-size: 0.95rem;
        padding: 0.65rem 0.75rem;
    }

    /* Alert compact */
    .alert {
        padding: 0.75rem;
        font-size: 0.875rem;
    }

    /* Job description compact */
    .job-description, .requirements {
        font-size: 0.85rem;
        line-height: 1.5;
    }

    /* Card headers */
    .card-header h5 {
        font-size: 0.95rem !important;
    }
}

/* Touch device improvements */
@media (hover: none) and (pointer: coarse) {
    .btn, .badge, a {
        min-height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .list-group-item-action {
        min-height: 60px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
}
</style>
@endsection
