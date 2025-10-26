<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $announcement->title }} - PWD System</title>
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
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Edit Announcement: {{ $announcement->title }}</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('announcements.update', $announcement->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="title" class="form-label">Announcement Title *</label>
                                <input type="text" class="form-control" id="title" name="title"
                                       value="{{ old('title', $announcement->title) }}" required>
                                @error('title')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content *</label>
                                <textarea class="form-control" id="content" name="content" rows="8" required>{{ old('content', $announcement->content) }}</textarea>
                                @error('content')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    You can use multiple lines and paragraphs. The content will be displayed with proper formatting.
                                </div>
                            </div>

                            <div class="mb-3 form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                                       {{ old('is_active', $announcement->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Visible to PWD users)
                                </label>
                                <div class="form-text">
                                    When active, this announcement will be displayed on the PWD user dashboard.
                                </div>
                            </div>

                            <!-- Last Updated Info -->
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Last updated:</strong> {{ $announcement->updated_at->format('F d, Y \a\t h:i A') }}
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('announcements.show', $announcement->id) }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancel
                                    </a>
                                    <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                                        Back to List
                                    </a>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Announcement
                                    </button>
                                </div>
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
