<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PWD System for Alaminos City - Helping Persons with Disabilities find jobs and training opportunities">
    <title>@yield('title', 'PWD System - Alaminos City')</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    @vite(['resources/sass/app.scss', 'resources/css/app.css'])

    @yield('styles')
</head>

<body class="{{ App\Http\Controllers\Accessibility\AccessibilityController::getBodyClasses() }} @auth @if(auth()->user()->isPwd()) pwd-user @endif @endauth">
    <!-- Skip to main content for screen readers -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Screen Reader Announcer -->
    <div id="sr-announcer" class="sr-only" aria-live="polite" aria-atomic="true"></div>

    <!-- Clean Accessibility Toggle Button -->
    <button class="accessibility-toggle" id="accessibilityToggle" aria-label="Open accessibility settings">
        <i class="fas fa-universal-access"></i>
    </button>

    {{-- PWD Sidebar --}}
    @auth
        @if(auth()->user()->isPwd())
            @include('layouts.pwd-sidebar')
        @endif
    @endauth

    <!-- Organized Accessibility Panel -->
    <div class="accessibility-panel" id="accessibilityPanel">
        <div class="panel-header">
            <div>
                <h4 class="mb-0"><i class="fas fa-universal-access me-2"></i>Accessibility</h4>
                <small class="opacity-75">Customize your viewing experience</small>
            </div>
            <button type="button" class="btn-close btn-close-white" id="closeAccessibilityPanel" aria-label="Close accessibility panel"></button>
        </div>

        <div class="panel-body">
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

            <!-- Language Settings -->
            <div class="setting-group">
                <h6><i class="fas fa-globe me-2"></i>Language</h6>
                <div id="google_translate_element"></div>
            </div>
        </div>
    </div>

    <!-- Navigation - Only show for non-PWD users and guests -->
    @auth
        @if(!auth()->user()->isPwd())
            <nav class="navbar navbar-expand-lg navbar-dark bg-primary" aria-label="Main navigation">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <i class="fas fa-universal-access me-2"></i>
                        PWD System - Alaminos City
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                                    <i class="fas fa-home me-1"></i> Home
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">
                                    <i class="fas fa-info-circle me-1"></i> About Us
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                                    <i class="fas fa-envelope me-1"></i> Contact Us
                                </a>
                            </li>
                        </ul>

                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('profile.show') }}">
                                    <i class="fas fa-user me-1"></i> Profile
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        @endif
    @else
        <!-- Guest Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary" aria-label="Main navigation">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('images/pwd_logo.png') }}" alt="PWD System" style="height:45px; width:auto;">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="fas fa-home me-1"></i> Home
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">
                                <i class="fas fa-info-circle me-1"></i> About Us
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                                <i class="fas fa-envelope me-1"></i> How it Works
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                                <i class="fas fa-envelope me-1"></i> Contact Us
                            </a>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle fa-lg"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                <li>
                                    <a class="dropdown-item" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-2"></i> Login
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-2"></i> Register
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    @endauth

  <!-- Main Content -->
<main id="main-content" role="main" class="main-content-wrapper @auth @if(auth()->user()->isPwd()) main-content-with-sidebar @endif @endauth">
    @yield('content')
</main>

    <!-- Footer -->
    <footer class="bg-dark text-light mt-5 py-4" role="contentinfo">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>PWD System - Alaminos City</h5>
                    <p>Empowering Persons with Disabilities through employment and training opportunities.</p>
                </div>
                <div class="col-md-6 text-end">
                    <p>&copy; {{ date('Y') }} Alaminos City PWD System. All rights reserved.</p>
                    <p>Designed with accessibility in mind.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Keyboard Shortcuts Modal -->
    <div class="modal fade" id="keyboardShortcutsModal" tabindex="-1" aria-labelledby="keyboardShortcutsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="keyboardShortcutsModalLabel">Keyboard Shortcuts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Navigation</strong>
                            <ul class="list-unstyled mt-2">
                                <li><kbd>Tab</kbd> - Navigate forward</li>
                                <li><kbd>Shift + Tab</kbd> - Navigate backward</li>
                                <li><kbd>Enter</kbd> - Activate button/link</li>
                                <li><kbd>Space</kbd> - Toggle checkboxes</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <strong>Accessibility</strong>
                            <ul class="list-unstyled mt-2">
                                <li><kbd>Alt + A</kbd> - Open accessibility</li>
                                <li><kbd>Alt + R</kbd> - Reset settings</li>
                                <li><kbd>Alt + S</kbd> - Skip to content</li>
                                <li><kbd>Alt + P</kbd> - Toggle sidebar</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Scripts -->
    <!-- Google Translate Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,tl',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate-element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- Custom JavaScript -->
    @vite(['resources/js/app.js'])

    @if(config('app.recaptcha.site_key'))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    @yield('scripts')

    <style>
        /* Fix for double navbar and screen fitting */
        .main-content-with-sidebar {
            margin-left: 0;
            width: 100%;
            transition: margin-left 0.3s ease;
        }

        @media (min-width: 768px) {
            .main-content-with-sidebar {
                margin-left: 250px;
                width: calc(100% - 250px);
            }
        }

        /* Ensure no horizontal scrolling */
        body {
            overflow-x: hidden;
        }

        .container, .container-fluid {
            max-width: 100%;
            padding-left: 15px;
            padding-right: 15px;
        }

        /* Remove duplicate sidebar toggle button */
        .pwd-sidebar-toggle {
            display: none;
        }

        /* Mobile menu button for sidebar */
        .mobile-menu-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1055;
            background: #3498db;
            border: none;
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        @media (min-width: 768px) {
            .mobile-menu-btn {
                display: none;
            }
        }

        /* Skip link styles */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #000;
            color: white;
            padding: 8px;
            text-decoration: none;
            z-index: 10000;
        }

        .skip-link:focus {
            top: 6px;
        }
    </style>
</body>
</html>
