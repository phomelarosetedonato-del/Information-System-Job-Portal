@extends('employer.layouts.employer')

@section('title', 'Jobs Performance')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('employer.analytics.overview') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Overview
            </a>
            <h2 class="fw-bold">
                <i class="fas fa-briefcase text-info"></i> Jobs Performance
            </h2>
            <p class="text-muted">Individual job posting metrics and performance analysis</p>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-trophy text-warning"></i> Top Performing Jobs
                    </h5>
                    @if($topPerformingJobs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($topPerformingJobs as $job)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $job->title }}</h6>
                                        <div class="d-flex gap-3 text-muted small">
                                            <span>
                                                <i class="fas fa-eye"></i> {{ $job->views ?? 0 }} views
                                            </span>
                                            <span>
                                                <i class="fas fa-file-alt"></i> {{ $job->applications_count }} applications
                                            </span>
                                        </div>
                                    </div>
                                    <span class="badge bg-success rounded-pill">
                                        {{ $job->views > 0 ? number_format(($job->applications_count / $job->views) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p class="mb-0">No job data available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="fas fa-chart-line text-danger"></i> Needs Attention
                    </h5>
                    @if($underperformingJobs->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($underperformingJobs as $job)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $job->title }}</h6>
                                        <div class="d-flex gap-3 text-muted small">
                                            <span>
                                                <i class="fas fa-eye"></i> {{ $job->views ?? 0 }} views
                                            </span>
                                            <span>
                                                <i class="fas fa-file-alt"></i> {{ $job->applications_count }} applications
                                            </span>
                                        </div>
                                    </div>
                                    <span class="badge bg-warning text-dark rounded-pill">
                                        Low
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                            <p class="mb-0">All jobs performing well!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Performance Table -->
    @if(!empty($jobsPerformance))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-table text-primary"></i> Detailed Performance Metrics
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Job Title</th>
                                    <th class="text-center">Views</th>
                                    <th class="text-center">Applications</th>
                                    <th class="text-center">Conversion Rate</th>
                                    <th class="text-center">Progress</th>
                                    <th class="text-center">Performance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jobsPerformance as $performance)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $performance['job_title'] }}</strong>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info rounded-pill">
                                            <i class="fas fa-eye"></i> {{ $performance['views'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary rounded-pill">
                                            <i class="fas fa-file-alt"></i> {{ $performance['applications'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge {{ $performance['application_ratio'] >= 10 ? 'bg-success' : ($performance['application_ratio'] >= 5 ? 'bg-warning text-dark' : 'bg-danger') }} rounded-pill">
                                            {{ $performance['application_ratio'] }}%
                                        </span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px; min-width: 100px;">
                                            <div class="progress-bar {{ $performance['completion_rate'] >= 75 ? 'bg-success' : ($performance['completion_rate'] >= 50 ? 'bg-warning' : 'bg-info') }}"
                                                 style="width: {{ $performance['completion_rate'] }}%">
                                                {{ number_format($performance['completion_rate'], 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $score = ($performance['application_ratio'] * 0.5) + ($performance['views'] * 0.05);
                                            $rating = $score >= 15 ? 'Excellent' : ($score >= 10 ? 'Good' : ($score >= 5 ? 'Fair' : 'Poor'));
                                            $ratingColor = $score >= 15 ? 'success' : ($score >= 10 ? 'primary' : ($score >= 5 ? 'warning' : 'danger'));
                                        @endphp
                                        <span class="badge bg-{{ $ratingColor }} rounded-pill">
                                            {{ $rating }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-briefcase fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No Job Performance Data Available</h5>
                    <p class="text-muted">Start posting jobs to see performance metrics here.</p>
                    <a href="{{ route('employer.job-postings.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Job Posting
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Performance Insights -->
    @if(!empty($jobsPerformance))
    <div class="row g-3">
        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #0d6efd;">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-lightbulb text-warning"></i> Best Conversion Rate
                    </h6>
                    <p class="mb-0">
                        @php
                            $bestJob = collect($jobsPerformance)->sortByDesc('application_ratio')->first();
                        @endphp
                        @if($bestJob)
                            <strong>{{ $bestJob['job_title'] }}</strong>
                            <br>
                            <span class="text-success">{{ $bestJob['application_ratio'] }}% conversion</span>
                        @else
                            No data available
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #198754;">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-eye text-info"></i> Most Viewed Job
                    </h6>
                    <p class="mb-0">
                        @php
                            $mostViewed = collect($jobsPerformance)->sortByDesc('views')->first();
                        @endphp
                        @if($mostViewed)
                            <strong>{{ $mostViewed['job_title'] }}</strong>
                            <br>
                            <span class="text-info">{{ $mostViewed['views'] }} views</span>
                        @else
                            No data available
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card shadow-sm h-100" style="border-left: 4px solid #ffc107;">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-users text-primary"></i> Most Applications
                    </h6>
                    <p class="mb-0">
                        @php
                            $mostApplications = collect($jobsPerformance)->sortByDesc('applications')->first();
                        @endphp
                        @if($mostApplications)
                            <strong>{{ $mostApplications['job_title'] }}</strong>
                            <br>
                            <span class="text-primary">{{ $mostApplications['applications'] }} applications</span>
                        @else
                            No data available
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
