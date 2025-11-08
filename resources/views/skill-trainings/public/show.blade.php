@extends('layouts.app')

@section('title', $skillTraining->title . ' - PWD System')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-chalkboard-teacher"></i>
                        {{ $skillTraining->title }}
                    </h4>
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
                        <div class="col-md-8">
                            <!-- Training Details -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-info-circle"></i>
                                    Training Details
                                </h5>
                                <div class="card">
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td width="30%" class="fw-bold">Trainer:</td>
                                                <td>{{ $skillTraining->trainer }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Schedule:</td>
                                                <td>
                                                    {{ $skillTraining->start_date->format('F d, Y') }} -
                                                    {{ $skillTraining->end_date->format('F d, Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Duration:</td>
                                                <td>
                                                    @php
                                                        $duration = $skillTraining->start_date->diffInDays($skillTraining->end_date) + 1;
                                                    @endphp
                                                    {{ $duration }} day{{ $duration > 1 ? 's' : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Location:</td>
                                                <td>{{ $skillTraining->location }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Available Slots:</td>
                                                <td>
                                                    @php
                                                        $enrollmentCount = $skillTraining->enrollments_count ?? $skillTraining->enrollments->count();
                                                        $availableSlots = $skillTraining->max_participants - $enrollmentCount;
                                                        $isFull = $availableSlots <= 0;
                                                    @endphp
                                                    {{ $availableSlots }} / {{ $skillTraining->max_participants }}
                                                    @if($isFull)
                                                        <span class="badge bg-danger ms-2">Full</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Status:</td>
                                                <td>
                                                    @if($skillTraining->start_date->isFuture())
                                                        <span class="badge bg-warning">Upcoming</span>
                                                    @elseif($skillTraining->end_date->isPast())
                                                        <span class="badge bg-secondary">Completed</span>
                                                    @else
                                                        <span class="badge bg-success">Ongoing</span>
                                                    @endif
                                                    @if(!$skillTraining->is_active)
                                                        <span class="badge bg-danger ms-1">Inactive</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Training Description -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-align-left"></i>
                                    Training Description
                                </h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="training-description">
                                            {!! nl2br(e($skillTraining->description)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Learning Objectives -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-bullseye"></i>
                                    Learning Objectives
                                </h5>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="training-objectives">
                                            {!! nl2br(e($skillTraining->objectives)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Enrollment Progress -->
                            <div class="mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-chart-bar"></i>
                                    Enrollment Status
                                </h5>
                                <div class="card">
                                    <div class="card-body">
                                        @php
                                            $enrollmentPercentage = min(100, ($enrollmentCount / $skillTraining->max_participants) * 100);
                                            $progressColor = $enrollmentPercentage >= 90 ? 'bg-danger' : ($enrollmentPercentage >= 75 ? 'bg-warning' : 'bg-success');
                                        @endphp
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Enrollment Progress</span>
                                            <span>{{ number_format($enrollmentPercentage, 1) }}%</span>
                                        </div>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar {{ $progressColor }} progress-bar-striped"
                                                 style="width: {{ $enrollmentPercentage }}%"
                                                 role="progressbar"
                                                 aria-valuenow="{{ $enrollmentPercentage }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                {{ $enrollmentCount }}/{{ $skillTraining->max_participants }}
                                            </div>
                                        </div>
                                        <small class="text-muted mt-2 d-block">
                                            @if($isFull)
                                                <i class="fas fa-exclamation-triangle text-danger"></i>
                                                This training is currently full. You can still view details but cannot enroll.
                                            @elseif($enrollmentPercentage >= 75)
                                                <i class="fas fa-info-circle text-warning"></i>
                                                This training is filling up quickly. Only {{ $availableSlots }} spot{{ $availableSlots > 1 ? 's' : '' }} left!
                                            @else
                                                <i class="fas fa-info-circle text-success"></i>
                                                {{ $availableSlots }} spot{{ $availableSlots > 1 ? 's' : '' }} available for enrollment.
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Enrollment Box -->
                            <div class="card sticky-top" style="top: 20px;">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-plus"></i>
                                        Enroll in this Training
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @auth
                                        @if(auth()->user()->role === 'pwd')
                                            @if($userEnrollment)
                                                <div class="alert alert-success">
                                                    <i class="fas fa-check-circle"></i>
                                                    <strong>Enrollment Submitted</strong>
                                                    <p class="mb-0 mt-2">You have already enrolled in this training.</p>
                                                    <div class="mt-2">
                                                        <strong>Your Status:</strong>
                                                        <span class="badge bg-{{ $userEnrollment->status == 'approved' ? 'success' : ($userEnrollment->status == 'pending' ? 'warning' : 'secondary') }}">
                                                            {{ ucfirst($userEnrollment->status) }}
                                                        </span>
                                                    </div>
                                                    @if($userEnrollment->status == 'pending')
                                                        <p class="mb-0 mt-2">We will review your enrollment and contact you soon.</p>
                                                    @endif
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <a href="{{ route('enrollments.index') }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-list"></i> View My Enrollments
                                                    </a>
                                                    @if($userEnrollment->status == 'pending')
                                                        <form action="{{ route('enrollments.destroy', $userEnrollment) }}" method="POST" class="d-grid">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger"
                                                                    onclick="return confirm('Are you sure you want to withdraw your enrollment?')">
                                                                <i class="fas fa-times"></i> Withdraw Enrollment
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                @if($skillTraining->end_date->isPast())
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        <strong>Training Completed</strong>
                                                        <p class="mb-0 mt-2">This training has already ended. Enrollment is closed.</p>
                                                    </div>
                                                @elseif(!$skillTraining->is_active)
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        <strong>Training Inactive</strong>
                                                        <p class="mb-0 mt-2">This training is no longer active.</p>
                                                    </div>
                                                @elseif($isFull)
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        <strong>Training Full</strong>
                                                        <p class="mb-0 mt-2">This training has reached maximum participants.</p>
                                                    </div>
                                                @elseif($skillTraining->start_date->isPast())
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        <strong>Training Started</strong>
                                                        <p class="mb-0 mt-2">This training has already started. Late enrollment may not be available.</p>
                                                    </div>
                                                @else
                                                    <form action="{{ route('enrollments.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="skill_training_id" value="{{ $skillTraining->id }}">

                                                        <div class="alert alert-info">
                                                            <i class="fas fa-info-circle"></i>
                                                            <strong>Enrollment Information</strong>
                                                            <ul class="mb-0 mt-2 ps-3">
                                                                <li>Training: <strong>{{ $skillTraining->title }}</strong></li>
                                                                <li>Date: {{ $skillTraining->start_date->format('M d, Y') }} - {{ $skillTraining->end_date->format('M d, Y') }}</li>
                                                                <li>Location: {{ $skillTraining->location }}</li>
                                                                <li>Available spots: {{ $availableSlots }}</li>
                                                            </ul>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="notes" class="form-label">
                                                                <strong>Additional Notes (Optional)</strong>
                                                            </label>
                                                            <textarea class="form-control" id="notes" name="notes"
                                                                      rows="3" placeholder="Any special requirements, accessibility needs, or questions...">{{ old('notes') }}</textarea>
                                                            @error('notes')
                                                                <div class="text-danger small">{{ $message }}</div>
                                                            @enderror
                                                        </div>

                                                        <div class="d-grid">
                                                            <button type="submit" class="btn btn-success btn-lg"
                                                                    onclick="return confirm('Confirm enrollment in {{ $skillTraining->title }}?')">
                                                                <i class="fas fa-user-plus me-2"></i> Confirm Enrollment
                                                            </button>
                                                        </div>

                                                        <div class="mt-2 text-center">
                                                            <small class="text-muted">
                                                                By enrolling, you agree to attend the training sessions as scheduled.
                                                            </small>
                                                        </div>
                                                    </form>
                                                @endif
                                            @endif
                                        @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i>
                                                <strong>Admin Account</strong>
                                                <p class="mb-0 mt-2">Training enrollment feature is available for PWD users only.</p>
                                            </div>
                                            <div class="d-grid">
                                                <a href="{{ route('skill-trainings.public.index') }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-chalkboard-teacher"></i> Browse Trainings
                                                </a>
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <strong>Login Required</strong>
                                            <p class="mb-0 mt-2">You need to be logged in as a PWD user to enroll in this training.</p>
                                        </div>
                                        <div class="d-grid gap-2">
                                            <a href="{{ route('login') }}" class="btn btn-primary">
                                                <i class="fas fa-sign-in-alt"></i> Login to Enroll
                                            </a>
                                            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                                                <i class="fas fa-user-plus"></i> Register as PWD
                                            </a>
                                        </div>
                                    @endauth
                                </div>
                            </div>

                            <!-- Quick Actions -->
                            <div class="card mt-3">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-bolt"></i>
                                        Quick Actions
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('skill-trainings.public.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-arrow-left me-2"></i> Back to Trainings
                                        </a>
                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-success">
                                            <i class="fas fa-home me-2"></i> Dashboard
                                        </a>
                                        <a href="{{ route('job-postings.public') }}" class="btn btn-outline-warning">
                                            <i class="fas fa-briefcase me-2"></i> Browse Jobs
                                        </a>
                                        <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-file-upload me-2"></i> Upload Documents
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Training Information -->
                            <div class="card mt-3">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle"></i>
                                        Training Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">
                                        <i class="fas fa-chalkboard-teacher text-muted"></i>
                                        <span class="text-muted">Trainer:</span> {{ $skillTraining->trainer }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-calendar-day text-muted"></i>
                                        <span class="text-muted">Schedule:</span>
                                        {{ $skillTraining->start_date->format('M d, Y') }} - {{ $skillTraining->end_date->format('M d, Y') }}
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-clock text-muted"></i>
                                        <span class="text-muted">Duration:</span>
                                        @php
                                            $duration = $skillTraining->start_date->diffInDays($skillTraining->end_date) + 1;
                                        @endphp
                                        {{ $duration }} day{{ $duration > 1 ? 's' : '' }}
                                    </p>
                                    <p class="mb-0">
                                        <i class="fas fa-map-marker-alt text-muted"></i>
                                        <span class="text-muted">Location:</span> {{ $skillTraining->location }}
                                    </p>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="card mt-3">
                                <div class="card-header bg-warning text-dark">
                                    <h5 class="mb-0">
                                        <i class="fas fa-headset"></i>
                                        Need Help?
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-2">
                                        <i class="fas fa-envelope text-muted"></i>
                                        <span class="text-muted">Email:</span> support@pwd-system.com
                                    </p>
                                    <p class="mb-2">
                                        <i class="fas fa-phone text-muted"></i>
                                        <span class="text-muted">Phone:</span> (123) 456-7890
                                    </p>
                                    <p class="mb-0">
                                        <i class="fas fa-clock text-muted"></i>
                                        <span class="text-muted">Hours:</span> Mon-Fri, 8AM-5PM
                                    </p>
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

@push('styles')
<style>
.training-description, .training-objectives {
    line-height: 1.6;
    white-space: pre-wrap;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: 1px solid #e3e6f0;
}

.card-header {
    border-bottom: 1px solid #e3e6f0;
}

.sticky-top {
    position: sticky;
    z-index: 10;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.alert {
    border: none;
    border-left: 4px solid;
}

.alert-success {
    border-left-color: #28a745;
}

.alert-danger {
    border-left-color: #dc3545;
}

.alert-warning {
    border-left-color: #ffc107;
}

.alert-info {
    border-left-color: #17a2b8;
}

.progress-bar {
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>
@endpush
