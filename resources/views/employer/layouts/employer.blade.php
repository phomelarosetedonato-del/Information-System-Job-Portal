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
            background: linear-gradient(135deg, #2E8B57 0%, #1A5D34 100%); /* Green gradient */
            min-height: 100vh;
            height: 100vh;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            width: 16.6667%; /* col-md-2 */
            z-index: 1030;
            overflow-y: auto;
        }
        @media (max-width: 991.98px) {
            .employer-sidebar {
                position: static !important;
                width: 100% !important;
                height: auto !important;
                min-height: 0 !important;
            }
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
            <!-- Mobile Navbar -->
            <nav class="navbar navbar-dark bg-success d-md-none">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#employerSidebar" aria-controls="employerSidebar" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <span class="navbar-brand ms-2">{{ config('app.name') }} <small class="fw-normal">Employer</small></span>
                </div>
            </nav>

            <!-- Sidebar (offcanvas for mobile, sticky for desktop) -->

            <div class="col-md-3 col-lg-2 px-0">
                <!-- Desktop Sidebar: fixed and full height -->
                <div class="d-none d-md-block">
                    <div class="employer-sidebar pt-3">
                        @include('employer.layouts.sidebar')
                    </div>
                </div>
                <!-- Offcanvas Sidebar for Mobile -->
                <div class="offcanvas offcanvas-start employer-sidebar d-md-none" tabindex="-1" id="employerSidebar" aria-labelledby="employerSidebarLabel" style="height: 100vh;">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="employerSidebarLabel">{{ config('app.name') }}</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body p-0">
                        @include('employer.layouts.sidebar')
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-4 py-4" style="margin-left: 0; margin-right: 0;">
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
