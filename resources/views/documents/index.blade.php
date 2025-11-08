@extends('layouts.app')

@section('title', 'My Documents - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2 text-dark">
                        <i class="fas fa-file-alt me-2 text-primary"></i>
                        My Documents
                    </h1>
                    <p class="mb-0 text-muted">Manage your resumes, certificates, and identification documents</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                        </a>
                        <a href="{{ route('documents.create') }}" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i> Upload Document
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Session Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Document Statistics -->
        @if($documents->count() > 0)
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-primary h-100 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="text-primary mb-3">
                            <i class="fas fa-folder fa-3x"></i>
                        </div>
                        <h3 class="h2 text-primary mb-1">{{ $documents->count() }}</h3>
                        <h5 class="card-title text-muted">Total Documents</h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-info h-100 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="text-info mb-3">
                            <i class="fas fa-file-contract fa-3x"></i>
                        </div>
                        <h3 class="h2 text-info mb-1">{{ $documents->where('type', 'resume')->count() }}</h3>
                        <h5 class="card-title text-muted">Resumes</h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-success h-100 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="text-success mb-3">
                            <i class="fas fa-certificate fa-3x"></i>
                        </div>
                        <h3 class="h2 text-success mb-1">{{ $documents->where('type', 'certificate')->count() }}</h3>
                        <h5 class="card-title text-muted">Certificates</h5>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card border-warning h-100 shadow-sm">
                    <div class="card-body text-center py-4">
                        <div class="text-warning mb-3">
                            <i class="fas fa-id-card fa-3x"></i>
                        </div>
                        <h3 class="h2 text-warning mb-1">{{ $documents->where('type', 'id')->count() }}</h3>
                        <h5 class="card-title text-muted">IDs</h5>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Documents Section -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-dark">
                                <i class="fas fa-folder-open me-2 text-primary"></i>
                                Document Library
                            </h4>
                            <span class="badge bg-light text-dark fs-6 border">
                                {{ $documents->count() }} {{ Str::plural('document', $documents->count()) }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        @if($documents->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0 ps-4">Document Details</th>
                                            <th class="border-0">Type</th>
                                            <th class="border-0">Size</th>
                                            <th class="border-0">Uploaded</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0 pe-4 text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($documents as $document)
                                            <tr class="border-bottom">
                                                <td class="ps-4">
                                                    <div class="d-flex align-items-start">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 text-dark">{{ $document->name }}</h6>
                                                            @if($document->description)
                                                                <div class="text-muted small">
                                                                    {{ $document->description }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $typeColors = [
                                                            'resume' => 'primary',
                                                            'certificate' => 'success',
                                                            'id' => 'warning',
                                                            'medical' => 'danger',
                                                            'other' => 'secondary'
                                                        ];
                                                        $typeIcons = [
                                                            'resume' => 'file-contract',
                                                            'certificate' => 'certificate',
                                                            'id' => 'id-card',
                                                            'medical' => 'file-medical',
                                                            'other' => 'file'
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $typeColors[$document->type] ?? 'secondary' }}">
                                                        <i class="fas fa-{{ $typeIcons[$document->type] ?? 'file' }} me-1"></i>
                                                        {{ ucfirst($document->type) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $size = $document->size;
                                                        if ($size < 1024) {
                                                            $size = $size . ' B';
                                                        } elseif ($size < 1048576) {
                                                            $size = round($size / 1024, 2) . ' KB';
                                                        } else {
                                                            $size = round($size / 1048576, 2) . ' MB';
                                                        }
                                                    @endphp
                                                    <div class="text-muted small">
                                                        <i class="fas fa-weight-hanging me-1"></i>
                                                        {{ $size }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-muted small">
                                                        <div class="mb-1">{{ $document->created_at->format('M j, Y') }}</div>
                                                        <div>{{ $document->created_at->diffForHumans() }}</div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-{{ $document->is_verified ? 'success' : 'warning' }}">
                                                        <i class="fas fa-{{ $document->is_verified ? 'check-circle' : 'clock' }} me-1"></i>
                                                        {{ $document->is_verified ? 'Verified' : 'Pending' }}
                                                    </span>
                                                </td>
                                                <td class="pe-4 text-end">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('documents.show', $document->id) }}"
                                                           target="_blank"
                                                           class="btn btn-outline-primary"
                                                           title="View Document"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('documents.download', $document->id) }}"
                                                           class="btn btn-outline-success"
                                                           title="Download Document"
                                                           data-bs-toggle="tooltip">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                        <form action="{{ route('documents.destroy', $document->id) }}"
                                                              method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="btn btn-outline-danger"
                                                                    title="Delete Document"
                                                                    data-bs-toggle="tooltip"
                                                                    onclick="return confirm('Are you sure you want to delete this document?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <!-- Empty State -->
                            <div class="text-center py-5">
                                <div class="py-4">
                                    <i class="fas fa-file-upload fa-4x text-muted mb-4"></i>
                                    <h3 class="text-muted mb-3">No Documents Yet</h3>
                                    <p class="text-muted mb-4">Upload your resumes, certificates, and IDs to complete your profile and applications.</p>

                                    <div class="row justify-content-center">
                                        <div class="col-md-8 col-lg-6">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body py-4">
                                                    <h5 class="card-title text-primary mb-3">
                                                        <i class="fas fa-cloud-upload-alt me-2"></i>
                                                        Start Building Your Portfolio
                                                    </h5>
                                                    <p class="card-text text-muted mb-4">
                                                        Upload important documents like resumes, certificates, and identification to enhance your job applications and profile completeness.
                                                    </p>
                                                    <div class="d-grid gap-2 d-md-flex justify-content-center">
                                                        <a href="{{ route('documents.create') }}" class="btn btn-primary btn-lg px-4">
                                                            <i class="fas fa-upload me-2"></i> Upload First Document
                                                        </a>
                                                        <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">
                                                            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
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
        margin: 0 2px;
    }
</style>
@endsection
@include('partials.accessibility-widget') 
@section('scripts')
<script>
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            var alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                var bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endsection
