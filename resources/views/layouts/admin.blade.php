<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@yield('title', 'Admin Panel - PWD System')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Custom styles -->
    <style>
        :root {
            --sidebar-width: 280px;
            --sidebar-bg: linear-gradient(180deg, #2c3e50 0%, #3498db 100%);
            --sidebar-color: white;
            --header-height: 70px;
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: 1px solid rgba(255,255,255,0.1);
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

            .sidebar-overlay.mobile-open {
                display: block;
            }
        }

        /* Card Hover Effects */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid #dee2e6;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
        }

        /* Badge Styles */
        .badge {
            font-size: 0.7em;
            padding: 4px 8px;
        }

        /* Content Container */
        .content-container {
            padding: 30px;
        }

        @media (max-width: 768px) {
            .content-container {
                padding: 15px;
            }
        }
    </style>

    @yield('styles')
</head>

<body>
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
                <a href="{{ route('job-postings.index') }}" class="nav-link {{ request()->routeIs('job-postings.*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase"></i>
                    <span>Job Postings</span>
                </a>
                <a href="{{ route('skill-trainings.index') }}" class="nav-link {{ request()->routeIs('skill-trainings.*') ? 'active' : '' }}">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Skill Trainings</span>
                </a>
                <a href="{{ route('announcements.index') }}" class="nav-link {{ request()->routeIs('announcements.*') ? 'active' : '' }}">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
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
        @yield('content')
    </main>

    <!-- Accessibility Widget -->
    @include('partials.accessibility-widget')

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
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

            // Add active class based on current route
            const currentPath = window.location.pathname;
            const navLinks = document.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                }
            });
        });
    </script>

    @yield('scripts')
</body>
</html>
