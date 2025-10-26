@extends('layouts.app')

@section('title', 'Enrollment Details - PWD System')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-check"></i>
                        Enrollment Details
                    </h4>
                    <div>
                        {!! $enrollment->status_badge !!}
                    </div>
                </div>
                <div class="card-body">
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
                        <div class="col-md-6">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-chalkboard-teacher"></i>
                                Training Information
                            </h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%">Program:</td>
                                            <td>{{ $enrollment->skillTraining->title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Trainer:</td>
                                            <td>{{ $enrollment->skillTraining->trainer }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Location:</td>
                                            <td>{{ $enrollment->skillTraining->location }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Schedule:</td>
                                            <td>
                                                {{ $enrollment->skillTraining->start_date->format('F d, Y') }} to
                                                {{ $enrollment->skillTraining->end_date->format('F d, Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Duration:</td>
                                            <td>{{ $enrollment->skillTraining->duration_days }} days</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Capacity:</td>
                                            <td>
                                                {{ $enrollment->skillTraining->enrollments()->whereIn('status', ['pending', 'approved'])->count() }} /
                                                {{ $enrollment->skillTraining->max_participants }} participants
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="text-success mb-3">
                                <i class="fas fa-user-check"></i>
                                Enrollment Information
                            </h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" style="width: 40%">Enrolled Date:</td>
                                            <td>{{ $enrollment->created_at->format('F d, Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Last Updated:</td>
                                            <td>{{ $enrollment->updated_at->format('F d, Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>{!! $enrollment->status_badge !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Enrollment ID:</td>
                                            <td>#{{ $enrollment->id }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Training Description -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-success">
                                <i class="fas fa-align-left"></i>
                                Training Description
                            </h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ $enrollment->skillTraining->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Learning Objectives -->
                    @if($enrollment->skillTraining->objectives)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-success">
                                <i class="fas fa-bullseye"></i>
                                Learning Objectives
                            </h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ $enrollment->skillTraining->objectives }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Admin Notes -->
                    @if($enrollment->notes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-success">
                                <i class="fas fa-sticky-note"></i>
                                Admin Notes
                            </h5>
                            <div class="card border-info">
                                <div class="card-body">
                                    <p class="mb-0">{{ $enrollment->notes }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('enrollments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Enrollments
                            </a>
                            <a href="{{ route('skill-trainings.index') }}" class="btn btn-success">
                                <i class="fas fa-search"></i> Browse More Trainings
                            </a>
                        </div>
                        <div class="col-md-6 text-end">
                            @if(auth()->user()->role === 'admin')
                                <!-- Admin Status Update Form -->
                                <form action="{{ route('enrollments.updateStatus', $enrollment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <div class="input-group">
                                        <select name="status" class="form-select" style="width: auto;">
                                            <option value="pending" {{ $enrollment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="approved" {{ $enrollment->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected" {{ $enrollment->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            <option value="completed" {{ $enrollment->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $enrollment->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary">Update Status</button>
                                    </div>
                                </form>
                            @elseif($enrollment->can_cancel)
                                <!-- User Cancel Button -->
                                <form action="{{ route('enrollments.cancel', $enrollment) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to cancel this enrollment?')">
                                        <i class="fas fa-times"></i> Cancel Enrollment
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Training Status Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i>
                        Training Status
                    </h5>
                </div>
                <div class="card-body">
                    @if($enrollment->skillTraining->is_upcoming)
                        <div class="alert alert-warning">
                            <i class="fas fa-clock"></i>
                            <strong>Upcoming Training</strong><br>
                            Starts {{ $enrollment->skillTraining->start_date->diffForHumans() }}
                        </div>
                    @elseif($enrollment->skillTraining->is_ongoing)
                        <div class="alert alert-success">
                            <i class="fas fa-play-circle"></i>
                            <strong>Training in Progress</strong><br>
                            Ends {{ $enrollment->skillTraining->end_date->diffForHumans() }}
                        </div>
                    @else
                        <div class="alert alert-secondary">
                            <i class="fas fa-check-circle"></i>
                            <strong>Training Completed</strong><br>
                            Ended {{ $enrollment->skillTraining->end_date->diffForHumans() }}
                        </div>
                    @endif

                    <!-- Training Progress -->
                    <div class="mt-3">
                        <strong>Training Progress:</strong>
                        <div class="progress mt-2" style="height: 20px;">
                            @php
                                $totalDays = $enrollment->skillTraining->duration_days;
                                if($enrollment->skillTraining->is_upcoming) {
                                    $progress = 0;
                                } elseif($enrollment->skillTraining->is_ongoing) {
                                    $daysPassed = $enrollment->skillTraining->start_date->diffInDays(now());
                                    $progress = min(100, ($daysPassed / $totalDays) * 100);
                                } else {
                                    $progress = 100;
                                }
                            @endphp
                            <div class="progress-bar
                                @if($progress == 0) bg-secondary
                                @elseif($progress < 100) bg-warning
                                @else bg-success @endif"
                                role="progressbar"
                                style="width: {{ $progress }}%"
                                aria-valuenow="{{ $progress }}"
                                aria-valuemin="0"
                                aria-valuemax="100">
                                {{ round($progress) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card mt-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('skill-trainings.show', $enrollment->skillTraining) }}"
                       class="btn btn-outline-primary btn-block mb-2 w-100">
                        <i class="fas fa-external-link-alt"></i> View Training Details
                    </a>

                    @if($enrollment->is_approved && $enrollment->has_training_started)
                        <button class="btn btn-outline-success btn-block mb-2 w-100">
                            <i class="fas fa-download"></i> Download Materials
                        </button>
                    @endif

                    <a href="{{ route('notifications.index') }}"
                       class="btn btn-outline-info btn-block mb-2 w-100">
                        <i class="fas fa-bell"></i> View Notifications
                    </a>

                    @if($enrollment->is_approved && $enrollment->has_training_ended)
                        <button class="btn btn-outline-warning btn-block w-100">
                            <i class="fas fa-certificate"></i> Download Certificate
                        </button>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mt-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-phone"></i>
                        Need Help?
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <i class="fas fa-envelope"></i>
                        <strong>Email:</strong> support@pwd.alaminos.city
                    </p>
                    <p class="mb-2">
                        <i class="fas fa-phone"></i>
                        <strong>Phone:</strong> (075) 123-4567
                    </p>
                    <p class="mb-0">
                        <i class="fas fa-clock"></i>
                        <strong>Hours:</strong> Mon-Fri, 8:00 AM - 5:00 PM
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
