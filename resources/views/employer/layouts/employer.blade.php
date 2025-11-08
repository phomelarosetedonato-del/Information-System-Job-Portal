<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Employer Dashboard') - {{ config('app.name') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .employer-sidebar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: white;
        }
        .employer-sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        .employer-sidebar .nav-link:hover,
        .employer-sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
        }
        .employer-sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        .verification-badge {
            background: #28a745;
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            font-size: 0.75rem;
        }
        .pending-badge {
            background: #ffc107;
            color: black;
        }
        .rejected-badge {
            background: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 employer-sidebar">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold">{{ config('app.name') }}</h4>
                        <small>Employer Portal</small>
                    </div>

                    <!-- Employer Info -->
                    <div class="text-center mb-4 p-3 bg-dark bg-opacity-25 rounded">
                        <div class="mb-2">
                            <i class="fas fa-building fa-2x"></i>
                        </div>
                        <h6 class="mb-1">{{ Auth::user()->company_name ?? 'Company Name' }}</h6>
                        <small class="text-muted">{{ Auth::user()->email }}</small>
                        <div class="mt-2">
                            @if(Auth::user()->isEmployerVerified())
                                <span class="verification-badge">
                                    <i class="fas fa-check-circle"></i> Verified
                                </span>
                            @elseif(Auth::user()->isEmployerPendingVerification())
                                <span class="verification-badge pending-badge">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            @else
                                <span class="verification-badge rejected-badge">
                                    <i class="fas fa-exclamation-circle"></i> Unverified
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Navigation -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.dashboard') ? 'active' : '' }}"
                               href="{{ route('employer.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>

                        @if(Auth::user()->isEmployerVerified())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.job-postings.*') ? 'active' : '' }}"
                               href="{{ route('employer.job-postings.index') }}">
                                <i class="fas fa-briefcase"></i> Job Postings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.applications.*') ? 'active' : '' }}"
                               href="{{ route('employer.applications.index') }}">
                                <i class="fas fa-users"></i> Applications
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-chart-bar"></i> Analytics
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('employer.analytics.overview') }}">Overview</a></li>
                                <li><a class="dropdown-item" href="{{ route('employer.analytics.performance') }}">Performance</a></li>
                                <li><a class="dropdown-item" href="{{ route('employer.analytics.applications-trend') }}">Application Trends</a></li>
                                <li><a class="dropdown-item" href="{{ route('employer.analytics.jobs-performance') }}">Jobs Performance</a></li>
                            </ul>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.profile.*') ? 'active' : '' }}"
                               href="{{ route('employer.profile.show') }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>

                        @if(!Auth::user()->isEmployerVerified())
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.verification.*') ? 'active' : '' }}"
                               href="{{ route('employer.verification.status') }}">
                                <i class="fas fa-shield-alt"></i> Verification
                            </a>
                        </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('employer.settings') ? 'active' : '' }}"
                               href="{{ route('employer.settings') }}">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-4 py-4">
                @include('layouts.partials.alerts')

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
