@extends('layouts.admin')

@section('title', 'Edit Community PWD Statistics - Admin Dashboard')

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
                                <i class="fas fa-edit me-2 text-primary"></i>
                                Edit Community Statistics
                            </h1>
                            <p class="text-muted small mb-0">Update disability statistics for {{ $year }}</p>
                        </div>
                        <a href="{{ route('admin.community-statistics.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </a>
                    </div>
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

            <!-- Live Totals Display -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card border-danger h-100 shadow-sm">
                        <div class="card-body text-center py-3">
                            <small class="text-muted">Total Unemployed</small>
                            <h3 class="h3 text-danger mb-0" id="liveTotalUnemployed">{{ $totals['total_unemployed'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-success h-100 shadow-sm">
                        <div class="card-body text-center py-3">
                            <small class="text-muted">Total Employed</small>
                            <h3 class="h3 text-success mb-0" id="liveTotalEmployed">{{ $totals['total_employed'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-info h-100 shadow-sm">
                        <div class="card-body text-center py-3">
                            <small class="text-muted">Total PWD</small>
                            <h3 class="h3 text-info mb-0" id="liveTotalPwd">{{ $totals['total_pwd'] }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card border-warning h-100 shadow-sm">
                        <div class="card-body text-center py-3">
                            <small class="text-muted">Employment Rate</small>
                            <h3 class="h3 text-warning mb-0" id="liveEmploymentRate">{{ $totals['employment_rate'] }}%</h3>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('admin.community-statistics.update', $year) }}" method="POST" id="statsForm">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white border-bottom">
                                <h5 class="mb-0">Disability Types - {{ $year }}</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="editTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Disability Type</th>
                                                <th class="text-center" style="width: 150px;">Unemployed</th>
                                                <th class="text-center" style="width: 150px;">Employed</th>
                                                <th class="text-center" style="width: 100px;">Total</th>
                                                <th class="text-center" style="width: 120px;">Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stats as $index => $stat)
                                                <tr>
                                                    <td>
                                                        <input type="hidden" name="stats[{{ $index }}][disability_type]" value="{{ $stat->disability_type }}">
                                                        <strong>{{ $stat->disability_type }}</strong>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="stats[{{ $index }}][unemployed_count]" class="form-control unemployed-input" value="{{ $stat->unemployed_count }}" min="0" required>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="stats[{{ $index }}][employed_count]" class="form-control employed-input" value="{{ $stat->employed_count }}" min="0" required>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="stat-total">{{ $stat->unemployed_count + $stat->employed_count }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="stat-rate">{{ $stat->getEmploymentRate() }}%</span>
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

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Changes
                            </button>
                            <a href="{{ route('admin.community-statistics.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                            <a href="{{ route('admin.community-statistics.export-csv', $year) }}" class="btn btn-success ms-auto">
                                <i class="fas fa-download me-2"></i>Export CSV
                            </a>
                            <button type="button" id="deleteYearBtn" class="btn btn-danger" data-year="{{ $year }}">
                                <i class="fas fa-trash-alt me-2"></i>Delete Year
                            </button>
                        </div>
                    </div>
                </div>

                <!-- CSV Import Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm border-info">
                            <div class="card-header bg-info bg-opacity-10 border-info">
                                <h5 class="mb-0 text-info">
                                    <i class="fas fa-upload me-2"></i>Import from CSV
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small mb-3">Upload a CSV file to update all statistics at once. Format: disability_type,unemployed_count,employed_count</p>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="csvFile" accept=".csv" />
                                    <button class="btn btn-info" type="button" id="importBtn">
                                        <i class="fas fa-upload me-2"></i>Import
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const table = document.getElementById('editTable');
    const form = document.getElementById('statsForm');

    function calculateAndUpdateTotals() {
        let totalUnemployed = 0;
        let totalEmployed = 0;

        document.querySelectorAll('tbody tr').forEach(row => {
            const unemployedInput = row.querySelector('.unemployed-input');
            const employedInput = row.querySelector('.employed-input');
            const totalCell = row.querySelector('.stat-total');
            const rateCell = row.querySelector('.stat-rate');

            const unemployed = parseInt(unemployedInput.value) || 0;
            const employed = parseInt(employedInput.value) || 0;
            const total = unemployed + employed;

            totalUnemployed += unemployed;
            totalEmployed += employed;

            // Update row totals
            totalCell.textContent = total;

            // Calculate employment rate
            const rate = total > 0 ? ((employed / total) * 100).toFixed(1) : 0;
            rateCell.textContent = rate + '%';
        });

        const totalPwd = totalUnemployed + totalEmployed;
        const employmentRate = totalPwd > 0 ? ((totalEmployed / totalPwd) * 100).toFixed(2) : 0;

        // Update live totals
        document.getElementById('liveTotalUnemployed').textContent = totalUnemployed;
        document.getElementById('liveTotalEmployed').textContent = totalEmployed;
        document.getElementById('liveTotalPwd').textContent = totalPwd;
        document.getElementById('liveEmploymentRate').textContent = employmentRate + '%';
    }

    // Add event listeners to all number inputs
    document.querySelectorAll('.unemployed-input, .employed-input').forEach(input => {
        input.addEventListener('input', function() {
            // Validate input
            if (this.value < 0) {
                this.value = 0;
            }
            calculateAndUpdateTotals();
        });
    });

    // CSV Import
    document.getElementById('importBtn').addEventListener('click', function() {
        const file = document.getElementById('csvFile').files[0];
        if (!file) {
            alert('Please select a CSV file');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const csv = e.target.result;
            const lines = csv.trim().split('\n');

            let rowIndex = 0;
            for (let i = 1; i < lines.length; i++) {
                if (rowIndex >= document.querySelectorAll('tbody tr').length) break;

                const parts = lines[i].split(',');
                if (parts.length < 3) continue;

                const row = document.querySelectorAll('tbody tr')[rowIndex];
                const unemployedInput = row.querySelector('.unemployed-input');
                const employedInput = row.querySelector('.employed-input');

                unemployedInput.value = parseInt(parts[1]) || 0;
                employedInput.value = parseInt(parts[2]) || 0;

                rowIndex++;
            }

            calculateAndUpdateTotals();
            alert('CSV imported successfully');
        };
        reader.readAsText(file);
    });

    // Initial calculation
    calculateAndUpdateTotals();

    // Delete Year Button
    document.getElementById('deleteYearBtn').addEventListener('click', function() {
        const year = this.getAttribute('data-year');

        if (confirm(`⚠️ Warning! You are about to delete all statistics for ${year}. This action cannot be undone. Are you sure?`)) {
            if (confirm(`Final confirmation: Delete ALL data for year ${year}?`)) {
                const deleteForm = document.createElement('form');
                deleteForm.method = 'POST';
                deleteForm.action = `/admin/community-statistics/${year}`;
                deleteForm.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(deleteForm);
                deleteForm.submit();
            }
        }
    });
});
</script>
@endsection
