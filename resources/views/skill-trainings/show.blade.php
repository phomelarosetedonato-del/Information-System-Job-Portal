@extends('layouts.app')

@section('title', $skillTraining->title . ' - PWD System')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-chalkboard-teacher"></i>
                        {{ $skillTraining->title }}
                    </h4>
                    <div>
                        {!! $skillTraining->status_badge !!}
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Training Details</h5>
                            <div class="card">
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%" class="fw-bold">Title:</td>
                                            <td>{{ $skillTraining->title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Trainer:</td>
                                            <td>{{ $skillTraining->trainer }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Location:</td>
                                            <td>{{ $skillTraining->location }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Schedule:</td>
                                            <td>{{ $skillTraining->date_range }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Duration:</td>
                                            <td>{{ $skillTraining->duration_days }} days</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Max Participants:</td>
                                            <td>{{ $skillTraining->max_participants }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Available Slots:</td>
                                            <td>
                                                {{ $skillTraining->available_slots }}
                                                @if($skillTraining->is_full)
                                                    <span class="badge bg-danger">Full</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>{!! $skillTraining->status_badge !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Created By:</td>
                                            <td>{{ $skillTraining->creator->name ?? 'Unknown' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <h5 class="text-primary mb-3">Enrollment Statistics</h5>
                            <div class="card">
                                <div class="card-body">
                                    @php
                                        $enrollments = $skillTraining->enrollments;
                                        $pending = $enrollments->where('status', 'pending')->count();
                                        $approved = $enrollments->where('status', 'approved')->count();
                                        $rejected = $enrollments->where('status', 'rejected')->count();
                                        $completed = $enrollments->where('status', 'completed')->count();
                                        $total = $enrollments->count();
                                    @endphp

                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="60%" class="fw-bold">Total Enrollments:</td>
                                            <td><span class="badge bg-primary">{{ $total }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Pending:</td>
                                            <td><span class="badge bg-warning">{{ $pending }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Approved:</td>
                                            <td><span class="badge bg-success">{{ $approved }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Rejected:</td>
                                            <td><span class="badge bg-danger">{{ $rejected }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Completed:</td>
                                            <td><span class="badge bg-info">{{ $completed }}</span></td>
                                        </tr>
                                    </table>

                                    <!-- Progress Bar -->
                                    <div class="mt-3">
                                        <strong>Capacity:</strong>
                                        <div class="progress mt-2" style="height: 20px;">
                                            @php
                                                $percentage = min(100, ($approved / $skillTraining->max_participants) * 100);
                                            @endphp
                                            <div class="progress-bar {{ $percentage >= 90 ? 'bg-danger' : ($percentage >= 75 ? 'bg-warning' : 'bg-success') }}"
                                                 role="progressbar"
                                                 style="width: {{ $percentage }}%"
                                                 aria-valuenow="{{ $percentage }}"
                                                 aria-valuemin="0"
                                                 aria-valuemax="100">
                                                {{ $approved }}/{{ $skillTraining->max_participants }} ({{ round($percentage) }}%)
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Training Description -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary">Training Description</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{!! nl2br(e($skillTraining->description)) !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Learning Objectives -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5 class="text-primary">Learning Objectives</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{!! nl2br(e($skillTraining->objectives)) !!}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('skill-trainings.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <div>
                            <a href="{{ route('skill-trainings.edit', $skillTraining) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form action="{{ route('skill-trainings.destroy', $skillTraining) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this training?')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt"></i>
                        Quick Actions
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('enrollments.index') }}?training={{ $skillTraining->id }}"
                           class="btn btn-outline-primary">
                            <i class="fas fa-list"></i> View Enrollments
                        </a>
                        <a href="{{ route('skill-trainings.public.show', $skillTraining) }}"
                           class="btn btn-outline-success" target="_blank">
                            <i class="fas fa-external-link-alt"></i> View Public Page
                        </a>
                        <a href="{{ route('skill-trainings.create') }}" class="btn btn-outline-warning">
                            <i class="fas fa-copy"></i> Duplicate Training
                        </a>
                    </div>
                </div>
            </div>

            <!-- Training Timeline -->
            <div class="card mt-3">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt"></i>
                        Training Timeline
                    </h5>
                </div>
                <div class="card-body">
                    @if($skillTraining->is_upcoming)
                        <div class="alert alert-info">
                            <i class="fas fa-clock"></i>
                            <strong>Upcoming Training</strong><br>
                            Starts in {{ $skillTraining->start_date->diffForHumans() }}
                        </div>
                    @elseif($skillTraining->is_ongoing)
                        <div class="alert alert-success">
                            <i class="fas fa-play-circle"></i>
                            <strong>Training in Progress</strong><br>
                            Ends in {{ $skillTraining->end_date->diffForHumans() }}
                        </div>
                    @else
                        <div class="alert alert-secondary">
                            <i class="fas fa-check-circle"></i>
                            <strong>Training Completed</strong><br>
                            Ended {{ $skillTraining->end_date->diffForHumans() }}
                        </div>
                    @endif

                    <div class="timeline">
                        <div class="timeline-item {{ $skillTraining->has_started ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>Start Date</strong>
                                <br>
                                <small>{{ $skillTraining->formatted_start_date }}</small>
                            </div>
                        </div>
                        <div class="timeline-item {{ $skillTraining->has_ended ? 'completed' : '' }}">
                            <div class="timeline-marker"></div>
                            <div class="timeline-content">
                                <strong>End Date</strong>
                                <br>
                                <small>{{ $skillTraining->formatted_end_date }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #6c757d;
    border: 3px solid #fff;
}
.timeline-item.completed .timeline-marker {
    background: #28a745;
}
.timeline-content {
    padding: 5px 0;
}
</style>
@endsection
