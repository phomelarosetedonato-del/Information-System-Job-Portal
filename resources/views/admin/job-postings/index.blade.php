@extends('layouts.admin')

@section('title', 'Manage Job Postings - Admin Panel')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-briefcase"></i> Job Postings Management
        </h1>
        <a href="{{ route('admin.job-postings.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Create New Job Posting
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Job Postings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $jobPostings->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Postings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jobPostings->where('is_active', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Expired Postings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jobPostings->where('application_deadline', '<', now())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Applications
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $jobPostings->sum(function($job) { return $job->applications->count(); }) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters & Search</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.job-postings.index') }}">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" class="form-control" id="search" name="search"
                                   value="{{ request('search') }}" placeholder="Search by title, company, location...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Job Postings Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Job Postings</h6>
            <a href="{{ route('admin.job-postings.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> New Job Posting
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if($jobPostings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Company</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Deadline</th>
                                <th>Applications</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jobPostings as $job)
                                <tr>
                                    <td>
                                        <strong>{{ $job->title }}</strong>
                                        <br>
                                        <small class="text-muted">Created: {{ $job->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>{{ $job->company }}</td>
                                    <td>{{ $job->location->name ?? $job->location }}</td>
                                    <td>
                                        <span class="badge bg-info text-white">{{ $job->employment_type }}</span>
                                    </td>
                                    <td>
                                        @if($job->application_deadline)
                                            @if($job->application_deadline->isPast())
                                                <span class="text-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    {{ $job->application_deadline->format('M d, Y') }}
                                                </span>
                                            @else
                                                {{ $job->application_deadline->format('M d, Y') }}
                                            @endif
                                        @else
                                            <span class="text-muted">No deadline</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary text-white">
                                            {{ $job->applications->count() }} applications
                                        </span>
                                    </td>
                                    <td>
                                        @if($job->is_active)
                                            <span class="badge bg-success text-white">Active</span>
                                        @else
                                            <span class="badge bg-secondary text-white">Inactive</span>
                                        @endif

                                        @if($job->application_deadline && $job->application_deadline->isPast())
                                            <span class="badge bg-warning text-dark mt-1">Expired</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <!-- View Button -->
                                            <a href="{{ route('admin.job-postings.show', $job->id) }}"
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="{{ route('admin.job-postings.edit', $job->id) }}"
                                               class="btn btn-primary btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <!-- Toggle Status Button -->
                                            <form action="{{ route('admin.job-postings.toggle-status', $job->id) }}"
                                                  method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm" title="{{ $job->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-power-off"></i>
                                                </button>
                                            </form>

                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-danger btn-sm"
                                                    title="Delete"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteConfirmModal"
                                                    data-job-id="{{ $job->id }}"
                                                    data-job-title="{{ $job->title }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap">
                    <div class="text-muted mb-2">
                        Showing {{ $jobPostings->firstItem() ?? 0 }} to {{ $jobPostings->lastItem() ?? 0 }} of {{ $jobPostings->total() }} entries
                    </div>
                    <div class="mb-2">
                        {{ $jobPostings->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                    <h3 class="text-muted">No Job Postings Found</h3>
                    <p class="text-muted">Get started by creating your first job posting.</p>
                    <a href="{{ route('admin.job-postings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Job Posting
                    </a>
                </div>
            @endif
        </div>
    </div>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-danger">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteConfirmLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Job Posting?
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Are you sure you want to delete this job posting?</p>
                    <p class="fw-bold mb-3"><span id="jobTitleDisplay"></span></p>
                    <div class="alert alert-warning" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. All associated applications will also be deleted.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Cancel
                    </button>
                    <form id="deleteJobForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Delete Job Posting
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    @parent

    <script>
        // Auto-submit form when status changes
        document.getElementById('status').addEventListener('change', function() {
            this.form.submit();
        });

        // Delete confirmation modal handler
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        deleteConfirmModal.addEventListener('show.bs.modal', function(e) {
            const button = e.relatedTarget;
            const jobId = button.getAttribute('data-job-id');
            const jobTitle = button.getAttribute('data-job-title');

            document.getElementById('jobTitleDisplay').textContent = '"' + jobTitle + '"';

            const form = document.getElementById('deleteJobForm');
            form.action = `/admin/job-postings/${jobId}`;
        });

        // DataTable initialization (if you have DataTables)
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "pageLength": 25,
                "ordering": true,
                "info": false,
                "searching": false,
                "lengthChange": false
            });
        });
    </script>
@endsection
