@extends('employer.layouts.employer')

@section('title', 'Application Trends')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('employer.analytics.overview') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Overview
            </a>
            <h2 class="fw-bold">
                <i class="fas fa-chart-line text-success"></i> Application Trends
            </h2>
            <p class="text-muted">Track application patterns and source data</p>
        </div>
    </div>

    <!-- Trends Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-chart-area text-primary"></i> Application & View Trends (Last 30 Days)
                    </h5>
                    <canvas id="trendsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications by Category -->
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-layer-group text-info"></i> Applications by Category
                    </h5>
                    @if(!empty($categoryData))
                        <canvas id="categoryChart" height="200"></canvas>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No category data available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-map-marker-alt text-warning"></i> Application Sources
                    </h5>
                    @if(!empty($sourceData))
                        <canvas id="sourceChart" height="200"></canvas>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3"></i>
                            <p>No source data available yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Category Breakdown Table -->
    @if(!empty($categoryData))
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-table text-primary"></i> Category Breakdown
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th class="text-end">Total Applications</th>
                                    <th class="text-end">Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $total = array_sum($categoryData);
                                @endphp
                                @foreach($categoryData as $category => $count)
                                <tr>
                                    <td>
                                        <i class="fas fa-tag text-muted me-2"></i>
                                        {{ ucfirst($category) }}
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if($total > 0)
                                            <div class="progress" style="height: 20px; min-width: 100px;">
                                                <div class="progress-bar"
                                                     style="width: {{ ($count / $total) * 100 }}%">
                                                    {{ number_format(($count / $total) * 100, 1) }}%
                                                </div>
                                            </div>
                                        @else
                                            <span>0%</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="fw-bold">
                                    <td>Total</td>
                                    <td class="text-end">
                                        <span class="badge bg-dark rounded-pill">{{ $total }}</span>
                                    </td>
                                    <td class="text-end">100%</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Insights -->
    <div class="row g-3">
        <div class="col-md-4">
            <div class="card shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-lightbulb text-warning"></i> Peak Application Day
                    </h6>
                    <p class="mb-0">
                        @if(!empty($trends['applications']))
                            @php
                                $maxIndex = array_search(max($trends['applications']), $trends['applications']);
                                $peakDay = isset($trends['labels'][$maxIndex]) ?
                                    \Carbon\Carbon::parse($trends['labels'][$maxIndex])->format('M d, Y') : 'N/A';
                            @endphp
                            <strong>{{ $peakDay }}</strong> with <strong>{{ max($trends['applications']) }}</strong> applications
                        @else
                            No data available
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-chart-line text-success"></i> Average Daily Applications
                    </h6>
                    <p class="mb-0">
                        @if(!empty($trends['applications']))
                            <strong>{{ number_format(array_sum($trends['applications']) / count($trends['applications']), 1) }}</strong> applications per day
                        @else
                            No data available
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-start border-info border-4">
                <div class="card-body">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-fire text-danger"></i> Most Popular Category
                    </h6>
                    <p class="mb-0">
                        @if(!empty($categoryData))
                            @php
                                $topCategory = array_key_first($categoryData);
                            @endphp
                            <strong>{{ ucfirst($topCategory) }}</strong> with <strong>{{ $categoryData[$topCategory] }}</strong> applications
                        @else
                            No data available
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Trends Chart
    const trendsCtx = document.getElementById('trendsChart').getContext('2d');
    const trendsData = @json($trends);

    new Chart(trendsCtx, {
        type: 'line',
        data: {
            labels: trendsData.labels,
            datasets: [
                {
                    label: 'Applications',
                    data: trendsData.applications,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Views',
                    data: trendsData.views,
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

    // Category Chart
    @if(!empty($categoryData))
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = @json($categoryData);

    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(categoryData).map(cat => cat.charAt(0).toUpperCase() + cat.slice(1)),
            datasets: [{
                data: Object.values(categoryData),
                backgroundColor: [
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)',
                    'rgba(255, 159, 64, 0.8)',
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

    // Source Chart
    @if(!empty($sourceData))
    const sourceCtx = document.getElementById('sourceChart').getContext('2d');
    const sourceData = @json($sourceData);

    new Chart(sourceCtx, {
        type: 'pie',
        data: {
            labels: Object.keys(sourceData),
            datasets: [{
                data: Object.values(sourceData),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
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
