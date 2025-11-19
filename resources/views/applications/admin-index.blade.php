<!-- resources/views/applications/admin-index.blade.php -->

@extends('layouts.admin')

@section('title', 'Manage Job Applications - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2 text-dark">
                        <i class="fas fa-briefcase me-2 text-primary"></i>
                        Job Applications Management
                    </h1>
                    <p class="mb-0 text-muted">Review and manage all job applications from PWD users</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('admin.applications.index') }}" method="GET">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label class="form-label small text-muted mb-1">Search Applications</label>
                                    <input type="text" name="search" class="form-control"
                                           placeholder="Search by applicant name, job title, or company..."
                                           value="{{ request('search') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Filter by Status</label>
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="shortlisted" {{ request('status') == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small text-muted mb-1">Sort By</label>
                                    <select name="sort" class="form-select">
                                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-1"></i> Apply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-primary h-100 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-primary mb-2">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h3 class="h4 text-primary mb-1">{{ $applications->where('status', 'pending')->count() }}</h3>
                        <h6 class="card-title text-muted">Pending Review</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-info h-100 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-info mb-2">
                            <i class="fas fa-list fa-2x"></i>
                        </div>
                        <h3 class="h4 text-info mb-1">{{ $applications->where('status', 'shortlisted')->count() }}</h3>
                        <h6 class="card-title text-muted">Shortlisted</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-success h-100 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-success mb-2">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <h3 class="h4 text-success mb-1">{{ $applications->where('status', 'approved')->count() }}</h3>
                        <h6 class="card-title text-muted">Approved</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-warning h-100 shadow-sm">
                    <div class="card-body text-center py-3">
                        <div class="text-warning mb-2">
                            <i class="fas fa-chart-bar fa-2x"></i>
                        </div>
                        <h3 class="h4 text-warning mb-1">{{ $applications->total() }}</h3>
                        <h6 class="card-title text-muted">Total Applications</h6>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-dark">
                                <i class="fas fa-list me-2 text-primary"></i>
                                Application History
                            </h4>
                            <span class="badge bg-light text-dark fs-6 border">
                                {{ $applications->total() }} {{ Str::plural('application', $applications->total()) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($applications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 ps-4">Applicant & Job Details</th>
                                            <th class="border-0">Company</th>
                                            <th class="border-0">Applied Date</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0 pe-4 text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($applications as $application)
                                            <tr class="border-bottom">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 text-dark">{{ $application->user->name }}</h6>
                                                            <div class="text-muted small mb-2">
                                                                <i class="fas fa-envelope me-1"></i>
                                                                {{ $application->user->email }}
                                                            </div>
                                                            <div class="text-dark small">
                                                                <i class="fas fa-briefcase me-1"></i>
                                                                <strong>{{ $application->jobPosting->title }}</strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted">
                                                        <i class="fas fa-building me-1"></i>
                                                        {{ $application->jobPosting->company }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted small">
                                                        <div class="mb-1">{{ $application->created_at->format('M j, Y') }}</div>
                                                        <div>{{ $application->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge
                                                        @if($application->status == 'pending') bg-warning text-dark
                                                        @elseif($application->status == 'shortlisted') bg-info
                                                        @elseif($application->status == 'approved') bg-success
                                                        @elseif($application->status == 'rejected') bg-danger
                                                        @else bg-secondary @endif">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                    @if($application->reviewed_at)
                                                        <div class="text-muted small mt-1">
                                                            Reviewed: {{ $application->reviewed_at->format('M j, Y') }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.applications.show', $application) }}"
                                                           class="btn btn-outline-primary"
                                                           title="View Application Details"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-eye"></i>
                                                        </a>

                                                        <button type="button"
                                                                class="btn btn-outline-secondary dropdown-toggle"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false"
                                                                title="Change Status">
                                                            <i class="fas fa-cog"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <form action="{{ route('admin.applications.update-status', $application) }}" method="POST" class="d-inline w-100">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="shortlisted">
                                                                    <button type="submit" class="dropdown-item text-info">
                                                                        <i class="fas fa-list me-2"></i>Shortlist
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="{{ route('admin.applications.update-status', $application) }}" method="POST" class="d-inline w-100">
                                                                    @csrf
                                                                    <input type="hidden" name="status" value="approved">
                                                                    <button type="submit" class="dropdown-item text-success">
                                                                        <i class="fas fa-check me-2"></i>Approve
                                                                    </button>
                                                                </form>
                                                            </li>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <button type="button" class="dropdown-item text-danger rejection-trigger"
                                                                        data-application-id="{{ $application->id }}"
                                                                        data-applicant-name="{{ $application->user->name }}"
                                                                        data-job-title="{{ $application->jobPosting->title }}">
                                                                    <i class="fas fa-times me-2"></i>Reject
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="card-footer bg-white border-0 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-muted">
                                        Showing {{ $applications->firstItem() }} to {{ $applications->lastItem() }}
                                        of {{ $applications->total() }} entries
                                    </div>
                                    <nav>
                                        {{ $applications->onEachSide(1)->links() }}
                                    </nav>
                                </div>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-briefcase fa-4x text-muted mb-4"></i>
                                    <h3 class="text-muted mb-3">No Applications Found</h3>
                                    <p class="text-muted mb-4">
                                        @if(request()->has('search') || request()->has('status'))
                                            No job applications match your current filters.
                                        @else
                                            There are no job applications to display at the moment.
                                        @endif
                                    </p>

                                    @if(request()->has('search') || request()->has('status'))
                                        <a href="{{ route('admin.applications.index') }}" class="btn btn-primary">
                                            <i class="fas fa-times me-2"></i> Clear Filters
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rejection Reason Modal -->
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectionModalLabel">Reject Application</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectionForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <p class="text-muted" id="rejectionApplicationInfo"></p>
                        <label for="rejection_reason" class="form-label">Rejection Reason (Optional)</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4"
                                  placeholder="Provide a reason for rejection (this will be shared with the applicant)..."></textarea>
                        <div class="form-text">Max 500 characters</div>
                    </div>
                    <input type="hidden" name="status" value="rejected">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Confirm Rejection</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table > :not(caption) > * > * {
        padding: 1rem 0.75rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .border-bottom {
        border-bottom: 1px solid #e9ecef !important;
    }

    .btn-group-sm > .btn {
        border-radius: 6px;
        margin: 0 1px;
    }

    .dropdown-menu {
        min-width: 200px;
    }

    .badge {
        font-size: 0.75em;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Rejection Modal Logic
        const rejectionModal = new bootstrap.Modal(document.getElementById('rejectionModal'));
        let currentApplicationUrl = '';

        // Handle rejection trigger clicks
        document.querySelectorAll('.rejection-trigger').forEach(button => {
            button.addEventListener('click', function() {
                const applicationId = this.getAttribute('data-application-id');
                const applicantName = this.getAttribute('data-applicant-name');
                const jobTitle = this.getAttribute('data-job-title');

                // Set the application info text
                document.getElementById('rejectionApplicationInfo').textContent =
                    `Reject application from "${applicantName}" for "${jobTitle}"?`;

                // Set the form action
                currentApplicationUrl = "{{ url('admin/applications') }}/" + applicationId + "/update-status";
                document.getElementById('rejectionForm').action = currentApplicationUrl;

                // Show the modal
                rejectionModal.show();
            });
        });

        // Handle modal form submission
        document.getElementById('rejectionForm').addEventListener('submit', function(e) {
            const rejectionReason = document.getElementById('rejection_reason').value;

            if (rejectionReason.length > 500) {
                e.preventDefault();
                alert('Rejection reason must be less than 500 characters.');
                return;
            }

            // Optional: Add confirmation for rejection
            if (!confirm('Are you sure you want to reject this application?')) {
                e.preventDefault();
            }
        });

        // Reset modal when closed
        document.getElementById('rejectionModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('rejection_reason').value = '';
            currentApplicationUrl = '';
        });

        // Enhanced status change confirmation for shortlist and approve
        document.querySelectorAll('form[action*="update-status"]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const status = this.querySelector('input[name="status"]').value;
                const applicationRow = this.closest('tr');
                const applicantName = applicationRow.querySelector('h6').textContent;
                const jobTitle = applicationRow.querySelector('.text-dark strong').textContent;

                if (status === 'shortlisted') {
                    if (!confirm(`Shortlist the application from "${applicantName}" for "${jobTitle}"?`)) {
                        e.preventDefault();
                    }
                } else if (status === 'approved') {
                    if (!confirm(`Approve the application from "${applicantName}" for "${jobTitle}"?`)) {
                        e.preventDefault();
                    }
                }
                // Note: Rejection is handled by the modal
            });
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endsection
