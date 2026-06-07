@extends('employer.layouts.employer')

@section('title', 'Performance Metrics')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('employer.analytics.overview') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Overview
            </a>
            <h2 class="fw-bold">
                <i class="fas fa-tachometer-alt text-primary"></i> Performance Metrics
            </h2>
            <p class="text-muted">Detailed analysis of your recruitment performance</p>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Total Job Views</p>
                            <h3 class="mb-0">{{ number_format($metrics['total_job_views']) }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-eye text-primary fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Across all job postings</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Avg Views Per Job</p>
                            <h3 class="mb-0">{{ number_format($metrics['average_views_per_job'], 1) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-chart-bar text-success fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Average engagement</small>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 small">Application to View Ratio</p>
                            <h3 class="mb-0">{{ $metrics['application_to_view_ratio'] }}%</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-percentage text-info fa-lg"></i>
                        </div>
                    </div>
                    <small class="text-muted">Conversion effectiveness</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Metrics -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-clock text-primary"></i> Time Metrics
                    </h5>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-hourglass-half text-muted"></i>
                                Average Application Time
                            </span>
                            <span class="badge bg-primary rounded-pill">
                                {{ $metrics['average_application_time'] ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-reply text-muted"></i>
                                Average Response Time
                            </span>
                            <span class="badge bg-success rounded-pill">
                                {{ $metrics['response_time'] ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-calendar-check text-muted"></i>
                                Average Time to Fill
                            </span>
                            <span class="badge bg-info rounded-pill">
                                {{ $metrics['time_to_fill'] ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-award text-warning"></i> Quality Metrics
                    </h5>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-user-check text-muted"></i>
                                Application Quality Score
                            </span>
                            <span class="badge bg-warning rounded-pill">
                                {{ $metrics['application_quality_score'] }}/10
                            </span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-star text-muted"></i>
                                Employer Rating
                            </span>
                            <span class="badge bg-warning rounded-pill">
                                {{ number_format($metrics['employer_rating'], 1) }}/5
                            </span>
                        </div>
                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fas fa-smile text-muted"></i>
                                Candidate Satisfaction
                            </span>
                            <span class="badge bg-success rounded-pill">
                                {{ number_format($metrics['candidate_satisfaction'], 1) }}/5
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Comparison -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-balance-scale text-primary"></i> Platform Comparison
                    </h5>
                    <p class="text-muted small mb-4">See how you compare to platform averages</p>

                    <div class="row g-4">
                        <div class="col-12 col-md-4">
                            <h6>Conversion Rate</h6>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Your Performance</small>
                                    <small class="fw-bold">{{ $comparisonData['conversion_rate']['user'] }}%</small>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-primary"
                                         style="width: {{ min(100, ($comparisonData['conversion_rate']['user'] / $comparisonData['conversion_rate']['platform_average']) * 100) }}%">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Platform Average</small>
                                    <small class="fw-bold">{{ $comparisonData['conversion_rate']['platform_average'] }}%</small>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-secondary" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <h6>Response Time</h6>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Your Performance</small>
                                    <small class="fw-bold">{{ $comparisonData['response_time']['user'] }} hrs</small>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success"
                                         style="width: {{ min(100, 100 - (($comparisonData['response_time']['user'] / $comparisonData['response_time']['platform_average']) * 50)) }}%">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Platform Average</small>
                                    <small class="fw-bold">{{ $comparisonData['response_time']['platform_average'] }} hrs</small>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-secondary" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <h6>Application Quality</h6>
                            <div class="mb-2">
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Your Performance</small>
                                    <small class="fw-bold">{{ $comparisonData['application_quality']['user'] }}/10</small>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-info"
                                         style="width: {{ ($comparisonData['application_quality']['user'] / $comparisonData['application_quality']['platform_average']) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between mb-1">
                                    <small>Platform Average</small>
                                    <small class="fw-bold">{{ $comparisonData['application_quality']['platform_average'] }}/10</small>
                                </div>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-secondary" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rate Breakdown -->
    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-check-circle text-success"></i> Conversion Rate
                    </h5>
                    <div class="text-center py-3">
                        <h1 class="display-4 fw-bold text-success">{{ $metrics['conversion_rate'] }}%</h1>
                        <p class="text-muted">Applications that resulted in hires</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-tasks text-info"></i> Completion Rate
                    </h5>
                    <div class="text-center py-3">
                        <h1 class="display-4 fw-bold text-info">{{ $metrics['completion_rate'] }}%</h1>
                        <p class="text-muted">Jobs successfully filled or completed</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
