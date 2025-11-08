@extends('layouts.app')

@section('title', 'Application Details - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2 text-dark">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        Application Details
                    </h1>
                    <p class="mb-0 text-muted">View your job application information and status</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('applications.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Applications
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Status Header -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-dark">
                                <i class="fas fa-briefcase me-2"></i>
                                {{ $application->jobPosting->title }}
                            </h4>
                            @php
                                $statusColors = [
                                    'pending' => 'warning',
                                    'approved' => 'success',
                                    'rejected' => 'danger'
                                ];
                                $statusIcons = [
                                    'pending' => 'clock',
                                    'approved' => 'check-circle',
                                    'rejected' => 'times-circle'
                                ];
                            @endphp
                            <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }} fs-6">
                                <i class="fas fa-{{ $statusIcons[$application->status] ?? 'circle' }} me-1"></i>
                                {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Left Column - Job & Application Info -->
                    <div class="col-lg-6 mb-4">
                        <!-- Job Information -->
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="mb-0 text-primary">
                                    <i class="fas fa-building me-2"></i>
                                    Job Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Position Title</label>
                                        <p class="fw-semibold text-dark mb-0">{{ $application->jobPosting->title }}</p>
                                    </div>
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Company</label>
                                        <p class="mb-0">
                                            <i class="fas fa-building text-muted me-2"></i>
                                            {{ $application->jobPosting->company }}
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Location</label>
                                        <p class="mb-0">
                                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                            {{ $application->jobPosting->location ?? 'Not specified' }}
                                        </p>
                                    </div>
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Employment Type</label>
                                        <p class="mb-0">
                                            <i class="fas fa-briefcase text-muted me-2"></i>
                                            {{ $application->jobPosting->employment_type ?? 'Not specified' }}
                                        </p>
                                    </div>
                                    @if($application->jobPosting->salary)
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Salary</label>
                                        <p class="mb-0">
                                            <i class="fas fa-money-bill-wave text-muted me-2"></i>
                                            ₱{{ number_format($application->jobPosting->salary, 2) }}
                                        </p>
                                    </div>
                                    @endif
                                    @if($application->jobPosting->application_deadline)
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Application Deadline</label>
                                        <p class="mb-0">
                                            <i class="fas fa-clock text-muted me-2"></i>
                                            {{ $application->jobPosting->application_deadline->format('M j, Y') }}
                                        </p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Application Details -->
                    <div class="col-lg-6 mb-4">
                        <!-- Application Information -->
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="mb-0 text-primary">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Application Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Applied Date</label>
                                        <p class="mb-0">
                                            <i class="fas fa-calendar-check text-muted me-2"></i>
                                            {{ $application->created_at->format('F d, Y') }}
                                        </p>
                                        <small class="text-muted">{{ $application->created_at->format('h:i A') }}</small>
                                    </div>
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Last Updated</label>
                                        <p class="mb-0">
                                            <i class="fas fa-sync-alt text-muted me-2"></i>
                                            {{ $application->updated_at->format('F d, Y') }}
                                        </p>
                                        <small class="text-muted">{{ $application->updated_at->format('h:i A') }}</small>
                                    </div>
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Application Status</label>
                                        <div>
                                            <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                                <i class="fas fa-{{ $statusIcons[$application->status] ?? 'circle' }} me-1"></i>
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="small text-muted mb-1">Application ID</label>
                                        <p class="mb-0 text-muted">#{{ $application->id }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Job Description & Requirements -->
                <div class="row">
                    <!-- Job Description -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="mb-0 text-primary">
                                    <i class="fas fa-align-left me-2"></i>
                                    Job Description
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($application->jobPosting->description)
                                    <div class="text-muted">
                                        {!! nl2br(e($application->jobPosting->description)) !!}
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No description provided.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Requirements -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-white border-bottom py-3">
                                <h5 class="mb-0 text-primary">
                                    <i class="fas fa-list-check me-2"></i>
                                    Requirements
                                </h5>
                            </div>
                            <div class="card-body">
                                @if($application->jobPosting->requirements)
                                    <div class="text-muted">
                                        {!! nl2br(e($application->jobPosting->requirements)) !!}
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No specific requirements listed.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Notes -->
                @if($application->admin_notes)
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="card shadow-sm border-info">
                            <div class="card-header bg-info text-white border-bottom py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-sticky-note me-2"></i>
                                    Admin Notes
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="text-muted">
                                    {!! nl2br(e($application->admin_notes)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="row">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center py-4">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('applications.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-2"></i> Back to Applications
                                    </a>
                                    <a href="{{ route('job-postings.public') }}" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i> Browse More Jobs
                                    </a>
                                    @if($application->status === 'pending')
                                        <a href="{{ route('job-postings.public.show', $application->jobPosting) }}"
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-eye me-2"></i> View Job Posting
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 12px;
    }

    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }

    .btn-group .btn {
        border-radius: 8px;
        margin: 0 4px;
    }

    .text-muted {
        line-height: 1.6;
    }
</style>
@endsection
