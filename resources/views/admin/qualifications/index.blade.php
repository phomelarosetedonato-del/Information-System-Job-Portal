@extends('layouts.admin')

@section('title', 'Qualified Applicants Report - Admin Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Main Content Area -->
    <div class="dashboard-content bg-light">
        <div class="container-fluid py-4">
            <!-- Page Header with Breadcrumb -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="h3 mb-1 text-dark">
                                <i class="fas fa-check-circle me-2 text-success"></i>
                                Qualified Applicants Report
                            </h1>
                            <p class="text-muted small mb-0">Manage and track all qualified PWD applicants</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Qualified Applicants</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Session Messages & Alerts -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-success h-100 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="text-success mb-2">
                        <i class="fas fa-check-double fa-2x"></i>
                    </div>
                    <h3 class="h4 text-success mb-1">{{ $stats['total_qualified'] }}</h3>
                    <p class="text-muted small mb-0">Total Qualified</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-info h-100 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="text-info mb-2">
                        <i class="fas fa-briefcase fa-2x"></i>
                    </div>
                    <h3 class="h4 text-info mb-1">{{ $stats['available_for_jobs'] }}</h3>
                    <p class="text-muted small mb-0">Available for Jobs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-warning h-100 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="text-warning mb-2">
                        <i class="fas fa-pause-circle fa-2x"></i>
                    </div>
                    <h3 class="h4 text-warning mb-1">{{ $stats['not_available'] }}</h3>
                    <p class="text-muted small mb-0">Not Available</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-primary h-100 shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="text-primary mb-2">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                    <h3 class="h4 text-primary mb-1">{{ number_format($stats['average_score'] ?? 0, 2) }}/100</h3>
                    <p class="text-muted small mb-0">Avg Score</p>
                </div>
            </div>
            </div>

            <!-- Filter & Export Options -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-3 text-dark font-weight-bold">Filter by Availability:</h6>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.qualifications.index') }}" class="btn btn-outline-primary {{ !isset($availability) ? 'active' : '' }}">
                                    <i class="fas fa-list me-1"></i>All Qualified
                                </a>
                                <a href="{{ route('admin.qualifications.filter', 'available') }}" class="btn btn-outline-info {{ isset($availability) && $availability === 'available' ? 'active' : '' }}">
                                    <i class="fas fa-check me-1"></i>Available
                                </a>
                                <a href="{{ route('admin.qualifications.filter', 'unavailable') }}" class="btn btn-outline-warning {{ isset($availability) && $availability === 'unavailable' ? 'active' : '' }}">
                                    <i class="fas fa-times me-1"></i>Not Available
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h6 class="mb-3 text-dark font-weight-bold">Export:</h6>
                            <a href="{{ route('admin.qualifications.export-pdf') }}" class="btn btn-danger btn-sm me-2">
                                <i class="fas fa-file-pdf me-1"></i>Export PDF
                            </a>
                            <a href="{{ route('admin.qualifications.export-excel') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel me-1"></i>Export Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Qualified Applicants Table -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-users me-2 text-primary"></i>
                        Qualified Applicants List
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($qualifiedApplicants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 ps-4">Applicant Name</th>
                                        <th class="border-0">Email</th>
                                        <th class="border-0">Qualification Score</th>
                                        <th class="border-0">Qualified Date</th>
                                        <th class="border-0">Availability</th>
                                        <th class="border-0 pe-4 text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($qualifiedApplicants as $applicant)
                                        <tr class="border-bottom">
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-1 text-dark">{{ $applicant->name }}</h6>
                                                        @if($applicant->pwdProfile)
                                                            <small class="text-muted">
                                                                <i class="fas fa-wheelchair me-1"></i>
                                                                {{ $applicant->pwdProfile->disability_type ?? 'N/A' }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $applicant->email }}</span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress" style="width: 100px; height: 20px;" role="progressbar" aria-valuenow="{{ $applicant->qualification_score }}" aria-valuemin="0" aria-valuemax="100">
                                                        <div class="progress-bar bg-success" style="width: {{ $applicant->qualification_score }}%"></div>
                                                    </div>
                                                    <span class="ms-2 small fw-bold">{{ number_format($applicant->qualification_score, 2) }}/100</span>
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted">
                                                    @if($applicant->qualified_at)
                                                        <i class="fas fa-calendar me-1"></i>{{ $applicant->qualified_at->format('M d, Y') }}
                                                    @else
                                                        N/A
                                                    @endif
                                                </small>
                                            </td>
                                            <td>
                                                @if($applicant->available_for_jobs)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-check me-1"></i>Available
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning text-dark">
                                                        <i class="fas fa-pause me-1"></i>Not Available
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="pe-4 text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('applications.show', ['application' => $applicant->jobApplications->first()?->id ?? '#']) }}"
                                                       class="btn btn-outline-primary" title="View Profile">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-outline-info" data-bs-toggle="modal"
                                                            data-bs-target="#updateQualificationModal{{ $applicant->id }}" title="Update Qualification">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <form action="{{ route('admin.qualifications.remove', $applicant->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" title="Remove Qualification"
                                                                onclick="return confirm('Remove qualification for {{ $applicant->name }}?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Update Qualification Modal -->
                                        <div class="modal fade" id="updateQualificationModal{{ $applicant->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Update Qualification - {{ $applicant->name }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="{{ route('admin.qualifications.update', $applicant->id) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="score{{ $applicant->id }}" class="form-label">Qualification Score (0-100)</label>
                                                                <input type="number" class="form-control" id="score{{ $applicant->id }}" name="qualification_score"
                                                                       value="{{ $applicant->qualification_score }}" min="0" max="100" step="0.01" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Availability Status</label>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="available_for_jobs" value="1" id="available{{ $applicant->id }}"
                                                                           {{ $applicant->available_for_jobs ? 'checked' : '' }} required>
                                                                    <label class="form-check-label" for="available{{ $applicant->id }}">
                                                                        <i class="fas fa-check me-1 text-success"></i>Available for Jobs
                                                                    </label>
                                                                </div>
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="available_for_jobs" value="0" id="unavailable{{ $applicant->id }}"
                                                                           {{ !$applicant->available_for_jobs ? 'checked' : '' }} required>
                                                                    <label class="form-check-label" for="unavailable{{ $applicant->id }}">
                                                                        <i class="fas fa-pause me-1 text-warning"></i>Not Available
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                                    <small class="text-muted">
                                        Showing {{ $qualifiedApplicants->firstItem() }} to {{ $qualifiedApplicants->lastItem() }} of {{ $qualifiedApplicants->total() }} applicants
                                    </small>
                                    {{ $qualifiedApplicants->links('pagination::bootstrap-4') }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">No Qualified Applicants Found</h4>
                                    <p class="text-muted mb-0">There are no applicants marked as qualified yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
