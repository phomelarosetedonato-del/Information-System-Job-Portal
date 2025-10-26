<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $announcement->title }} - PWD System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">PWD System - Alaminos City</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link text-white" href="{{ route('dashboard') }}">Dashboard</a>
                <a class="nav-link text-white" href="{{ route('announcements.index') }}">Announcements</a>
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
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $announcement->title }}</h4>
                        <div>
                            <span class="badge bg-{{ $announcement->is_active ? 'success' : 'secondary' }} me-2">
                                {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-calendar"></i> {{ $announcement->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Announcement Content -->
                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Announcement Content</h5>
                                    <div class="announcement-content" style="white-space: pre-line; font-size: 1.1em; line-height: 1.6;">
                                        {{ $announcement->content }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Announcement Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Announcement Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td class="fw-bold" style="width: 40%">Status:</td>
                                                <td>
                                                    <span class="badge bg-{{ $announcement->is_active ? 'success' : 'secondary' }}">
                                                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Created By:</td>
                                                <td>{{ $announcement->creator->name ?? 'System Admin' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Created Date:</td>
                                                <td>{{ $announcement->created_at->format('F d, Y \a\t h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Last Updated:</td>
                                                <td>{{ $announcement->updated_at->format('F d, Y \a\t h:i A') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">Visibility</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($announcement->is_active)
                                            <div class="alert alert-success">
                                                <i class="fas fa-eye"></i>
                                                <strong>This announcement is visible</strong> to all PWD users on their dashboard.
                                            </div>
                                        @else
                                            <div class="alert alert-secondary">
                                                <i class="fas fa-eye-slash"></i>
                                                <strong>This announcement is hidden</strong> from PWD users.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('announcements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Announcements
                            </a>
                            <div>
                                <a href="{{ route('announcements.edit', $announcement->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Announcement
                                </a>
                                <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this announcement? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete Announcement
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
