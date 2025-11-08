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
            background: linear-gradient(135deg, var(--accessibility-primary), var(--accessibility-secondary));
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

        /* Accessibility Widget Overrides for Admin */
        .accessibility-toggle {
            z-index: 1051 !important;
        }

        .accessibility-panel {
            z-index: 1052 !important;
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
        <div class="dashboard-header">
            <div class="container-fluid py-3">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="h4 mb-0">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            @yield('page-title', 'Admin Dashboard')
                        </h1>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb justify-content-md-end mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-white">Home</a></li>
                                <li class="breadcrumb-item active text-white">@yield('page-title', 'Dashboard')</li>
                            </ol>
                        </nav>
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

    <!-- Accessibility Widget -->
    <div class="accessibility-widget">
        @php
            $currentLanguage = App\Http\Controllers\Accessibility\AccessibilityController::getCurrentLanguage();
        @endphp

        <button class="accessibility-toggle" id="accessibilityToggle" aria-label="Accessibility Options">
            <i class="fas fa-universal-access"></i>
        </button>

        <div class="accessibility-panel" id="accessibilityPanel">
            <div class="panel-header">
                <div>
                    <h4 class="mb-0"><i class="fas fa-universal-access me-2"></i>Accessibility</h4>
                    <small class="opacity-75">Customize your viewing experience</small>
                </div>
                <button type="button" class="btn-close btn-close-white" id="closeAccessibilityPanel" aria-label="Close accessibility panel"></button>
            </div>

            <div class="panel-body">
                <!-- Language Toggle -->
                <div class="setting-group">
                    <h6><i class="fas fa-language me-2"></i>Language</h6>
                    <div class="btn-group-setting">
                        <button class="btn-setting language-btn {{ $currentLanguage === 'en' ? 'active' : '' }}" data-language="en">
                            <span class="language-flag">🇺🇸</span> English
                        </button>
                        <button class="btn-setting language-btn {{ $currentLanguage === 'tl' ? 'active' : '' }}" data-language="tl">
                            <span class="language-flag">🇵🇭</span> Tagalog
                        </button>
                    </div>
                    <small class="text-muted d-block mt-2" id="currentLanguageText">
                        {{ $currentLanguage === 'en' ? 'Current language: English' : 'Kasalukuyang wika: Tagalog' }}
                    </small>
                </div>

                <!-- Text Size Settings -->
                <div class="setting-group">
                    <h6><i class="fas fa-text-height me-2"></i>Text Size</h6>
                    <div class="btn-group-setting">
                        <button class="btn-setting" data-size="small">Small</button>
                        <button class="btn-setting active" data-size="medium">Medium</button>
                        <button class="btn-setting" data-size="large">Large</button>
                        <button class="btn-setting" data-size="xlarge">X-Large</button>
                    </div>
                </div>

                <!-- Contrast Settings -->
                <div class="setting-group">
                    <h6><i class="fas fa-adjust me-2"></i>Color & Contrast</h6>
                    <div class="btn-group-setting">
                        <button class="btn-setting active" data-contrast="normal">Normal</button>
                        <button class="btn-setting" data-contrast="high">High</button>
                        <button class="btn-setting" data-contrast="very-high">Very High</button>
                    </div>
                </div>

                <!-- Quick Presets -->
                <div class="setting-group">
                    <h6><i class="fas fa-magic me-2"></i>Quick Presets</h6>
                    <div class="preset-card" data-preset="vision">
                        <div class="preset-icon">
                            <i class="fas fa-eye"></i>
                        </div>
                        <h6 class="mb-1">Low Vision</h6>
                        <small class="text-muted">Larger text, high contrast</small>
                    </div>
                    <div class="preset-card" data-preset="dyslexia">
                        <div class="preset-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h6 class="mb-1">Dyslexia Friendly</h6>
                        <small class="text-muted">OpenDyslexic font, spacing</small>
                    </div>
                    <div class="preset-card" data-preset="motor">
                        <div class="preset-icon">
                            <i class="fas fa-mouse-pointer"></i>
                        </div>
                        <h6 class="mb-1">Motor Assistance</h6>
                        <small class="text-muted">Large buttons, keyboard nav</small>
                    </div>
                </div>

                <!-- Additional Features -->
                <div class="setting-group">
                    <h6><i class="fas fa-cog me-2"></i>Additional Features</h6>
                    <div class="setting-option">
                        <span class="setting-label">Reduce Animations</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="reduceMotion">
                        </div>
                    </div>
                    <div class="setting-option">
                        <span class="setting-label">Highlight Focus</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="highlightFocus" checked>
                        </div>
                    </div>
                    <div class="setting-option">
                        <span class="setting-label">Simplify Layout</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="simplifyLayout">
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="setting-group">
                    <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                    <div class="quick-actions">
                        <button class="btn-quick-action" onclick="speakPageTitle()">
                            <i class="fas fa-volume-up mb-2"></i><br>
                            <small>Read Aloud</small>
                        </button>
                        <button class="btn-quick-action" onclick="resetAccessibility()">
                            <i class="fas fa-redo mb-2"></i><br>
                            <small>Reset All</small>
                        </button>
                        <button class="btn-quick-action" onclick="printPage()">
                            <i class="fas fa-print mb-2"></i><br>
                            <small>Print Page</small>
                        </button>
                        <button class="btn-quick-action" data-bs-toggle="modal" data-bs-target="#keyboardShortcutsModal">
                            <i class="fas fa-keyboard mb-2"></i><br>
                            <small>Shortcuts</small>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Keyboard Shortcuts Modal -->
    <div class="modal fade" id="keyboardShortcutsModal" tabindex="-1" aria-labelledby="keyboardShortcutsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="keyboardShortcutsModalLabel">Keyboard Shortcuts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="shortcut-item">
                        <kbd>Alt + A</kbd>
                        <span>Open Accessibility Panel</span>
                    </div>
                    <div class="shortcut-item">
                        <kbd>Alt + 1</kbd>
                        <span>Increase Text Size</span>
                    </div>
                    <div class="shortcut-item">
                        <kbd>Alt + 2</kbd>
                        <span>Decrease Text Size</span>
                    </div>
                    <div class="shortcut-item">
                        <kbd>Alt + C</kbd>
                        <span>Toggle Contrast</span>
                    </div>
                    <div class="shortcut-item">
                        <kbd>Alt + L</kbd>
                        <span>Toggle Language</span>
                    </div>
                    <div class="shortcut-item">
                        <kbd>Alt + R</kbd>
                        <span>Reset All Settings</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            const accessibilityToggle = document.getElementById('accessibilityToggle');
            const accessibilityPanel = document.getElementById('accessibilityPanel');
            const closeAccessibilityPanel = document.getElementById('closeAccessibilityPanel');

            if (accessibilityToggle && accessibilityPanel && closeAccessibilityPanel) {
                // Toggle panel visibility
                accessibilityToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    accessibilityPanel.classList.toggle('show');
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
                        if (data.success) {
                            // Update active state
                            document.querySelectorAll('.language-btn').forEach(b => b.classList.remove('active'));
                            this.classList.add('active');

                            // Update language text
                            document.getElementById('currentLanguageText').textContent =
                                language === 'tl' ? 'Kasalukuyang wika: Tagalog' : 'Current language: English';

                            // Reload page to apply language changes
                            setTimeout(() => {
                                window.location.reload();
                            }, 500);
                        }
                    })
                    .catch(error => console.error('Error:', error));
                });
            });

            // Font size controls
            document.querySelectorAll('[data-size]').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('[data-size]').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Remove all font size classes
                    document.body.className = document.body.className.replace(/font-size-\w+/g, '');
                    // Add selected font size class
                    document.body.classList.add('font-size-' + this.dataset.size);

                    // Save to localStorage
                    localStorage.setItem('accessibility-font-size', this.dataset.size);
                });
            });

            // Contrast controls
            document.querySelectorAll('[data-contrast]').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('[data-contrast]').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Remove all contrast classes
                    document.body.className = document.body.className.replace(/contrast-\w+/g, '');
                    // Add selected contrast class
                    document.body.classList.add('contrast-' + this.dataset.contrast);

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
        });
    </script>

    @yield('scripts')
</body>
</html>
