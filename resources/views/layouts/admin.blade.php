<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Admin Panel - PWD System')</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Load compiled CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom styles -->
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: linear-gradient(180deg, #2c3e50 0%, #3498db 100%);
            --sidebar-color: white;
            --header-height: 70px;
            --accessibility-primary: #667eea;
            --accessibility-secondary: #764ba2;
        }

        body {
            overflow-x: hidden;
            background-color: #f8f9fa;
        }

        /* Fixed Sidebar */
        .pwd-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            z-index: 1000;
            overflow-y: auto;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        /* Main Content Area */
        .main-content-with-sidebar {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Dashboard Header */
        .dashboard-header {
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Sidebar Header */
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0,0,0,0.1);
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid rgba(255,255,255,0.2);
        }

        .avatar-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .user-details h6 {
            color: white;
            font-weight: 600;
            margin-bottom: 0;
        }

        .user-details small {
            color: rgba(255,255,255,0.7);
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            padding: 20px 0;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .nav-section {
            margin-bottom: 25px;
        }

        .nav-section.mt-auto {
            margin-top: auto;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0 20px 8px 20px;
            margin-bottom: 8px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
            font-weight: 600;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            margin: 2px 8px;
            border-radius: 8px;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            border-left-color: #3498db;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: rgba(231, 76, 60, 0.8);
            color: white;
            border-left-color: #e74c3c;
            font-weight: 600;
        }

        .nav-link.text-danger {
            color: rgba(255,255,255,0.8) !important;
        }

        .nav-link.text-danger:hover {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c !important;
            border-left-color: #e74c3c;
        }

        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 12px;
        }

        /* Mobile Toggle Button */
        .mobile-menu-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: #3498db;
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }

        .sidebar-overlay.mobile-open {
            display: block;
        }

        /* Content Container */
        .content-container {
            padding: 30px;
        }

        /* ========================================
           ACCESSIBILITY WIDGET STYLES
           ======================================== */
        
        .accessibility-widget {
            position: fixed !important;
            right: 20px !important;
            bottom: 20px !important;
            z-index: 10000 !important;
        }
        
        .accessibility-toggle {
            width: 60px !important;
            height: 60px !important;
            border-radius: 50% !important;
            background: linear-gradient(135deg, #2E8B57 0%, #1A5D34 100%) !important;
            color: white !important;
            border: none !important;
            box-shadow: 0 4px 20px rgba(46, 139, 87, 0.4) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            cursor: pointer !important;
            transition: all 0.3s ease !important;
            z-index: 10001 !important;
            animation: pulse-green 2s infinite;
        }

        @keyframes pulse-green {
            0%, 100% {
                box-shadow: 0 4px 20px rgba(46, 139, 87, 0.4);
            }
            50% {
                box-shadow: 0 4px 30px rgba(46, 139, 87, 0.7);
            }
        }

        .accessibility-toggle:hover {
            transform: scale(1.1) !important;
            background: linear-gradient(135deg, #1A5D34 0%, #2E8B57 100%) !important;
            box-shadow: 0 6px 25px rgba(46, 139, 87, 0.6) !important;
        }

        .accessibility-toggle i {
            font-size: 1.8rem !important;
        }

        .accessibility-panel {
            position: fixed !important;
            right: 20px !important;
            bottom: 95px !important;
            width: 380px !important;
            max-height: 80vh !important;
            background: white !important;
            border-radius: 16px !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
            border: 2px solid #2E8B57 !important;
            z-index: 10002 !important;
            display: none !important;
            overflow: hidden !important;
        }

        .accessibility-panel.show {
            display: block !important;
            animation: slideUpPanel 0.3s ease;
        }

        @keyframes slideUpPanel {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .panel-header {
            background: linear-gradient(135deg, #2E8B57 0%, #1A5D34 100%) !important;
            color: white !important;
            padding: 20px !important;
            display: flex !important;
            justify-content: space-between !important;
            align-items: start !important;
        }

        .panel-body {
            padding: 20px !important;
            max-height: calc(80vh - 80px) !important;
            overflow-y: auto !important;
        }

        .setting-group {
            margin-bottom: 24px !important;
            padding-bottom: 20px !important;
            border-bottom: 1px solid #e9ecef !important;
        }

        .setting-group:last-child {
            border-bottom: none !important;
        }

        .setting-group h6 {
            color: #2E8B57 !important;
            font-weight: 600 !important;
            margin-bottom: 12px !important;
            font-size: 0.95rem !important;
        }

        .btn-group-setting {
            display: flex !important;
            gap: 8px !important;
            flex-wrap: wrap !important;
        }

        .btn-setting {
            flex: 1 !important;
            min-width: 80px !important;
            padding: 10px 12px !important;
            border: 2px solid #e9ecef !important;
            border-radius: 8px !important;
            background: white !important;
            color: #495057 !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
        }

        .btn-setting:hover {
            border-color: #2E8B57 !important;
            background: #f0f8f5 !important;
            transform: translateY(-2px) !important;
        }

        .btn-setting.active {
            background: #2E8B57 !important;
            border-color: #2E8B57 !important;
            color: white !important;
            font-weight: 600 !important;
        }

        .language-flag {
            font-size: 1.2rem !important;
            margin-right: 4px !important;
        }

        .preset-card {
            padding: 15px !important;
            border: 2px solid #e9ecef !important;
            border-radius: 10px !important;
            margin-bottom: 12px !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
        }

        .preset-card:hover {
            border-color: #2E8B57 !important;
            background: #f0f8f5 !important;
            transform: translateX(5px) !important;
        }

        .preset-icon {
            width: 40px !important;
            height: 40px !important;
            background: linear-gradient(135deg, #2E8B57 0%, #1A5D34 100%) !important;
            border-radius: 10px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            color: white !important;
            font-size: 1.2rem !important;
        }

        .setting-option {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 12px 0 !important;
        }

        .setting-label {
            font-size: 0.9rem !important;
            color: #495057 !important;
        }

        .form-check-input:checked {
            background-color: #2E8B57 !important;
            border-color: #2E8B57 !important;
        }

        .quick-actions {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 12px !important;
        }

        .btn-quick-action {
            padding: 15px 10px !important;
            border: 2px solid #e9ecef !important;
            border-radius: 10px !important;
            background: white !important;
            cursor: pointer !important;
            transition: all 0.2s ease !important;
            text-align: center !important;
            font-size: 0.75rem !important;
            color: #495057 !important;
        }

        .btn-quick-action:hover {
            border-color: #2E8B57 !important;
            background: #f0f8f5 !important;
            transform: translateY(-3px) !important;
            box-shadow: 0 4px 12px rgba(46, 139, 87, 0.2) !important;
        }

        .btn-quick-action i {
            color: #2E8B57 !important;
            font-size: 1.5rem !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .accessibility-panel {
                width: calc(100vw - 40px) !important;
                right: 20px !important;
                left: 20px !important;
            }

            .accessibility-toggle {
                width: 55px !important;
                height: 55px !important;
                right: 15px !important;
                bottom: 15px !important;
            }

            .quick-actions {
                grid-template-columns: repeat(2, 1fr) !important;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .pwd-sidebar {
                transform: translateX(-100%);
                width: 280px;
            }

            .pwd-sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-content-with-sidebar {
                margin-left: 0;
            }

            .mobile-menu-btn {
                display: flex;
            }

            .content-container {
                padding: 15px;
            }
        }

        /* ========================================
           ACCESSIBILITY FEATURE STYLES
           ======================================== */

        /* Font Size Classes */
        body.font-size-small {
            font-size: 12px !important;
        }

        body.font-size-small h1 { font-size: 1.8rem !important; }
        body.font-size-small h2 { font-size: 1.6rem !important; }
        body.font-size-small h3 { font-size: 1.4rem !important; }
        body.font-size-small h4 { font-size: 1.2rem !important; }
        body.font-size-small h5 { font-size: 1.1rem !important; }
        body.font-size-small h6 { font-size: 1rem !important; }

        body.font-size-medium {
            font-size: 16px !important;
        }

        body.font-size-large {
            font-size: 18px !important;
        }

        body.font-size-large h1 { font-size: 2.8rem !important; }
        body.font-size-large h2 { font-size: 2.4rem !important; }
        body.font-size-large h3 { font-size: 2rem !important; }
        body.font-size-large h4 { font-size: 1.75rem !important; }
        body.font-size-large h5 { font-size: 1.5rem !important; }
        body.font-size-large h6 { font-size: 1.25rem !important; }

        body.font-size-xlarge {
            font-size: 22px !important;
        }

        body.font-size-xlarge h1 { font-size: 3.2rem !important; }
        body.font-size-xlarge h2 { font-size: 2.8rem !important; }
        body.font-size-xlarge h3 { font-size: 2.4rem !important; }
        body.font-size-xlarge h4 { font-size: 2rem !important; }
        body.font-size-xlarge h5 { font-size: 1.75rem !important; }
        body.font-size-xlarge h6 { font-size: 1.5rem !important; }

        /* Contrast Classes */
        body.contrast-normal {
            /* Default contrast - no changes needed */
        }

        body.contrast-high {
            filter: contrast(130%) !important;
        }

        body.contrast-high .card,
        body.contrast-high .btn,
        body.contrast-high .table {
            border: 2px solid #000 !important;
        }

        body.contrast-high .text-muted {
            color: #495057 !important;
        }

        body.contrast-very-high {
            background-color: #000 !important;
            color: #FFF !important;
            filter: contrast(150%) !important;
        }

        body.contrast-very-high * {
            border-color: #FFF !important;
        }

        body.contrast-very-high .card,
        body.contrast-very-high .card-body,
        body.contrast-very-high .card-header {
            background-color: #1a1a1a !important;
            color: #FFFF00 !important;
            border: 3px solid #FFFFFF !important;
        }

        body.contrast-very-high .btn {
            background-color: #000 !important;
            color: #FFFF00 !important;
            border: 2px solid #FFFF00 !important;
        }

        body.contrast-very-high .btn:hover {
            background-color: #FFFF00 !important;
            color: #000 !important;
        }

        body.contrast-very-high a {
            color: #00FFFF !important;
            text-decoration: underline !important;
        }

        body.contrast-very-high .text-muted,
        body.contrast-very-high .text-secondary {
            color: #FFFFFF !important;
        }

        body.contrast-very-high .table {
            color: #FFFFFF !important;
            border: 2px solid #FFFFFF !important;
        }

        body.contrast-very-high .table th,
        body.contrast-very-high .table td {
            border: 1px solid #FFFFFF !important;
            background-color: #000 !important;
        }

        body.contrast-very-high input,
        body.contrast-very-high select,
        body.contrast-very-high textarea {
            background-color: #000 !important;
            color: #FFFF00 !important;
            border: 2px solid #FFFFFF !important;
        }

        body.contrast-very-high .pwd-sidebar,
        body.contrast-very-high .navbar {
            background-color: #000 !important;
            border-right: 3px solid #FFF !important;
        }

        body.contrast-very-high .nav-link {
            color: #FFFF00 !important;
        }

        body.contrast-very-high .nav-link:hover,
        body.contrast-very-high .nav-link.active {
            background-color: #FFFF00 !important;
            color: #000 !important;
        }

        /* Dyslexia Friendly Font */
        body.dyslexia-font,
        body.dyslexia-font * {
            font-family: 'Comic Sans MS', 'OpenDyslexic', Arial, sans-serif !important;
            letter-spacing: 0.12em !important;
            word-spacing: 0.16em !important;
            line-height: 1.8 !important;
        }

        body.dyslexia-font h1,
        body.dyslexia-font h2,
        body.dyslexia-font h3,
        body.dyslexia-font h4,
        body.dyslexia-font h5,
        body.dyslexia-font h6 {
            font-weight: 700 !important;
        }

        /* Motor Assistance */
        body.motor-friendly button,
        body.motor-friendly .btn,
        body.motor-friendly a {
            min-width: 48px !important;
            min-height: 48px !important;
            padding: 12px 20px !important;
        }

        body.motor-friendly input,
        body.motor-friendly select,
        body.motor-friendly textarea {
            min-height: 48px !important;
            font-size: 18px !important;
        }

        /* Reduce Motion */
        body.reduce-motion * {
            animation: none !important;
            transition: none !important;
        }

        /* Simplified Layout */
        body.simplified-layout .card-hover {
            box-shadow: none !important;
            border: 2px solid #000 !important;
        }

        body.simplified-layout .shadow,
        body.simplified-layout .shadow-sm,
        body.simplified-layout .shadow-lg {
            box-shadow: none !important;
        }

        /* No Focus Outline */
        body.no-focus-outline *:focus {
            outline: none !important;
            box-shadow: none !important;
        }
    </style>

    @yield('styles')
</head>

<body class="{{ App\Http\Controllers\Accessibility\AccessibilityController::getBodyClasses() }}">
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="pwd-sidebar" id="pwdSidebar">
        <div class="sidebar-header">
            <div class="user-info">
                <div class="user-avatar">
                    @if(Auth::user()->avatar ?? false)
                        <img src="{{ Auth::user()->avatar }}" alt="{{ Auth::user()->name }}" class="avatar-img">
                    @else
                        <div class="avatar-placeholder">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                <div class="user-details">
                    <h6>{{ Auth::user()->name ?? 'Administrator' }}</h6>
                    <small>Admin Panel</small>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <!-- Dashboard -->
            <div class="nav-section">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- User Management -->
            <div class="nav-section">
                <div class="section-title">User Management</div>
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i>
                    <span>Manage Users</span>
                </a>
                <a href="{{ route('admin.applications.index') }}" class="nav-link {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i>
                    <span>Job Applications</span>
                </a>
            </div>

            <!-- Content Management -->
            <div class="nav-section">
                <div class="section-title">Content Management</div>
                <a href="{{ route('admin.job-postings.index') }}" class="nav-link {{ request()->routeIs('admin.job-postings.*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Job Postings</span>
                </a>
                <a href="{{ route('admin.skill-trainings.index') }}" class="nav-link {{ request()->routeIs('admin.skill-trainings.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Skill Trainings</span>
                </a>
                <a href="{{ route('admin.announcements.index') }}" class="nav-link {{ request()->routeIs('admin.announcements.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </div>

            <!-- Training Management -->
            <div class="nav-section">
                <div class="section-title">Training Management</div>
                <a href="{{ route('admin.enrollments.index') }}" class="nav-link {{ request()->routeIs('admin.enrollments.*') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i>
                    <span>Training Enrollments</span>
                </a>
            </div>

            <!-- System Tools -->
            <div class="nav-section">
                <div class="section-title">System Tools</div>
                <a href="{{ route('admin.security.report') }}" class="nav-link {{ request()->routeIs('admin.security.*') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt"></i>
                    <span>Security Center</span>
                </a>
                <a href="{{ route('admin.statistics') }}" class="nav-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </div>

            <!-- Bottom Section -->
            <div class="nav-section mt-auto">
                <a href="{{ route('admin.profile.show') }}" class="nav-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i>
                    <span>My Profile</span>
                </a>
                <a href="{{ url('/') }}" class="nav-link" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    <span>View Public Site</span>
                </a>
                <a href="{{ route('logout') }}" class="nav-link text-danger"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </nav>
    </div>

    <!-- Main Content -->
    <main class="main-content-with-sidebar">
        <!-- Dashboard Header -->
        <div class="dashboard-header bg-white border-bottom" style="border-color: #dee2e6 !important; border-width: 2px !important;">
            <div class="container-fluid py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h4 mb-0 text-dark">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            @yield('page-title', 'Admin Dashboard')
                        </h1>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <!-- Breadcrumb removed as requested -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Content -->
        <div class="content-container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    {{-- Include Modern Accessibility Widget with Translation Support --}}
    @include('partials.accessibility-widget')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile sidebar toggle
            const mobileToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('pwdSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            if (mobileToggle && sidebar && overlay) {
                mobileToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('mobile-open');
                    overlay.classList.toggle('mobile-open');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('mobile-open');
                    overlay.classList.remove('mobile-open');
                });

                // Close sidebar when clicking on nav links on mobile
                const navLinks = document.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth <= 768) {
                            sidebar.classList.remove('mobile-open');
                            overlay.classList.remove('mobile-open');
                        }
                    });
                });
            }

            // Accessibility Widget Functionality
            console.log('=== LAYOUT ACCESSIBILITY WIDGET INITIALIZING ===');
            
            const accessibilityToggle = document.getElementById('accessibilityToggle');
            const accessibilityPanel = document.getElementById('accessibilityPanel');
            const closeAccessibilityPanel = document.getElementById('closeAccessibilityPanel');

            console.log('Toggle:', accessibilityToggle ? '✓' : '✗');
            console.log('Panel:', accessibilityPanel ? '✓' : '✗');
            console.log('Close button:', closeAccessibilityPanel ? '✓' : '✗');

            if (accessibilityToggle && accessibilityPanel && closeAccessibilityPanel) {
                // Toggle panel visibility
                accessibilityToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isShowing = accessibilityPanel.classList.toggle('show');
                    console.log('>>> Panel toggled:', isShowing ? 'OPEN' : 'CLOSED');
                });

                closeAccessibilityPanel.addEventListener('click', function(e) {
                    e.stopPropagation();
                    accessibilityPanel.classList.remove('show');
                });

                // Close panel when clicking outside
                document.addEventListener('click', function(event) {
                    if (!accessibilityPanel.contains(event.target) && !accessibilityToggle.contains(event.target)) {
                        accessibilityPanel.classList.remove('show');
                    }
                });

                // Prevent panel clicks from closing it
                accessibilityPanel.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Language controls
            document.querySelectorAll('.language-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const language = this.dataset.language;
                    console.log('>>> LANGUAGE BUTTON CLICKED:', language);

                    fetch('/accessibility/quick-tool', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            tool: 'language',
                            language: language
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('>>> Language change response:', data);
                        
                        if (data.success) {
                            // Update active state
                            document.querySelectorAll('.language-btn').forEach(b => b.classList.remove('active'));
                            this.classList.add('active');

                            // Update language text
                            document.getElementById('currentLanguageText').textContent =
                                language === 'tl' ? 'Kasalukuyang wika: Tagalog' : 'Current language: English';

                            console.log('>>> Reloading page to apply language...');
                            
                            // Reload page to apply language changes
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }
                    })
                    .catch(error => {
                        console.error('>>> Language change ERROR:', error);
                    });
                });
            });

            // Font size controls
            document.querySelectorAll('[data-size]').forEach(btn => {
                btn.addEventListener('click', function() {
                    console.log('>>> FONT SIZE CLICKED:', this.dataset.size);
                    
                    document.querySelectorAll('[data-size]').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Remove all font size classes
                    document.body.className = document.body.className.replace(/font-size-\w+/g, '');
                    // Add selected font size class
                    const sizeClass = 'font-size-' + this.dataset.size;
                    document.body.classList.add(sizeClass);

                    console.log('>>> Font size class applied:', sizeClass);
                    console.log('>>> Body classes:', document.body.className);

                    // Save to localStorage
                    localStorage.setItem('accessibility-font-size', this.dataset.size);
                });
            });

            // Contrast controls
            document.querySelectorAll('[data-contrast]').forEach(btn => {
                btn.addEventListener('click', function() {
                    console.log('>>> CONTRAST CLICKED:', this.dataset.contrast);
                    
                    document.querySelectorAll('[data-contrast]').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Remove all contrast classes
                    document.body.className = document.body.className.replace(/contrast-\w+/g, '');
                    // Add selected contrast class
                    const contrastClass = 'contrast-' + this.dataset.contrast;
                    document.body.classList.add(contrastClass);

                    console.log('>>> Contrast class applied:', contrastClass);
                    console.log('>>> Body classes:', document.body.className);

                    // Save to localStorage
                    localStorage.setItem('accessibility-contrast', this.dataset.contrast);
                });
            });

            // Additional settings
            const reduceMotion = document.getElementById('reduceMotion');
            const highlightFocus = document.getElementById('highlightFocus');
            const simplifyLayout = document.getElementById('simplifyLayout');

            if (reduceMotion) {
                reduceMotion.addEventListener('change', function() {
                    if (this.checked) {
                        document.body.classList.add('reduce-motion');
                    } else {
                        document.body.classList.remove('reduce-motion');
                    }
                    localStorage.setItem('accessibility-reduce-motion', this.checked);
                });
            }

            if (highlightFocus) {
                highlightFocus.addEventListener('change', function() {
                    if (this.checked) {
                        document.body.classList.remove('no-focus-outline');
                    } else {
                        document.body.classList.add('no-focus-outline');
                    }
                    localStorage.setItem('accessibility-highlight-focus', this.checked);
                });
            }

            if (simplifyLayout) {
                simplifyLayout.addEventListener('change', function() {
                    if (this.checked) {
                        document.body.classList.add('simplified-layout');
                    } else {
                        document.body.classList.remove('simplified-layout');
                    }
                    localStorage.setItem('accessibility-simplify-layout', this.checked);
                });
            }

            // Load saved settings
            loadSavedSettings();

            // Global functions for quick actions
            window.speakPageTitle = function() {
                if ('speechSynthesis' in window) {
                    const utterance = new SpeechSynthesisUtterance(document.title);
                    window.speechSynthesis.speak(utterance);
                } else {
                    alert('Text-to-speech is not supported in your browser.');
                }
            };

            window.resetAccessibility = function() {
                // Reset font size
                setFontSize('medium');
                // Reset contrast
                setContrast('normal');
                // Reset checkboxes
                if (reduceMotion) reduceMotion.checked = false;
                if (highlightFocus) highlightFocus.checked = true;
                if (simplifyLayout) simplifyLayout.checked = false;

                // Remove all accessibility classes
                document.body.classList.remove('dyslexia-font', 'motor-friendly', 'reduce-motion', 'simplified-layout', 'no-focus-outline');
                document.body.className = document.body.className.replace(/font-size-\w+/g, '');
                document.body.className = document.body.className.replace(/contrast-\w+/g, '');

                // Clear localStorage
                localStorage.removeItem('accessibility-font-size');
                localStorage.removeItem('accessibility-contrast');
                localStorage.removeItem('accessibility-reduce-motion');
                localStorage.removeItem('accessibility-highlight-focus');
                localStorage.removeItem('accessibility-simplify-layout');
            };

            window.printPage = function() {
                window.print();
            };

            function setFontSize(size) {
                document.querySelectorAll('[data-size]').forEach(b => b.classList.remove('active'));
                const targetBtn = document.querySelector(`[data-size="${size}"]`);
                if (targetBtn) {
                    targetBtn.classList.add('active');
                    document.body.className = document.body.className.replace(/font-size-\w+/g, '');
                    document.body.classList.add('font-size-' + size);
                }
            }

            function setContrast(contrast) {
                document.querySelectorAll('[data-contrast]').forEach(b => b.classList.remove('active'));
                const targetBtn = document.querySelector(`[data-contrast="${contrast}"]`);
                if (targetBtn) {
                    targetBtn.classList.add('active');
                    document.body.className = document.body.className.replace(/contrast-\w+/g, '');
                    document.body.classList.add('contrast-' + contrast);
                }
            }

            function loadSavedSettings() {
                const fontSize = localStorage.getItem('accessibility-font-size');
                const contrast = localStorage.getItem('accessibility-contrast');
                const reduceMotion = localStorage.getItem('accessibility-reduce-motion') === 'true';
                const highlightFocus = localStorage.getItem('accessibility-highlight-focus') !== 'false';
                const simplifyLayout = localStorage.getItem('accessibility-simplify-layout') === 'true';

                console.log('>>> Loading saved settings...');
                console.log('  Font size:', fontSize || 'default');
                console.log('  Contrast:', contrast || 'default');

                if (fontSize) setFontSize(fontSize);
                if (contrast) setContrast(contrast);
                if (reduceMotion) {
                    document.getElementById('reduceMotion').checked = true;
                    document.body.classList.add('reduce-motion');
                }
                if (!highlightFocus) {
                    document.getElementById('highlightFocus').checked = false;
                    document.body.classList.add('no-focus-outline');
                }
                if (simplifyLayout) {
                    document.getElementById('simplifyLayout').checked = true;
                    document.body.classList.add('simplified-layout');
                }
            }

            // Summary
            console.log('===========================================');
            console.log('LAYOUT ACCESSIBILITY WIDGET SUMMARY:');
            console.log('Language buttons:', document.querySelectorAll('.language-btn').length);
            console.log('Font size buttons:', document.querySelectorAll('[data-size]').length);
            console.log('Contrast buttons:', document.querySelectorAll('[data-contrast]').length);
            console.log('===========================================');
            console.log('✓ Layout accessibility widget READY!');
        });
    </script>

    @yield('scripts')
</body>
</html>
