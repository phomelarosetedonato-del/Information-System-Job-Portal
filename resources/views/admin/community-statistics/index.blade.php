@extends('layouts.admin')

@section('title', 'Community PWD Statistics - Admin Dashboard')

@section('content')
<div class="dashboard-container">
    <!-- Main Content Area -->
    <div class="dashboard-content bg-light">
        <div class="container-fluid py-4">
            <!-- Page Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h1 class="h3 mb-1 text-dark">
                                <i class="fas fa-chart-bar me-2 text-primary"></i>
                                Community PWD Statistics
                            </h1>
                            <p class="text-muted small mb-0">Track and manage community disability statistics by year</p>
                        </div>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb small mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Community Statistics</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Session Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Year Selection -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div>
                                    <label class="form-label small text-muted mb-2">Select Year</label>
                                    <select id="yearSelect" class="form-select" style="width: 200px;">
                                        @foreach($years as $y)
                                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('admin.community-statistics.create') }}" class="btn btn-success">
                                        <i class="fas fa-plus-circle me-2"></i>Add New Year
                                    </a>
                                    <a href="#" id="editBtn" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i>Edit Data
                                    </a>
                                    <a href="#" id="exportBtn" class="btn btn-success">
                                        <i class="fas fa-download me-2"></i>Export CSV
                                    </a>
                                    <button type="button" id="deleteBtn" class="btn btn-danger" title="Delete this year">
                                        <i class="fas fa-trash-alt me-2"></i>Delete Year
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4" id="statsContainer">
                <!-- Loaded via JavaScript -->
                <div class="col-md-3 mb-3">
                    <div class="card border-danger h-100 shadow-sm">
                        <div class="card-body text-center py-4">
                            <div class="text-danger mb-2">
                                <i class="fas fa-users fa-2x"></i>
                            </div>
                            <h3 class="h4 text-danger mb-1" id="totalUnemployed">-</h3>
                            <p class="text-muted small mb-0">Unemployed PWD</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-success h-100 shadow-sm">
                        <div class="card-body text-center py-4">
                            <div class="text-success mb-2">
                                <i class="fas fa-briefcase fa-2x"></i>
                            </div>
                            <h3 class="h4 text-success mb-1" id="totalEmployed">-</h3>
                            <p class="text-muted small mb-0">Employed PWD</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-info h-100 shadow-sm">
                        <div class="card-body text-center py-4">
                            <div class="text-info mb-2">
                                <i class="fas fa-user-check fa-2x"></i>
                            </div>
                            <h3 class="h4 text-info mb-1" id="totalPwd">-</h3>
                            <p class="text-muted small mb-0">Total PWD</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-warning h-100 shadow-sm">
                        <div class="card-body text-center py-4">
                            <div class="text-warning mb-2">
                                <i class="fas fa-chart-pie fa-2x"></i>
                            </div>
                            <h3 class="h4 text-warning mb-1" id="employmentRate">-</h3>
                            <p class="text-muted small mb-0">Employment Rate</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0">Disability Type Breakdown</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="statsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Disability Type</th>
                                            <th class="text-center">Unemployed</th>
                                            <th class="text-center">Employed</th>
                                            <th class="text-center">Total</th>
                                            <th class="text-center">Employment Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody id="statsBody">
                                        <!-- Loaded via JavaScript -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('yearSelect');
    const editBtn = document.getElementById('editBtn');
    const exportBtn = document.getElementById('exportBtn');
    const deleteBtn = document.getElementById('deleteBtn');

    function loadStats(year) {
        fetch(`/api/community-stats/${year}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateStatsDisplay(data.stats, data.totals);
                }
            })
            .catch(error => console.error('Error loading stats:', error));
    }

    function updateStatsDisplay(stats, totals) {
        document.getElementById('totalUnemployed').textContent = totals.total_unemployed.toLocaleString();
        document.getElementById('totalEmployed').textContent = totals.total_employed.toLocaleString();
        document.getElementById('totalPwd').textContent = totals.total_pwd.toLocaleString();
        document.getElementById('employmentRate').textContent = totals.employment_rate.toFixed(1) + '%';

        const tbody = document.getElementById('statsBody');
        tbody.innerHTML = '';

        stats.forEach(stat => {
            const total = stat.unemployed_count + stat.employed_count;
            const rate = total > 0 ? ((stat.employed_count / total) * 100).toFixed(1) : 0;

            const row = `
                <tr>
                    <td><strong>${stat.disability_type}</strong></td>
                    <td class="text-center"><span class="badge bg-danger">${stat.unemployed_count}</span></td>
                    <td class="text-center"><span class="badge bg-success">${stat.employed_count}</span></td>
                    <td class="text-center"><strong>${total}</strong></td>
                    <td class="text-center">
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" style="width: ${rate}%">${rate}%</div>
                        </div>
                    </td>
                </tr>
            `;

            tbody.innerHTML += row;
        });
    }

    yearSelect.addEventListener('change', function() {
        loadStats(this.value);
    });

    editBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = `/admin/community-statistics/${yearSelect.value}/edit`;
    });

    exportBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.location.href = `/admin/community-statistics/${yearSelect.value}/export-csv`;
    });

    deleteBtn.addEventListener('click', function(e) {
        e.preventDefault();
        const year = yearSelect.value;

        if (confirm(`⚠️ Warning! You are about to delete all statistics for ${year}. This action cannot be undone. Are you sure?`)) {
            if (confirm(`Final confirmation: Delete ALL data for year ${year}?`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/community-statistics/${year}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
    });

    // Load initial stats
    loadStats(yearSelect.value);
});
</script>
@endsection
