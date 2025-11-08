@extends('layouts.app')

@section('title', 'My Job Applications - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2 text-dark">
                        <i class="fas fa-briefcase me-2 text-primary"></i>
                        My Job Applications
                    </h1>
                    <p class="mb-0 text-muted">Track and manage all your job applications in one place</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Application Statistics -->
        @if($applications->count() > 0)
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-primary h-100 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="text-primary mb-3">
                            <i class="fas fa-file-alt fa-3x"></i>
                        </div>
                        <h3 class="h2 text-primary mb-1">{{ $applications->count() }}</h3>
                        <h5 class="card-title text-muted">Total Applications</h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-warning h-100 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="text-warning mb-3">
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                        <h3 class="h2 text-warning mb-1">{{ $applications->where('status', 'pending')->count() }}</h3>
                        <h5 class="card-title text-muted">Pending</h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-success h-100 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="text-success mb-3">
                            <i class="fas fa-check-circle fa-3x"></i>
                        </div>
                        <h3 class="h2 text-success mb-1">{{ $applications->where('status', 'approved')->count() }}</h3>
                        <h5 class="card-title text-muted">Approved</h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-danger h-100 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="text-danger mb-3">
                            <i class="fas fa-times-circle fa-3x"></i>
                        </div>
                        <h3 class="h2 text-danger mb-1">{{ $applications->where('status', 'rejected')->count() }}</h3>
                        <h5 class="card-title text-muted">Rejected</h5>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Applications Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-dark">
                                <i class="fas fa-history me-2 text-primary"></i>
                                Application History
                            </h4>
                            <span class="badge bg-light text-dark fs-6 border">
                                {{ $applications->count() }} {{ Str::plural('application', $applications->count()) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($applications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 ps-4">Job Position & Details</th>
                                            <th class="border-0">Company</th>
                                            <th class="border-0">Applied Date</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0 pe-4 text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($applications as $application)
                                            <tr class="border-bottom">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 text-dark">{{ $application->jobPosting->title }}</h6>
                                                            <div class="text-muted small mb-2">
                                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                                {{ $application->jobPosting->location ?? 'Not specified' }}
                                                            </div>
                                                            @if($application->jobPosting->application_deadline)
                                                                <div class="text-muted small">
                                                                    <i class="fas fa-clock me-1"></i>
                                                                    Deadline: {{ $application->jobPosting->application_deadline->format('M j, Y') }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted">
                                                        <i class="fas fa-building me-1"></i>
                                                        {{ $application->jobPosting->company }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted small">
                                                        <div class="mb-1">{{ $application->created_at->format('M j, Y') }}</div>
                                                        <div>{{ $application->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </td>
                                                <td>
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
                                                    <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                                        <i class="fas fa-{{ $statusIcons[$application->status] ?? 'circle' }} me-1"></i>
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('applications.show', $application->id) }}"
                                                           class="btn btn-outline-primary"
                                                           title="View Application Details"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        @if($application->status === 'pending')
                                                            <form action="{{ route('applications.cancel', $application) }}"
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                        class="btn btn-outline-danger"
                                                                        title="Cancel Application"
                                                                        data-bs-toggle="tooltip"
                                                                        onclick="return confirm('Are you sure you want to cancel this application?')">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-briefcase fa-4x text-muted mb-4"></i>
                                    <h3 class="text-muted mb-3">No Applications Yet</h3>
                                    <p class="text-muted mb-4">You haven't applied to any jobs yet. Start browsing available opportunities to apply.</p>

                                    <div class="row justify-content-center">
                                        <div class="col-md-8 col-lg-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body py-4">
                                                    <h5 class="card-title text-primary mb-3">
                                                        <i class="fas fa-search me-2"></i>
                                                        Find Your Next Opportunity
                                                    </h5>
                                                    <p class="card-text text-muted mb-4">
                                                        Browse available job postings and apply to positions that match your skills and interests.
                                                    </p>
                                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                                        <a href="{{ route('job-postings.public') }}" class="btn btn-primary btn-lg px-4">
                                                            <i class="fas fa-search me-2"></i> Browse Jobs
                                                        </a>
                                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">
                                                            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .border-bottom {
        border-bottom: 1px solid #e9ecef !important;
    }

    .btn-group-sm > .btn {
        border-radius: 6px;
        margin: 0 2px;
    }
</style>
@endsection

@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endsection
