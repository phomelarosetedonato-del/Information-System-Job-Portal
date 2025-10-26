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
        }
        .training-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .training-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
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
                    <h3>{{ $totalTrainings }}</h3>
                    <p class="mb-0">Total Trainings</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="stats-icon">
                        <i class="fas fa-users fa-2x mb-3"></i>
                    </div>
                    <h3>{{ $activeTrainings }}</h3>
                    <p class="mb-0">Active Now</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="stats-icon">
                        <i class="fas fa-calendar-check fa-2x mb-3"></i>
                    </div>
                    <h3>{{ $upcomingTrainings }}</h3>
                    <p class="mb-0">Upcoming</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="stats-icon">
                        <i class="fas fa-user-check fa-2x mb-3"></i>
                    </div>
                    <h3>{{ $userEnrollments }}</h3>
                    <p class="mb-0">My Enrollments</p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="filter-section">
                    <form method="GET" action="{{ route('skill-trainings.public') }}">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Training Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">All Status</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
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
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i>Search
                                    </button>
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

                        <div class="card-body">
                            <p class="card-text text-muted mb-3">
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
                                $enrollmentPercentage = min(100, (($training->enrollments_count ?? $training->enrollments->count()) / $training->max_participants) * 100);
                                $progressColor = $enrollmentPercentage >= 90 ? 'bg-danger' : ($enrollmentPercentage >= 75 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar {{ $progressColor }}"
                                     style="width: {{ $enrollmentPercentage }}%"
                                     title="{{ $enrollmentPercentage }}% filled">
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                @php
                                    $userEnrollment = $training->enrollments->where('user_id', auth()->id())->first();
                                    $isFull = ($training->enrollments_count ?? $training->enrollments->count()) >= $training->max_participants;
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

                                <a href="{{ route('skill-trainings.public-show', $training->id) }}"
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
                        <p class="text-muted">There are currently no skill trainings matching your criteria.</p>
                        @if(request()->anyFilled(['status', 'location', 'trainer']))
                            <a href="{{ route('skill-trainings.public') }}" class="btn btn-primary mt-2">
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
                    <nav aria-label="Training pagination">
                        {{ $skillTrainings->links() }}
                    </nav>
                </div>
            </div>
        @endif
    </div>
    @endsection

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-refresh progress bars and status
        function updateTrainingStatus() {
            document.querySelectorAll('.training-card').forEach(card => {
                const startDate = new Date(card.dataset.startDate);
                const endDate = new Date(card.dataset.endDate);
                const now = new Date();

                // You can add real-time status updates here if needed
            });
        }

        // Update every minute
        setInterval(updateTrainingStatus, 60000);

        // Add smooth scrolling for pagination
        document.addEventListener('DOMContentLoaded', function() {
            const paginationLinks = document.querySelectorAll('.pagination a');
            paginationLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = this.href;

                    // Smooth scroll to top before navigation
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                    // Small delay to allow scroll to complete
                    setTimeout(() => {
                        window.location.href = url;
                    }, 300);
                });
            });
        });
    </script>
</body>
</html>
