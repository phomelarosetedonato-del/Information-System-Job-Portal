<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Job Applications - PWD System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>My Job Applications</h2>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Application History</h5>
            </div>
            <div class="card-body">
                @if($applications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Job Position</th>
                                    <th>Company</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                    <tr>
                                        <td>
                                            <strong>{{ $application->jobPosting->title }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $application->jobPosting->location }}</small>
                                        </td>
                                        <td>{{ $application->jobPosting->company }}</td>
                                        <td>{{ $application->created_at->format('M d, Y') }}</td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'pending' => 'warning',
                                                    'approved' => 'success',
                                                    'rejected' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$application->status] ?? 'secondary' }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('applications.show', $application->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Job Applications Yet</h5>
                        <p class="text-muted">You haven't applied to any jobs yet. Browse available jobs on your dashboard.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-search"></i> Browse Jobs
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Application Statistics -->
        @if($applications->count() > 0)
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body text-center">
                        <h6>Total Applications</h6>
                        <h3>{{ $applications->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body text-center">
                        <h6>Pending</h6>
                        <h3>{{ $applications->where('status', 'pending')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body text-center">
                        <h6>Approved</h6>
                        <h3>{{ $applications->where('status', 'approved')->count() }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body text-center">
                        <h6>Rejected</h6>
                        <h3>{{ $applications->where('status', 'rejected')->count() }}</h3>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
