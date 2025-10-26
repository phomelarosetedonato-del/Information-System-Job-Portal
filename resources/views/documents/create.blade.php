<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Document - PWD System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">PWD System - Alaminos City</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link text-white" href="{{ route('documents.index') }}">Documents</a>
                <a class="nav-link text-white" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Upload New Document</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="type" class="form-label">Document Type *</label>
                                <select class="form-control" id="type" name="type" required>
                                    <option value="">Select Document Type</option>
                                    <option value="resume" {{ old('type') == 'resume' ? 'selected' : '' }}>Resume/CV</option>
                                    <option value="certificate" {{ old('type') == 'certificate' ? 'selected' : '' }}>Certificate</option>
                                    <option value="id" {{ old('type') == 'id' ? 'selected' : '' }}>ID Document</option>
                                    <option value="medical" {{ old('type') == 'medical' ? 'selected' : '' }}>Medical Certificate</option>
                                    <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other Document</option>
                                </select>
                                @error('type')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Document Name *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                       value="{{ old('name') }}" placeholder="e.g., My Resume, PWD ID, Training Certificate" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description (Optional)</label>
                                <textarea class="form-control" id="description" name="description" rows="3"
                                          placeholder="Brief description of the document">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="document" class="form-label">Select File *</label>
                                <input type="file" class="form-control" id="document" name="document"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                                @error('document')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    Accepted file types: PDF, DOC, DOCX, JPG, JPEG, PNG. Maximum file size: 5MB.
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> Document Guidelines</h6>
                                <ul class="mb-0">
                                    <li>Upload clear and legible documents</li>
                                    <li>Ensure files are not password protected</li>
                                    <li>Keep file sizes reasonable for faster uploads</li>
                                    <li>Documents will be verified by administrators</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('documents.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Upload Document
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
