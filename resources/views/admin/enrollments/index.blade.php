@extends('layouts.admin')

@section('title', 'Training Enrollments Management - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-users"></i>
                            Training Enrollments Management
                        </h3>
                        <div class="btn-group">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <form method="GET" action="{{ route('admin.enrollments.index') }}" class="row g-2">
                                <div class="col-md-4">
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Statuses</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="training_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Trainings</option>
                                        @foreach($trainings as $training)
                                            <option value="{{ $training->id }}" {{ request('training_id') == $training->id ? 'selected' : '' }}>
                                                {{ $training->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                    <a href="{{ route('admin.enrollments.index') }}" class="btn btn-secondary">Reset</a>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#bulkApproveModal">
                                    <i class="fas fa-check-double"></i> Bulk Approve
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center p-3">
                                    <h5 class="card-title">Total</h5>
                                    <h3 class="mb-0">{{ $enrollments->total() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center p-3">
                                    <h5 class="card-title">Pending</h5>
                                    <h3 class="mb-0">{{ $enrollments->where('status', 'pending')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center p-3">
                                    <h5 class="card-title">Approved</h5>
                                    <h3 class="mb-0">{{ $enrollments->where('status', 'approved')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center p-3">
                                    <h5 class="card-title">Rejected</h5>
                                    <h3 class="mb-0">{{ $enrollments->where('status', 'rejected')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center p-3">
                                    <h5 class="card-title">Completed</h5>
                                    <h3 class="mb-0">{{ $enrollments->where('status', 'completed')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center p-3">
                                    <h5 class="card-title">Cancelled</h5>
                                    <h3 class="mb-0">{{ $enrollments->where('status', 'cancelled')->count() }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrollments Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="50">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Training</th>
                                    <th>Enrolled Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrollments as $enrollment)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="enrollment-checkbox" value="{{ $enrollment->id }}"
                                                   {{ $enrollment->status !== 'pending' ? 'disabled' : '' }}>
                                        </td>
                                        <td>{{ $enrollment->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    @if($enrollment->user->profile_photo)
                                                        <img src="{{ asset('storage/' . $enrollment->user->profile_photo) }}"
                                                             alt="{{ $enrollment->user->name }}"
                                                             class="rounded-circle" width="40" height="40">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $enrollment->user->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $enrollment->user->email }}</small>
                                                    @if($enrollment->user->pwdProfile)
                                                        <br>
                                                        <span class="badge bg-info">PWD</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $enrollment->skillTraining->title }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ $enrollment->skillTraining->start_date->format('M d, Y') }} -
                                                {{ $enrollment->skillTraining->end_date->format('M d, Y') }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                Location: {{ $enrollment->skillTraining->location }}
                                            </small>
                                        </td>
                                        <td>
                                            {{ $enrollment->created_at->format('M d, Y h:i A') }}
                                            <br>
                                            <small class="text-muted">
                                                {{ $enrollment->created_at->diffForHumans() }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{
                                                $enrollment->status === 'approved' ? 'success' :
                                                ($enrollment->status === 'rejected' ? 'danger' :
                                                ($enrollment->status === 'pending' ? 'warning' :
                                                ($enrollment->status === 'completed' ? 'info' : 'secondary')))
                                            }}">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <!-- Quick Status Actions -->
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
                                                        <input type="hidden" name="status" value="completed">
                                                        <button type="submit" class="btn btn-info" title="Mark Completed">
                                                            <i class="fas fa-graduation-cap"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- View Details -->
                                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                                        data-bs-target="#enrollmentModal{{ $enrollment->id }}" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>

                                                <!-- Delete -->
                                                <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger"
                                                            onclick="return confirm('Are you sure you want to delete this enrollment?')"
                                                            title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Enrollment Details Modal -->
                                            <div class="modal fade" id="enrollmentModal{{ $enrollment->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Enrollment Details</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <h6>User Information</h6>
                                                                    <p><strong>Name:</strong> {{ $enrollment->user->name }}</p>
                                                                    <p><strong>Email:</strong> {{ $enrollment->user->email }}</p>
                                                                    <p><strong>Phone:</strong> {{ $enrollment->user->phone ?? 'N/A' }}</p>

                                                                    @if($enrollment->user->pwdProfile)
                                                                        <h6 class="mt-3">PWD Information</h6>
                                                                        <p><strong>Disability Type:</strong> {{ $enrollment->user->pwdProfile->disability_type ?? 'N/A' }}</p>
                                                                        <p><strong>PWD ID:</strong> {{ $enrollment->user->pwdProfile->pwd_id_number ?? 'N/A' }}</p>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <h6>Training Information</h6>
                                                                    <p><strong>Title:</strong> {{ $enrollment->skillTraining->title }}</p>
                                                                    <p><strong>Trainer:</strong> {{ $enrollment->skillTraining->trainer }}</p>
                                                                    <p><strong>Dates:</strong> {{ $enrollment->skillTraining->start_date->format('M d, Y') }} - {{ $enrollment->skillTraining->end_date->format('M d, Y') }}</p>
                                                                    <p><strong>Location:</strong> {{ $enrollment->skillTraining->location }}</p>
                                                                </div>
                                                            </div>

                                                            <div class="row mt-3">
                                                                <div class="col-12">
                                                                    <h6>Enrollment Details</h6>
                                                                    <p><strong>Status:</strong>
                                                                        <span class="badge badge-{{
                                                                            $enrollment->status === 'approved' ? 'success' :
                                                                            ($enrollment->status === 'rejected' ? 'danger' :
                                                                            ($enrollment->status === 'pending' ? 'warning' :
                                                                            ($enrollment->status === 'completed' ? 'info' : 'secondary')))
                                                                        }}">
                                                                            {{ ucfirst($enrollment->status) }}
                                                                        </span>
                                                                    </p>
                                                                    <p><strong>Enrolled:</strong> {{ $enrollment->created_at->format('M d, Y h:i A') }}</p>

                                                                    @if($enrollment->notes)
                                                                        <p><strong>User Notes:</strong> {{ $enrollment->notes }}</p>
                                                                    @endif

                                                                    @if($enrollment->reviewed_at)
                                                                        <p><strong>Reviewed:</strong> {{ $enrollment->reviewed_at->format('M d, Y h:i A') }}</p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <h4>No enrollments found</h4>
                                            <p>There are no training enrollments matching your criteria.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($enrollments->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted">
                                Showing {{ $enrollments->firstItem() }} to {{ $enrollments->lastItem() }} of {{ $enrollments->total() }} entries
                            </div>
                            <div>
                                {{ $enrollments->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Approve Modal -->
<div class="modal fade" id="bulkApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bulk Approve Enrollments</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.enrollments.bulkUpdate') }}" method="POST" id="bulkApproveForm">
                @csrf
                <div class="modal-body">
                    <p>You are about to approve <span id="selectedCount">0</span> selected enrollment(s).</p>
                    <input type="hidden" name="status" value="approved">
                    <input type="hidden" name="enrollment_ids" id="bulkEnrollmentIds" value="">

                    <div class="form-group">
                        <label for="admin_notes" class="form-label">Admin Notes (Optional)</label>
                        <textarea name="admin_notes" id="admin_notes" class="form-control" rows="3"
                                  placeholder="Add notes for this bulk action..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Approve Selected</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.enrollment-checkbox:not(:disabled)');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = this.checked;
                }
            });
            updateSelectedCount();
        });
    }

    // Update selected count
    function updateSelectedCount() {
        const selected = document.querySelectorAll('.enrollment-checkbox:checked:not(:disabled)').length;
        document.getElementById('selectedCount').textContent = selected;

        // Update hidden field with selected IDs
        const selectedIds = Array.from(document.querySelectorAll('.enrollment-checkbox:checked:not(:disabled)'))
            .map(checkbox => checkbox.value);
        document.getElementById('bulkEnrollmentIds').value = selectedIds.join(',');
    }

    // Add event listeners to checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Bulk approve form submission
    const bulkApproveForm = document.getElementById('bulkApproveForm');
    if (bulkApproveForm) {
        bulkApproveForm.addEventListener('submit', function(e) {
            const selectedCount = document.querySelectorAll('.enrollment-checkbox:checked:not(:disabled)').length;
            if (selectedCount === 0) {
                e.preventDefault();
                alert('Please select at least one enrollment to approve.');
                return false;
            }

            if (!confirm(`Are you sure you want to approve ${selectedCount} enrollment(s)?`)) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Auto-refresh every 30 seconds if there are pending enrollments
    const pendingCount = {{ $enrollments->where('status', 'pending')->count() }};
    if (pendingCount > 0) {
        setInterval(() => {
            window.location.reload();
        }, 30000);
    }
});
</script>
@endsection
