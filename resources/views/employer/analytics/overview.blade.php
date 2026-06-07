@extends('employer.layouts.employer')

@section('title', 'Analytics Overview')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold">
                <i class="fas fa-chart-line text-primary"></i> Analytics Overview
            </h2>
            <p class="text-muted">Comprehensive insights into your recruitment performance</p>
        </div>
    </div>

    <!-- Key Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Jobs Posted</p>
                            <h3 class="mb-0">{{ $stats['total_jobs'] }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-briefcase text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Applications</p>
                            <h3 class="mb-0">{{ $stats['total_applications'] }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-file-alt text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Active Jobs</p>
                            <h3 class="mb-0">{{ $stats['active_jobs'] }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-check-circle text-info fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Conversion Rate</p>
                            <h3 class="mb-0">{{ $performanceMetrics['conversion_rate'] }}%</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-percentage text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-chart-bar text-primary"></i> Performance Metrics
                    </h5>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="border-start border-primary border-3 ps-3">
                                <p class="text-muted mb-1 small">Avg Application Time</p>
                                <p class="fw-bold mb-0">{{ $performanceMetrics['average_application_time'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border-start border-success border-3 ps-3">
                                <p class="text-muted mb-1 small">Response Time</p>
                                <p class="fw-bold mb-0">{{ $performanceMetrics['response_time'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border-start border-info border-3 ps-3">
                                <p class="text-muted mb-1 small">Completion Rate</p>
                                <p class="fw-bold mb-0">{{ $performanceMetrics['completion_rate'] }}%</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border-start border-warning border-3 ps-3">
                                <p class="text-muted mb-1 small">Quality Score</p>
                                <p class="fw-bold mb-0">{{ $performanceMetrics['application_quality_score'] }}/10</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-star text-warning"></i> Employer Rating
                    </h5>
                    <div class="text-center py-3">
                        <h1 class="display-4 fw-bold text-primary">{{ number_format($performanceMetrics['employer_rating'], 1) }}</h1>
                        <div class="mb-2">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $performanceMetrics['employer_rating'])
                                    <i class="fas fa-star text-warning fa-lg"></i>
                                @elseif ($i - 0.5 <= $performanceMetrics['employer_rating'])
                                    <i class="fas fa-star-half-alt text-warning fa-lg"></i>
                                @else
                                    <i class="far fa-star text-warning fa-lg"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-muted">Based on response time and quality</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Trends Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-chart-line text-primary"></i> Application Trends (Last 30 Days)
                    </h5>
                    <canvas id="applicationTrendsChart" height="80"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-3">
        <div class="col-12 col-md-3">
            <a href="{{ route('employer.analytics.performance') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-tachometer-alt fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Performance Metrics</h5>
                        <p class="text-muted small">Detailed performance analysis</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-3">
            <a href="{{ route('employer.analytics.application-trends') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-line fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Application Trends</h5>
                        <p class="text-muted small">View application patterns</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-3">
            <a href="{{ route('employer.analytics.jobs-performance') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-briefcase fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Jobs Performance</h5>
                        <p class="text-muted small">Individual job metrics</p>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-3">
            <a href="{{ route('employer.dashboard') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-shadow">
                    <div class="card-body text-center">
                        <i class="fas fa-home fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">Dashboard</h5>
                        <p class="text-muted small">Return to main dashboard</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: all 0.3s;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15) !important;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('applicationTrendsChart').getContext('2d');

    const applicationTrends = @json($applicationTrends);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: applicationTrends.labels,
            datasets: [
                {
                    label: 'Applications',
                    data: applicationTrends.applications,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Views',
                    data: applicationTrends.views,
                    borderColor: 'rgb(255, 159, 64)',
                    backgroundColor: 'rgba(255, 159, 64, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
});
</script>
@endpush
@endsection
