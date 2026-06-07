@extends('employer.layouts.employer')

@section('title', 'Application Statistics')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('employer.applications.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Applications
            </a>
            <h2 class="fw-bold">
                <i class="fas fa-chart-bar text-primary"></i> My Application Statistics
            </h2>
            <p class="text-muted">Overview of applications received for your job postings</p>
        </div>
    </div>

    <!-- Total Applications -->
    <div class="row g-3 mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="display-4 fw-bold text-primary">{{ number_format($totalApplications) }}</h3>
                    <p class="text-muted mb-0">Total Applications Received</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications by Status -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-chart-pie text-primary"></i> Applications by Status
                    </h5>
                    @if($applicationsByStatus->count() > 0)
                        <canvas id="statusChart" height="200"></canvas>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No application data available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-list text-primary"></i> Status Breakdown
                    </h5>
                    @if($applicationsByStatus->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($applicationsByStatus as $status => $count)
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                                <span>
                                    @if($status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($status == 'shortlisted')
                                        <span class="badge bg-info">Shortlisted</span>
                                    @elseif($status == 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @elseif($status == 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @elseif($status == 'hired')
                                        <span class="badge bg-primary">Hired</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                    @endif
                                </span>
                                <div>
                                    <span class="badge bg-primary rounded-pill me-2">{{ $count }}</span>
                                    <small class="text-muted">
                                        {{ $totalApplications > 0 ? number_format(($count / $totalApplications) * 100, 1) : 0 }}%
                                    </small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No status data available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Applications by Job -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-briefcase text-primary"></i> Applications by Job Posting
                    </h5>
                    @if($applicationsByJob->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Location</th>
                                        <th>Employment Type</th>
                                        <th class="text-center">Applications</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applicationsByJob as $jobApp)
                                    <tr>
                                        <td>
                                            @if($jobApp->jobPosting)
                                                <strong>{{ $jobApp->jobPosting->title }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    Posted {{ $jobApp->jobPosting->created_at->diffForHumans() }}
                                                </small>
                                            @else
                                                <em class="text-muted">Job Deleted</em>
                                            @endif
                                        </td>
                                        <td>
                                            @if($jobApp->jobPosting)
                                                <small>
                                                    @if($jobApp->jobPosting->location && is_object($jobApp->jobPosting->location))
                                                        {{ $jobApp->jobPosting->location->name }}
                                                    @else
                                                        {{ $jobApp->jobPosting->location ?? 'N/A' }}
                                                    @endif
                                                </small>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if($jobApp->jobPosting)
                                                <span class="badge bg-secondary">
                                                    {{ ucfirst($jobApp->jobPosting->employment_type) }}
                                                </span>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary rounded-pill fs-6">
                                                {{ $jobApp->applications }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($jobApp->jobPosting)
                                                <a href="{{ route('employer.applications.index', ['job_posting_id' => $jobApp->job_posting_id]) }}"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> View Applications
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-center">
                                            <strong class="text-primary">{{ $applicationsByJob->sum('applications') }}</strong>
                                        </td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">No Job Postings Yet</h5>
                            <p class="text-muted">Create job postings to start receiving applications</p>
                            <a href="{{ route('job-postings.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create Job Posting
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($applicationsByStatus->count() > 0)
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusData = @json($applicationsByStatus);

    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(key => key.charAt(0).toUpperCase() + key.slice(1)),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: [
                    'rgba(255, 193, 7, 0.8)',  // pending
                    'rgba(13, 202, 240, 0.8)', // shortlisted
                    'rgba(25, 135, 84, 0.8)',  // approved
                    'rgba(220, 53, 69, 0.8)',  // rejected
                    'rgba(13, 110, 253, 0.8)', // hired
                    'rgba(108, 117, 125, 0.8)' // other
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
    @endif
});
</script>
@endpush
@endsection
