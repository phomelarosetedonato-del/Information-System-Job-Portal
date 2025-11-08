@extends('layouts.app')

@section('title', 'Upload Document - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2 text-dark">
                        <i class="fas fa-cloud-upload-alt me-2 text-primary"></i>
                        Upload New Document
                    </h1>
                    <p class="mb-0 text-muted">Add documents to your profile for job applications and training enrollments</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Documents
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-bottom py-3">
                        <h4 class="mb-0 text-dark">
                            <i class="fas fa-file-upload me-2 text-primary"></i>
                            Document Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <!-- Document Type -->
                                <div class="col-md-6 mb-3">
                                    <label for="type" class="form-label">Document Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="type" name="type" required>
                                        <option value="">Select Document Type</option>
                                        <option value="resume" {{ old('type') == 'resume' ? 'selected' : '' }}>Resume/CV</option>
                                        <option value="certificate" {{ old('type') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                        <option value="id" {{ old('type') == 'id' ? 'selected' : '' }}>ID Document</option>
                                        <option value="medical" {{ old('type') == 'medical' ? 'selected' : '' }}>Medical Certificate</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other Document</option>
                                    </select>
                                    @error('type')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Document Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Document Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name') }}"
                                           placeholder="e.g., My Resume, PWD ID, Training Certificate"
                                           required>
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description <span class="text-muted">(Optional)</span></label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          placeholder="Brief description of the document (what it is, when it was issued, etc.)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div class="mb-4">
                                <label for="document" class="form-label">Select File <span class="text-danger">*</span></label>
                                <input type="file" class="form-control" id="document" name="document"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                @error('document')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Accepted file types: PDF, DOC, DOCX, JPG, JPEG, PNG. Maximum file size: 5MB.
                                </div>
                            </div>

                            <!-- Guidelines Card -->
                            <div class="card border-info mb-4">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Document Guidelines
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Upload clear and legible documents
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Ensure files are not password protected
                                                </li>
                                                <li>
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Keep file sizes reasonable
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Documents will be verified by administrators
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Use descriptive names for easy identification
                                                </li>
                                                <li>
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Update documents when they expire
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('documents.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-2"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload me-2"></i> Upload Document
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Quick Tips -->
                <div class="card border-0 bg-light mt-4">
                    <div class="card-body">
                        <h6 class="text-primary mb-3">
                            <i class="fas fa-lightbulb me-2"></i>
                            Quick Tips for Document Upload
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-file-pdf text-danger me-2 mt-1"></i>
                                    <div>
                                        <strong>PDF Files</strong>
                                        <div class="text-muted small">Best for resumes and certificates</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-id-card text-warning me-2 mt-1"></i>
                                    <div>
                                        <strong>ID Documents</strong>
                                        <div class="text-muted small">Clear photos of government IDs</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-images text-success me-2 mt-1"></i>
                                    <div>
                                        <strong>Images</strong>
                                        <div class="text-muted small">High quality, well-lit photos</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
    }

    .card {
        border-radius: 12px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File input validation
        const fileInput = document.getElementById('document');
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size
                if (file.size > maxSize) {
                    alert('File size exceeds 5MB limit. Please choose a smaller file.');
                    e.target.value = ''; // Clear the file input
                    return;
                }

                // Check file type
                const allowedTypes = ['application/pdf', 'application/msword',
                                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                    'image/jpeg', 'image/jpg', 'image/png'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Please select a valid file type (PDF, DOC, DOCX, JPG, JPEG, PNG).');
                    e.target.value = ''; // Clear the file input
                    return;
                }
            }
        });

        // Form submission validation
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const file = fileInput.files[0];
            if (!file) {
                e.preventDefault();
                alert('Please select a file to upload.');
                return;
            }

            // You can add additional validation here if needed
        });
    });
</script>
@endsection
