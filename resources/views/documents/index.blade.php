<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Documents - PWD System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">PWD System - Alaminos City</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link text-white" href="{{ route('applications.index') }}">Applications</a>
                <a class="nav-link text-white" href="{{ route('profile.show') }}">Profile</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Documents</h2>
            <div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
                <a href="{{ route('documents.create') }}" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Upload Document
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Document Library</h5>
            </div>
            <div class="card-body">
                @if($documents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Document Name</th>
                                    <th>Type</th>
                                    <th>Size</th>
                                    <th>Uploaded</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $document)
                                    <tr>
                                        <td>
                                            <strong>{{ $document->name }}</strong>
                                            @if($document->description)
                                                <br>
                                                <small class="text-muted">{{ $document->description }}</small>
                                            @endif
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
                                            @endphp
                                            <span class="badge bg-{{ $typeColors[$document->type] ?? 'secondary' }}">
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
                                            {{ $size }}
                                        </td>
                                        <td>{{ $document->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-{{ $document->is_verified ? 'success' : 'warning' }}">
                                                {{ $document->is_verified ? 'Verified' : 'Pending' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('documents.show', $document->id) }}"
                                               target="_blank"
                                               class="btn btn-sm btn-info"
                                               title="View Document">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <form action="{{ route('documents.destroy', $document->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Are you sure you want to delete this document?')"
                                                        title="Delete Document">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Documents Uploaded Yet</h5>
                        <p class="text-muted">Upload your resumes, certificates, and IDs to complete your profile and applications.</p>
                        <a href="{{ route('documents.create') }}" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Your First Document
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Document Statistics -->
        @if($documents->count() > 0)
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body text-center">
                        <h6>Total Documents</h6>
                        <h3>{{ $documents->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body text-center">
                        <h6>Resumes</h6>
                        <h3>{{ $documents->where('type', 'resume')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h6>Certificates</h6>
                        <h3>{{ $documents->where('type', 'certificate')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body text-center">
                        <h6>IDs</h6>
                        <h3>{{ $documents->where('type', 'id')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
