<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Training Enrollments - PWD System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">PWD System - Alaminos City</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link text-white" href="{{ route('enrollments.index') }}">Enrollments</a>
                <a class="nav-link text-white" href="{{ route('notifications.index') }}">
                    <i class="fas fa-bell"></i>
                    @if(auth()->user()->unreadNotifications->count() > 0)
                        <span class="badge badge-light">{{ auth()->user()->unreadNotifications->count() }}</span>
                    @endif
                </a>
                <a class="nav-link text-white" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-check"></i>
                            My Training Enrollments
                        </h4>
                        <a href="{{ route('skill-trainings.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-search"></i> Browse Trainings
                        </a>
                    </div>
                    <div class="card-body">
                        @if($enrollments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Training Program</th>
                                            <th>Schedule</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Enrollment Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($enrollments as $enrollment)
                                            <tr>
                                                <td>
                                                    <strong>{{ $enrollment->skillTraining->title }}</strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-chalkboard-teacher"></i>
                                                        {{ $enrollment->skillTraining->trainer }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="fas fa-users"></i>
                                                        {{ $enrollment->skillTraining->enrollments->whereIn('status', ['pending', 'approved'])->count() }}/{{ $enrollment->skillTraining->max_participants }} participants
                                                    </small>
                                                </td>
                                                <td>
                                                    <small>
                                                        <i class="fas fa-calendar-day"></i>
                                                        {{ $enrollment->skillTraining->start_date->format('M j, Y') }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        to {{ $enrollment->skillTraining->end_date->format('M j, Y') }}
                                                    </small>
                                                    <br>
                                                    <small class="text-muted">
                                                        ({{ $enrollment->skillTraining->duration_days }} days)
                                                    </small>
                                                </td>
                                                <td>
                                                    <i class="fas fa-map-marker-alt"></i>
                                                    {{ $enrollment->skillTraining->location }}
                                                </td>
                                                <td>
                                                    {!! $enrollment->status_badge !!}
                                                    @if($enrollment->is_approved && $enrollment->has_training_started && !$enrollment->has_training_ended)
                                                        <br>
                                                        <small class="text-success">
                                                            <i class="fas fa-play-circle"></i> Ongoing
                                                        </small>
                                                    @endif
                                                    @if($enrollment->is_approved && $enrollment->has_training_ended)
                                                        <br>
                                                        <small class="text-info">
                                                            <i class="fas fa-check-circle"></i> Completed
                                                        </small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $enrollment->enrolled_date }}</small>
                                                    <br>
                                                    <small class="text-muted">{{ $enrollment->enrolled_ago }}</small>
                                                </td>
                                                <td>
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
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div class="text-muted">
                                    Showing {{ $enrollments->firstItem() }} to {{ $enrollments->lastItem() }}
                                    of {{ $enrollments->total() }} entries
                                </div>
                                <nav>
                                    {{ $enrollments->links() }}
                                </nav>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h3 class="text-muted">No Enrollments Found</h3>
                                <p class="text-muted mb-4">You haven't enrolled in any training programs yet.</p>
                                <div class="row justify-content-center">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <h5 class="card-title">Get Started with Trainings</h5>
                                                <p class="card-text">Browse available training programs and enroll to develop new skills.</p>
                                                <a href="{{ route('skill-trainings.index') }}" class="btn btn-success btn-lg">
                                                    <i class="fas fa-search me-2"></i> Browse Training Programs
                                                </a>
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

        <!-- Quick Stats -->
        @if($enrollments->count() > 0)
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $enrollments->where('status', 'pending')->count() }}</h4>
                                <small>Pending</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $enrollments->where('status', 'approved')->count() }}</h4>
                                <small>Approved</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $enrollments->where('status', 'completed')->count() }}</h4>
                                <small>Completed</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-graduation-cap fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h4 class="mb-0">{{ $enrollments->total() }}</h4>
                                <small>Total Enrollments</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-list-alt fa-2x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
