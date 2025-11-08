@extends('layouts.admin')

@section('title', 'Manage Skill Trainings - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Manage Skill Trainings</h4>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row">
        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-multiple widget-icon"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Total Trainings</h5>
                    <h3 class="mt-3 mb-3">{{ $totalTrainings }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-check-circle widget-icon text-success"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Active Trainings</h5>
                    <h3 class="mt-3 mb-3">{{ $activeTrainings }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-calendar-clock widget-icon text-info"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Upcoming</h5>
                    <h3 class="mt-3 mb-3">{{ $upcomingTrainings }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card widget-flat">
                <div class="card-body">
                    <div class="float-end">
                        <i class="mdi mdi-account-group widget-icon text-warning"></i>
                    </div>
                    <h5 class="text-muted fw-normal mt-0">Total Enrollments</h5>
                    <h3 class="mt-3 mb-3">{{ $totalEnrollments }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <h5>All Skill Trainings</h5>
                <a href="{{ route('admin.skill-trainings.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Create New Training
                </a>
            </div>
        </div>
    </div>

    <!-- Trainings Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Trainer</th>
                                    <th>Schedule</th>
                                    <th>Location</th>
                                    <th>Participants</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($skillTrainings as $training)
                                    <tr>
                                        <td>
                                            <strong>{{ $training->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ Str::limit($training->description, 50) }}</small>
                                        </td>
                                        <td>{{ $training->trainer }}</td>
                                        <td>
                                            <small>{{ $training->formatted_start_date }}</small>
                                            <br>
                                            <small class="text-muted">to {{ $training->formatted_end_date }}</small>
                                        </td>
                                        <td>{{ $training->location }}</td>
                                        <td>
                                            {{ $training->enrollments_count ?? $training->enrollments->count() }} / {{ $training->max_participants }}
                                            @if($training->is_full)
                                                <span class="badge bg-danger">Full</span>
                                            @endif
                                        </td>
                                        <td>{!! $training->status_badge !!}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.skill-trainings.show', $training) }}"
                                                   class="btn btn-sm btn-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.skill-trainings.edit', $training) }}"
                                                   class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.skill-trainings.enrollments', $training) }}"
                                                   class="btn btn-sm btn-primary" title="Enrollments">
                                                    <i class="fas fa-users"></i>
                                                </a>
                                                <form action="{{ route('admin.skill-trainings.toggle-status', $training) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $training->is_active ? 'btn-secondary' : 'btn-success' }}"
                                                            title="{{ $training->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas {{ $training->is_active ? 'fa-pause' : 'fa-play' }}"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.skill-trainings.destroy', $training) }}"
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this training?')"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                                            <h5>No Skill Trainings Found</h5>
                                            <p class="text-muted">Get started by creating your first skill training.</p>
                                            <a href="{{ route('admin.skill-trainings.create') }}" class="btn btn-primary">
                                                Create Training
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($skillTrainings->hasPages())
                        <div class="row mt-3">
                            <div class="col-12">
                                {{ $skillTrainings->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
