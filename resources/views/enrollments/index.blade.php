@extends('layouts.app')

@section('title', 'My Training Enrollments - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-success text-white py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-1">
                        <i class="fas fa-calendar-check me-2"></i>
                        My Training Enrollments
                    </h1>
                    <p class="mb-0 opacity-75">Manage your training program enrollments and track your progress</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('skill-trainings.public.index') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-search me-2"></i> Browse Trainings
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

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Quick Stats -->
        @if($enrollments->count() > 0)
        <div class="row mb-5">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-primary h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-primary mb-3">
                            <i class="fas fa-clock fa-3x"></i>
                        </div>
                        <h3 class="h2 text-primary">{{ $enrollments->where('status', 'pending')->count() }}</h3>
                        <h5 class="card-title text-muted">Pending</h5>
                        <p class="text-muted small">Awaiting approval</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-success h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-success mb-3">
                            <i class="fas fa-check-circle fa-3x"></i>
                        </div>
                        <h3 class="h2 text-success">{{ $enrollments->where('status', 'approved')->count() }}</h3>
                        <h5 class="card-title text-muted">Approved</h5>
                        <p class="text-muted small">Confirmed enrollments</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-info h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-info mb-3">
                            <i class="fas fa-graduation-cap fa-3x"></i>
                        </div>
                        <h3 class="h2 text-info">{{ $enrollments->where('status', 'completed')->count() }}</h3>
                        <h5 class="card-title text-muted">Completed</h5>
                        <p class="text-muted small">Finished trainings</p>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-warning h-100 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-warning mb-3">
                            <i class="fas fa-list-alt fa-3x"></i>
                        </div>
                        <h3 class="h2 text-warning">{{ $enrollments->total() }}</h3>
                        <h5 class="card-title text-muted">Total</h5>
                        <p class="text-muted small">All enrollments</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Enrollments Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-dark">
                                <i class="fas fa-list me-2 text-success"></i>
                                Enrollment History
                            </h4>
                            <span class="badge bg-light text-dark fs-6 border">
                                {{ $enrollments->total() }} {{ Str::plural('enrollment', $enrollments->total()) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($enrollments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 ps-4">Training Program</th>
                                            <th class="border-0">Schedule</th>
                                            <th class="border-0">Location</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Enrollment Date</th>
                                            <th class="border-0 pe-4 text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($enrollments as $enrollment)
                                            <tr class="border-bottom">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 text-dark">{{ $enrollment->skillTraining->title }}</h6>
                                                            <div class="text-muted small">
                                                                <div class="mb-1">
                                                                    <i class="fas fa-chalkboard-teacher me-1"></i>
                                                                    {{ $enrollment->skillTraining->trainer ?? 'TBA' }}
                                                                </div>
                                                                <div>
                                                                    <i class="fas fa-users me-1"></i>
                                                                    {{ $enrollment->skillTraining->enrollments->whereIn('status', ['pending', 'approved'])->count() }}/{{ $enrollment->skillTraining->max_participants }} participants
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted small">
                                                        <div class="mb-1">
                                                            <i class="fas fa-calendar-day me-1"></i>
                                                            {{ $enrollment->skillTraining->start_date->format('M j, Y') }}
                                                        </div>
                                                        <div>
                                                            to {{ $enrollment->skillTraining->end_date->format('M j, Y') }}
                                                        </div>
                                                        <div class="text-muted">
                                                            ({{ $enrollment->skillTraining->duration_days }} days)
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        {{ $enrollment->skillTraining->location ?? 'Online' }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column gap-1">
                                                        {!! $enrollment->status_badge !!}
                                                        @if($enrollment->is_approved && $enrollment->has_training_started && !$enrollment->has_training_ended)
                                                            <span class="badge bg-light text-success small">
                                                                <i class="fas fa-play-circle me-1"></i> Ongoing
                                                            </span>
                                                        @endif
                                                        @if($enrollment->is_approved && $enrollment->has_training_ended)
                                                            <span class="badge bg-light text-info small">
                                                                <i class="fas fa-check-circle me-1"></i> Completed
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted small">
                                                        <div class="mb-1">{{ $enrollment->enrolled_date }}</div>
                                                        <div>{{ $enrollment->enrolled_ago }}</div>
                                                    </div>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('enrollments.show', $enrollment) }}"
                                                           class="btn btn-outline-primary"
                                                           title="View Details"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        @if($enrollment->can_cancel)
                                                            <form action="{{ route('enrollments.cancel', $enrollment) }}"
                                                                  method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit" class="btn btn-outline-danger"
                                                                        title="Cancel Enrollment"
                                                                        data-bs-toggle="tooltip"
                                                                        onclick="return confirm('Are you sure you want to cancel this enrollment?')">
                                                                    <i class="fas fa-times"></i>
                                                                </button>
                                                            </form>
                                                        @endif

                                                        @if($enrollment->is_approved && $enrollment->has_training_started)
                                                            <button class="btn btn-outline-success"
                                                                    title="Training Materials"
                                                                    data-bs-toggle="tooltip">
                                                                <i class="fas fa-download"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="card-footer bg-white border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Showing {{ $enrollments->firstItem() }} to {{ $enrollments->lastItem() }}
                                        of {{ $enrollments->total() }} entries
                                    </div>
                                    <nav>
                                        {{ $enrollments->onEachSide(1)->links() }}
                                    </nav>
                                </div>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-calendar-times fa-4x text-muted mb-4"></i>
                                    <h3 class="text-muted mb-3">No Enrollments Found</h3>
                                    <p class="text-muted mb-4">You haven't enrolled in any training programs yet.</p>

                                    <div class="row justify-content-center">
                                        <div class="col-md-8 col-lg-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body py-4">
                                                    <h5 class="card-title text-success mb-3">
                                                        <i class="fas fa-graduation-cap me-2"></i>
                                                        Start Your Learning Journey
                                                    </h5>
                                                    <p class="card-text text-muted mb-4">
                                                        Browse our available training programs to develop new skills and enhance your employment opportunities.
                                                    </p>
                                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                                        <a href="{{ route('skill-trainings.public.index') }}" class="btn btn-success btn-lg px-4">
                                                            <i class="fas fa-search me-2"></i> Browse Training Programs
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
    .card {
        border-radius: 12px;
    }

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
@include('partials.accessibility-widget')
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
