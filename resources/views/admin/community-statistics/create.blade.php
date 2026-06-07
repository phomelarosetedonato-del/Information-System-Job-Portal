@extends('layouts.app')

@section('title', 'Add New Year - Community PWD Statistics')

@section('content')
<div class="container-fluid mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold text-primary">
                <i class="fas fa-plus-circle me-2"></i> Add New Year - Community PWD Statistics
            </h2>
            <p class="text-muted">Enter employment data for each disability type</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i> Validation Errors</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form action="{{ route('admin.community-statistics.store') }}" method="POST" id="createStatisticsForm" novalidate>
                        @csrf

                        <!-- Year Input -->
                        <div class="mb-4">
                            <label for="year" class="form-label fw-bold">
                                <i class="fas fa-calendar me-2 text-primary"></i> Year <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                   class="form-control form-control-lg @error('year') is-invalid @enderror"
                                   id="year"
                                   name="year"
                                   min="{{ now()->year - 5 }}"
                                   max="{{ now()->year + 5 }}"
                                   value="{{ old('year', now()->year) }}"
                                   required
                                   placeholder="e.g., {{ now()->year }}">
                            @error('year')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-times-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i> Existing years: {{ implode(', ', $existingYears) }}
                            </small>
                        </div>

                        <hr class="my-4">

                        <div class="mb-4">
                            <h5 class="fw-bold text-dark mb-3">
                                <i class="fas fa-wheelchair me-2 text-info"></i> Employment Data by Disability Type
                            </h5>
                            <p class="text-muted small">Enter the number of unemployed and employed persons for each disability type</p>
                        </div>

                        <!-- Disability Types Input -->
                        @foreach ($disabilityTypes as $index => $type)
                            <div class="card bg-light mb-3 border-start border-4 border-info">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6 class="card-title mb-3 fw-bold text-secondary">
                                                {{ $index + 1 }}. {{ $type }}
                                            </h6>
                                        </div>
                                    </div>

                                    <div class="row g-3">
                                        <!-- Disability Type Hidden Input -->
                                        <input type="hidden" name="stats[{{ $index }}][disability_type]" value="{{ $type }}">

                                        <!-- Unemployed Count -->
                                        <div class="col-md-6">
                                            <label for="unemployed_{{ $index }}" class="form-label">
                                                <i class="fas fa-times me-1 text-danger"></i> Unemployed <span class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                   class="form-control @error("stats.{$index}.unemployed_count") is-invalid @enderror"
                                                   id="unemployed_{{ $index }}"
                                                   name="stats[{{ $index }}][unemployed_count]"
                                                   min="0"
                                                   value="{{ old("stats.{$index}.unemployed_count", 0) }}"
                                                   required
                                                   placeholder="0"
                                                   data-type="unemployed">
                                            @error("stats.{$index}.unemployed_count")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Employed Count -->
                                        <div class="col-md-6">
                                            <label for="employed_{{ $index }}" class="form-label">
                                                <i class="fas fa-check me-1 text-success"></i> Employed <span class="text-danger">*</span>
                                            </label>
                                            <input type="number"
                                                   class="form-control @error("stats.{$index}.employed_count") is-invalid @enderror"
                                                   id="employed_{{ $index }}"
                                                   name="stats[{{ $index }}][employed_count]"
                                                   min="0"
                                                   value="{{ old("stats.{$index}.employed_count", 0) }}"
                                                   required
                                                   placeholder="0"
                                                   data-type="employed">
                                            @error("stats.{$index}.employed_count")
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="col-12">
                                            <small class="text-muted">
                                                <i class="fas fa-chart-pie me-1"></i>
                                                Subtotal: <span class="fw-bold" data-subtotal="{{ $index }}">0</span> PWD
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <hr class="my-4">

                        <!-- Summary Cards -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="card text-white bg-danger border-0">
                                    <div class="card-body text-center py-3">
                                        <div class="fs-6 text-uppercase">Total Unemployed</div>
                                        <div class="fs-3 fw-bold" id="totalUnemployed">0</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card text-white bg-success border-0">
                                    <div class="card-body text-center py-3">
                                        <div class="fs-6 text-uppercase">Total Employed</div>
                                        <div class="fs-3 fw-bold" id="totalEmployed">0</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grand Total -->
                        <div class="card bg-primary text-white mb-4">
                            <div class="card-body text-center py-3">
                                <div class="fs-6 text-uppercase">Total PWD Count</div>
                                <div class="fs-2 fw-bold" id="grandTotal">0</div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex gap-2 pt-3">
                            <button type="submit" class="btn btn-primary btn-lg flex-grow-1" id="submitBtn">
                                <i class="fas fa-save me-2"></i> Save Year Statistics
                            </button>
                            <a href="{{ route('admin.community-statistics.index') }}" class="btn btn-secondary btn-lg">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Helper -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-lightbulb me-2"></i> Instructions</h6>
                </div>
                <div class="card-body">
                    <ol class="small">
                        <li class="mb-2">Enter the <strong>year</strong> you want to record statistics for</li>
                        <li class="mb-2">For each disability type, enter the number of <strong>unemployed</strong> persons</li>
                        <li class="mb-2">Then enter the number of <strong>employed</strong> persons</li>
                        <li class="mb-2">The totals will calculate automatically</li>
                        <li class="mb-2">All fields are <span class="badge bg-danger">required</span></li>
                        <li class="mb-0">Click <strong>Save</strong> when complete</li>
                    </ol>

                    <hr class="my-3">

                    <div class="alert alert-info alert-sm mb-0 p-2" role="alert">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Tip:</strong> You must provide data for all 10 disability types. Missing data will cause validation errors.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createStatisticsForm');
    const totalUnemployedEl = document.getElementById('totalUnemployed');
    const totalEmployedEl = document.getElementById('totalEmployed');
    const grandTotalEl = document.getElementById('grandTotal');

    function updateTotals() {
        let totalUnemployed = 0;
        let totalEmployed = 0;

        // Get all input fields
        const unemployedInputs = document.querySelectorAll('input[data-type="unemployed"]');
        const employedInputs = document.querySelectorAll('input[data-type="employed"]');

        // Calculate totals
        unemployedInputs.forEach(input => {
            const index = input.id.replace('unemployed_', '');
            totalUnemployed += parseInt(input.value) || 0;

            // Update subtotal for this disability type
            const subtotal = (parseInt(input.value) || 0) + (parseInt(document.getElementById(`employed_${index}`).value) || 0);
            const subtotalEl = document.querySelector(`span[data-subtotal="${index}"]`);
            if (subtotalEl) {
                subtotalEl.textContent = subtotal;
            }
        });

        employedInputs.forEach(input => {
            totalEmployed += parseInt(input.value) || 0;
        });

        const grandTotal = totalUnemployed + totalEmployed;

        // Update display
        totalUnemployedEl.textContent = totalUnemployed.toLocaleString();
        totalEmployedEl.textContent = totalEmployed.toLocaleString();
        grandTotalEl.textContent = grandTotal.toLocaleString();
    }

    // Listen for input changes on all number inputs
    const inputs = form.querySelectorAll('input[type="number"][name^="stats"]');
    inputs.forEach(input => {
        input.addEventListener('input', updateTotals);
        input.addEventListener('change', updateTotals);
    });

    // Initial calculation
    updateTotals();

    // Form validation
    form.addEventListener('submit', function(e) {
        if (!form.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
        }
        form.classList.add('was-validated');
    });
});
</script>
@endpush

@push('styles')
<style>
    .alert-sm {
        margin-bottom: 0 !important;
        padding: 0.5rem 0.75rem !important;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }
</style>
@endpush
