<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Details - PWD System</title>
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
                        <h4 class="mb-0">Application Details</h4>
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $statusColors[$application->status] }} fs-6">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Job Information -->
                        <div class="mb-4">
                            <h5 class="text-primary">Job Information</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" style="width: 30%">Position:</td>
                                            <td>{{ $application->jobPosting->title }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Company:</td>
                                            <td>{{ $application->jobPosting->company }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Location:</td>
                                            <td>{{ $application->jobPosting->location }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Employment Type:</td>
                                            <td>{{ $application->jobPosting->employment_type }}</td>
                                        </tr>
                                        @if($application->jobPosting->salary)
                                        <tr>
                                            <td class="fw-bold">Salary:</td>
                                            <td>₱{{ number_format($application->jobPosting->salary, 2) }}</td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Application Information -->
                        <div class="mb-4">
                            <h5 class="text-primary">Application Information</h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="fw-bold" style="width: 30%">Applied Date:</td>
                                            <td>{{ $application->created_at->format('F d, Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Last Updated:</td>
                                            <td>{{ $application->updated_at->format('F d, Y \a\t h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Status:</td>
                                            <td>
                                                <span class="badge bg-{{ $statusColors[$application->status] }}">
                                                    {{ ucfirst($application->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Job Description -->
                        <div class="mb-4">
                            <h5 class="text-primary">Job Description</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ $application->jobPosting->description }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Requirements -->
                        <div class="mb-4">
                            <h5 class="text-primary">Requirements</h5>
                            <div class="card">
                                <div class="card-body">
                                    <p class="mb-0">{{ $application->jobPosting->requirements }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Notes (if any) -->
                        @if($application->admin_notes)
                        <div class="mb-4">
                            <h5 class="text-primary">Admin Notes</h5>
                            <div class="card border-info">
                                <div class="card-body">
                                    <p class="mb-0">{{ $application->admin_notes }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('applications.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Applications
                            </a>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-search"></i> Browse More Jobs
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
