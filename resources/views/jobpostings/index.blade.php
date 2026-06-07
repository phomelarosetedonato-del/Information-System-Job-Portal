
@extends('employer.layouts.employer')

@section('content')
<div class="row mb-4">
    <div class="col-12">
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
    </div>
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0">
            <i class="fas fa-briefcase text-primary"></i> Manage Job Postings
        </h2>
        <a href="{{ route('employer.job-postings.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Create New Job Posting
        </a>
    </div>
</div>
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Employment Type</th>
                        <th>Salary</th>
                        <th>Deadline</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jobPostings as $job)
                        <tr>
                            <td>{{ $job->title }}</td>
                            <td>{{ $job->company }}</td>
                            <td>{{ $job->location ? $job->location->name : 'N/A' }}</td>
                            <td>{{ $job->employment_type }}</td>
                            <td>{{ $job->salary ? '₱' . number_format($job->salary, 2) : 'Negotiable' }}</td>
                            <td>{{ $job->application_deadline ? $job->application_deadline->format('M d, Y') : 'No deadline' }}</td>
                            <td>
                                <span class="badge bg-{{ $job->is_active ? 'success' : 'secondary' }}">
                                    {{ $job->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('employer.job-postings.show', $job->id) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('employer.job-postings.edit', $job->id) }}" class="btn btn-primary btn-sm">Edit</a>
                                <button type="button" class="btn btn-danger btn-sm delete-job-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteConfirmModal"
                                        data-job-id="{{ $job->id }}"
                                        data-job-title="{{ $job->title }}">Delete</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No job postings found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $jobPostings->links() }}
        </div>
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
@endsection

@push('scripts')
<script>
    // Delete confirmation modal handler
    const deleteConfirmModal = document.getElementById('deleteConfirmModal');
    deleteConfirmModal.addEventListener('show.bs.modal', function(e) {
        const button = e.relatedTarget;
        const jobId = button.getAttribute('data-job-id');
        const jobTitle = button.getAttribute('data-job-title');

        document.getElementById('jobTitleDisplay').textContent = '"' + jobTitle + '"';

        const form = document.getElementById('deleteJobForm');
        form.action = `/employer/job-postings/${jobId}`;
    });
</script>
@endpush
