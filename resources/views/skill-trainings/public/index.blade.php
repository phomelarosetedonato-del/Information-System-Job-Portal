<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Skill Trainings - PWD System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .training-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            height: 100%;
        }
        .training-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .training-header {
            background: linear-gradient( #0056b3 100%);
            color: white;
            padding: 20px;
            position: relative;
        }
        .training-badge {
            position: absolute;
            top: 15px;
            right: 15px;
        }
        .status-badge {
            font-size: 0.75em;
            padding: 4px 8px;
        }
        .enrollment-count {
            background: rgba(255,255,255,0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
        }
        .filter-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        .stats-card {
            background: linear-gradient(135deg, #ffffff 0%, #0056b3 100%);
            color: rgb(78, 78, 78);
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
        }
        .stats-card.warning {
            background: linear-gradient(135deg, #ffffff 0%, #0056b3 100%);
        }
        .stats-card.info {
            background: linear-gradient(135deg, #ffffff 0%, #0056b3 100%);
        }
        .stats-card.success {
            background: linear-gradient(135deg, #ffffff 0%, #0056b3 100%);
        }
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        .progress {
            height: 8px;
        }
        .training-details {
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    @extends('layouts.app')

    @section('content')
    <div class="container-fluid py-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-2">Available Skill Trainings</h1>
                        <p class="text-muted">Enhance your skills with our specialized training programs</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('enrollments.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-bookmark me-2"></i>My Enrollments
                        </a>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-graduation-cap fa-2x mb-3"></i>
                    </div>
                    <h3>{{ $totalTrainings ?? 0 }}</h3>
                    <p class="mb-0">Total Trainings</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card warning">
                    <div class="stats-icon">
                        <i class="fas fa-users fa-2x mb-3"></i>
                    </div>
                    <h3>{{ $activeTrainings ?? 0 }}</h3>
                    <p class="mb-0">Active Now</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card info">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-check fa-2x mb-3"></i>
                    </div>
                    <h3>{{ $upcomingTrainings ?? 0 }}</h3>
                    <p class="mb-0">Upcoming</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card success">
                    <div class="stats-icon">
                        <i class="fas fa-user-check fa-2x mb-3"></i>
                    </div>
                    <h3>{{ $userEnrollments ?? 0 }}</h3>
                    <p class="mb-0">My Enrollments</p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-section">
                    <form method="GET" action="{{ route('skill-trainings.public.index') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Training Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active (Ongoing)</option>
                                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location"
                                       value="{{ request('location') }}" placeholder="Enter location...">
                            </div>
                            <div class="col-md-3">
                                <label for="trainer" class="form-label">Trainer</label>
                                <input type="text" class="form-control" id="trainer" name="trainer"
                                       value="{{ request('trainer') }}" placeholder="Search trainer...">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid gap-2 d-md-flex">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
                                    @if(request()->anyFilled(['status', 'location', 'trainer']))
                                        <a href="{{ route('skill-trainings.public.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>Clear
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Training Grid -->
        <div class="row">
            @forelse($skillTrainings as $training)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card training-card h-100">
                        <div class="training-header position-relative">
                            <span class="training-badge">
                                @if($training->start_date->isFuture())
                                    <span class="badge bg-warning status-badge">Upcoming</span>
                                @elseif($training->end_date->isPast())
                                    <span class="badge bg-secondary status-badge">Completed</span>
                                @else
                                    <span class="badge bg-success status-badge">Ongoing</span>
                                @endif
                            </span>
                            <h5 class="card-title mb-2">{{ $training->title }}</h5>
                            <p class="card-text mb-0">
                                <i class="fas fa-user-tie me-2"></i>{{ $training->trainer }}
                            </p>
                        </div>

                        <div class="card-body d-flex flex-column">
                            <p class="card-text text-muted mb-3 flex-grow-1">
                                {{ Str::limit($training->description, 120) }}
                            </p>

                            <div class="training-details mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <small>{{ $training->location }}</small>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <small>
                                        {{ $training->start_date->format('M d, Y') }} -
                                        {{ $training->end_date->format('M d, Y') }}
                                    </small>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-users text-primary me-2"></i>
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
                            <div class="progress mb-3">
                                <div class="progress-bar {{ $progressColor }}"
                                     style="width: {{ $enrollmentPercentage }}%"
                                     title="{{ number_format($enrollmentPercentage, 1) }}% filled">
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2 mt-auto">
                                @php
                                    $userEnrollment = $training->enrollments->where('user_id', auth()->id())->first();
                                    $isFull = $enrollmentCount >= $training->max_participants;
                                    $canEnroll = !$userEnrollment && !$isFull && $training->is_active && $training->start_date->isFuture();
                                @endphp

                                @if($userEnrollment)
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check me-2"></i>Already Enrolled
                                    </button>
                                    <small class="text-muted text-center">
                                        Status:
                                        <span class="badge bg-{{ $userEnrollment->status == 'approved' ? 'success' : ($userEnrollment->status == 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($userEnrollment->status) }}
                                        </span>
                                    </small>
                                @elseif($isFull)
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-times me-2"></i>Training Full
                                    </button>
                                @elseif(!$training->is_active)
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-pause me-2"></i>Not Available
                                    </button>
                                @elseif($training->start_date->isPast())
                                    <button class="btn btn-secondary" disabled>
                                        <i class="fas fa-clock me-2"></i>Training Started
                                    </button>
                                @else
                                    <form action="{{ route('enrollments.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="skill_training_id" value="{{ $training->id }}">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-bookmark me-2"></i>Enroll Now
                                        </button>
                                    </form>
                                @endif

                                <a href="{{ route('skill-trainings.public.show', $training->id) }}"
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-info-circle me-2"></i>View Details
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
                            <a href="{{ route('skill-trainings.public.index') }}" class="btn btn-primary mt-2">
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
                <div class="d-flex justify-content-center">
                    {{ $skillTrainings->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Add confirmation for enrollment
        document.addEventListener('DOMContentLoaded', function() {
            const enrollmentForms = document.querySelectorAll('form[action="{{ route("enrollments.store") }}"]');

            enrollmentForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const trainingTitle = this.closest('.training-card').querySelector('.card-title').textContent;
                    if (!confirm(`Are you sure you want to enroll in "${trainingTitle}"?`)) {
                        e.preventDefault();
                    }
                });
            });

            // Auto-hide alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    @endsection
</body>
</html>
