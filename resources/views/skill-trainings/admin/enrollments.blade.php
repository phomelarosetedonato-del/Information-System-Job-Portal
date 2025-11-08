@extends('layouts.admin')

@section('title', 'Training Enrollments - ' . $skillTraining->title)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-users"></i>
                        Enrollments for: {{ $skillTraining->title }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.skill-trainings.show', $skillTraining) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Training
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Training Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Training Details</h5>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th width="30%">Trainer:</th>
                                    <td>{{ $skillTraining->trainer }}</td>
                                </tr>
                                <tr>
                                    <th>Dates:</th>
                                    <td>{{ $skillTraining->start_date->format('M d, Y') }} - {{ $skillTraining->end_date->format('M d, Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Location:</th>
                                    <td>{{ $skillTraining->location }}</td>
                                </tr>
                                <tr>
                                    <th>Max Participants:</th>
                                    <td>
                                        <span class="{{ $skillTraining->enrollments_count >= $skillTraining->max_participants ? 'text-danger' : 'text-success' }}">
                                            {{ $skillTraining->enrollments_count }} / {{ $skillTraining->max_participants }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Enrollment Statistics</h5>
                            <table class="table table-sm table-bordered">
                                <tr>
                                    <th width="40%">Total Enrollments:</th>
                                    <td>{{ $skillTraining->enrollments_count }}</td>
                                </tr>
                                <tr>
                                    <th>Pending:</th>
                                    <td>
                                        <span class="badge badge-warning">
                                            {{ $enrollments->where('status', 'pending')->count() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Approved:</th>
                                    <td>
                                        <span class="badge badge-success">
                                            {{ $enrollments->where('status', 'approved')->count() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Rejected:</th>
                                    <td>
                                        <span class="badge badge-danger">
                                            {{ $enrollments->where('status', 'rejected')->count() }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Enrollments Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>User Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Enrollment Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrollments as $enrollment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $enrollment->user) }}">
                                                {{ $enrollment->user->name }}
                                            </a>
                                        </td>
                                        <td>{{ $enrollment->user->email }}</td>
                                        <td>{{ $enrollment->user->phone ?? 'N/A' }}</td>
                                        <td>{{ $enrollment->created_at->format('M d, Y h:i A') }}</td>
                                        <td>
                                            <span class="badge badge-{{
                                                $enrollment->status === 'approved' ? 'success' :
                                                ($enrollment->status === 'rejected' ? 'danger' : 'warning')
                                            }}">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @if($enrollment->status === 'pending')
                                                    <form action="{{ route('admin.enrollments.updateStatus', $enrollment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="btn btn-success" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('admin.enrollments.updateStatus', $enrollment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="btn btn-danger" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @elseif($enrollment->status === 'approved')
                                                    <form action="{{ route('admin.enrollments.updateStatus', $enrollment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="btn btn-danger" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('admin.enrollments.updateStatus', $enrollment) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="btn btn-success" title="Approve">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#enrollmentModal{{ $enrollment->id }}" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>

                                            <!-- Enrollment Details Modal -->
                                            <div class="modal fade" id="enrollmentModal{{ $enrollment->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Enrollment Details</h5>
                                                            <button type="button" class="close" data-dismiss="modal">
                                                                <span>&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <strong>User:</strong>
                                                                    <p>{{ $enrollment->user->name }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Email:</strong>
                                                                    <p>{{ $enrollment->user->email }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <strong>Phone:</strong>
                                                                    <p>{{ $enrollment->user->phone ?? 'N/A' }}</p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Enrolled:</strong>
                                                                    <p>{{ $enrollment->created_at->format('M d, Y h:i A') }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <strong>Status:</strong>
                                                                    <p>
                                                                        <span class="badge badge-{{
                                                                            $enrollment->status === 'approved' ? 'success' :
                                                                            ($enrollment->status === 'rejected' ? 'danger' : 'warning')
                                                                        }}">
                                                                            {{ ucfirst($enrollment->status) }}
                                                                        </span>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            @if($enrollment->user->pwdProfile)
                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <strong>PWD Information:</strong>
                                                                        <p>
                                                                            Disability: {{ $enrollment->user->pwdProfile->disability_type ?? 'N/A' }}<br>
                                                                            ID Number: {{ $enrollment->user->pwdProfile->pwd_id_number ?? 'N/A' }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-users fa-2x mb-3"></i><br>
                                            No enrollments found for this training.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($enrollments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $enrollments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Add confirmation for status changes
        $('form[action*="updateStatus"]').on('submit', function(e) {
            const status = $(this).find('input[name="status"]').val();
            const action = status === 'approved' ? 'approve' : 'reject';

            if (!confirm(`Are you sure you want to ${action} this enrollment?`)) {
                e.preventDefault();
            }
        });
    });
</script>
@endsection
