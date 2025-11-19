@extends('layouts.app')

@section('title', 'Available Skill Trainings - PWD System')

@section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white py-3" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%) !important; color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0">
                            <i class="fas fa-graduation-cap me-2"></i>Available Skill Trainings
                        </h4>
                    </div>
                    <div class="d-flex gap-2">
                        @auth
                            @if(auth()->user()->role === 'pwd')
                                <a href="{{ route('enrollments.index') }}" class="btn btn-light btn-sm">
                                    <i class="fas fa-bookmark me-1"></i>My Enrollments
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">Enhance your skills with our specialized training programs designed for PWD users</p>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('skill-trainings.public.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label for="status" class="form-label small fw-semibold">
                                <i class="fas fa-filter me-1"></i>Training Status
                            </label>
                            <select class="form-select form-select-sm" id="status" name="status">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (Ongoing)</option>
                                <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="location" class="form-label small fw-semibold">
                                <i class="fas fa-map-marker-alt me-1"></i>Location
                            </label>
                            <input type="text" class="form-control form-control-sm" id="location" name="location"
                                   value="{{ request('location') }}" placeholder="Enter location...">
                        </div>
                        <div class="col-md-3">
                            <label for="trainer" class="form-label small fw-semibold">
                                <i class="fas fa-user-tie me-1"></i>Trainer
                            </label>
                            <input type="text" class="form-control form-control-sm" id="trainer" name="trainer"
                                   value="{{ request('trainer') }}" placeholder="Search trainer...">
                        </div>
                        <div class="col-md-3">
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-sm flex-fill" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); color: white; border: none;">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                                @if(request()->anyFilled(['status', 'location', 'trainer']))
                                    <a href="{{ route('skill-trainings.public.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Training Grid -->
        <div class="row">
            @forelse($skillTrainings as $training)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 border-0 bg-white" style="box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.25) !important;">
                        <div class="card-header bg-white border-bottom">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title mb-0">{{ $training->title }}</h5>
                                @if($training->start_date->isFuture())
                                    <span class="badge bg-warning text-dark">Upcoming</span>
                                @elseif($training->end_date->isPast())
                                    <span class="badge bg-secondary">Completed</span>
                                @else
                                    <span class="badge bg-success">Ongoing</span>
                                @endif
                            </div>
                            <p class="text-muted mb-0 mt-2">
                                <i class="fas fa-user-tie me-1"></i>{{ $training->trainer }}
                            </p>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <p class="card-text text-muted mb-3 flex-grow-1">
                                {{ Str::limit($training->description, 120) }}
                            </p>

                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                    <small>{{ $training->location }}</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-alt text-muted me-2"></i>
                                    <small>
                                        {{ $training->start_date->format('M d, Y') }} -
                                        {{ $training->end_date->format('M d, Y') }}
                                    </small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-users text-muted me-2"></i>
                                    <small>
                                        {{ $training->enrollments_count ?? $training->enrollments->count() }} /
                                        {{ $training->max_participants }} enrolled
                                    </small>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            @php
                                $enrollmentCount = $training->enrollments_count ?? $training->enrollments->count();
                                $enrollmentPercentage = min(100, ($enrollmentCount / $training->max_participants) * 100);
                                $progressColor = $enrollmentPercentage >= 90 ? 'bg-danger' : ($enrollmentPercentage >= 75 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress mb-3" style="height: 6px;">
                                <div class="progress-bar {{ $progressColor }}"
                                     style="width: {{ $enrollmentPercentage }}%"
                                     title="{{ number_format($enrollmentPercentage, 1) }}% filled">
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2 mt-auto">
                                @auth
                                    @if(auth()->user()->role === 'pwd')
                                        @php
                                            $userEnrollment = $training->enrollments->where('user_id', auth()->id())->first();
                                            $isFull = $enrollmentCount >= $training->max_participants;
                                        @endphp

                                        @if($userEnrollment)
                                            <button class="btn btn-success btn-sm" disabled>
                                                <i class="fas fa-check me-1"></i>Already Enrolled
                                            </button>
                                            <small class="text-muted text-center">
                                                Status:
                                                <span class="badge bg-{{ $userEnrollment->status == 'approved' ? 'success' : ($userEnrollment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($userEnrollment->status) }}
                                                </span>
                                            </small>
                                        @elseif($isFull)
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-times me-1"></i>Training Full
                                            </button>
                                        @elseif(!$training->is_active)
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-pause me-1"></i>Not Available
                                            </button>
                                        @elseif($training->start_date->isPast())
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="fas fa-clock me-1"></i>Training Started
                                            </button>
                                        @else
                                            <form action="{{ route('enrollments.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="skill_training_id" value="{{ $training->id }}">
                                                <button type="submit" class="btn btn-sm w-100" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); color: white; border: none;">
                                                    <i class="fas fa-user-plus me-1"></i>Enroll Now
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            <i class="fas fa-info-circle me-1"></i>PWD Users Only
                                        </button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm" style="border-color: #2E8B57; color: #2E8B57;">
                                        <i class="fas fa-sign-in-alt me-1"></i>Login to Enroll
                                    </a>
                                @endauth

                                <a href="{{ route('skill-trainings.public.show', $training->id) }}"
                                   class="btn btn-outline-success btn-sm" style="border-color: #2E8B57; color: #2E8B57;">
                                    <i class="fas fa-eye me-1"></i>View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-graduation-cap"></i>
                        <h4>No Trainings Available</h4>
                        <p class="text-muted">
                            @if(request()->anyFilled(['status', 'location', 'trainer']))
                                No skill trainings match your current filters.
                            @else
                                There are currently no skill trainings available. Please check back later.
                            @endif
                        </p>
                        @if(request()->anyFilled(['status', 'location', 'trainer']))
                            <a href="{{ route('skill-trainings.public.index') }}" class="btn mt-2" style="background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%); color: white; border: none;">
                                <i class="fas fa-refresh me-2"></i>Clear Filters
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($skillTrainings->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center pagination-compact-wrapper">
                    {{ $skillTrainings->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@section('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.1);
    }

    .progress {
        border-radius: 10px;
    }

    /* Compact Pagination Styles */
    .pagination-compact-wrapper .pagination {
        margin-bottom: 0;
        gap: 4px;
    }

    .pagination-compact-wrapper .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        line-height: 1.5;
        border-radius: 4px;
        margin: 0 2px;
        min-width: 32px;
        text-align: center;
    }

    .pagination-compact-wrapper .page-item:first-child .page-link,
    .pagination-compact-wrapper .page-item:last-child .page-link {
        border-radius: 4px;
    }

    .pagination-compact-wrapper .page-item.active .page-link {
        background-color: #007bff;
        border-color: #007bff;
        color: white;
        font-weight: 600;
    }

    .pagination-compact-wrapper .page-item.disabled .page-link {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .pagination-compact-wrapper .page-link:hover {
        background-color: #f8f9fa;
        border-color: #007bff;
        color: #007bff;
    }

    /* Make pagination responsive */
    @media (max-width: 768px) {
        .pagination-compact-wrapper .page-link {
            padding: 0.2rem 0.4rem;
            font-size: 0.75rem;
            min-width: 28px;
        }

        .pagination-compact-wrapper .pagination {
            gap: 2px;
        }

        .pagination-compact-wrapper .page-link {
            margin: 0 1px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add confirmation for enrollment
        const enrollmentForms = document.querySelectorAll('form[action*="enrollments"]');

        enrollmentForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const trainingTitle = this.closest('.card').querySelector('.card-title').textContent;
                if (!confirm(`Are you sure you want to enroll in "${trainingTitle}"?`)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endsection
