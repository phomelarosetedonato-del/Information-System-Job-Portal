<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="PWD System for Alaminos City - Helping Persons with Disabilities find jobs and training opportunities">
    <title>@yield('title', 'PWD System - Alaminos City')</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    @vite(['resources/sass/app.scss', 'resources/css/app.css', 'resources/css/nav.css','resources/css/high-contrast.css' ])

    @yield('styles')

    <style>
        /* Navigation Styles */
        .navbar-custom {
            background-color: #2c3e50 !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .navbar-custom .navbar-brand {
            font-weight: 600;
            color: #ecf0f1 !important;
            display: flex;
            align-items: center;
        }

        .navbar-custom .navbar-brand img {
            height: 45px;
            width: auto;
        }

        .navbar-custom .nav-link {
            color: #bdc3c7 !important;
            font-weight: 500;
            padding: 0.5rem 1rem;
            margin: 0 0.1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            position: relative;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link:focus {
            color: #ecf0f1 !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .navbar-custom .nav-link.active {
            color: #ecf0f1 !important;
            background-color: rgba(52, 152, 219, 0.2);
        }

        .navbar-custom .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 15%;
            width: 70%;
            height: 2px;
            background-color: #3498db;
        }

        .navbar-custom .dropdown-menu {
            background-color: #34495e;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .dropdown-item {
            color: #bdc3c7;
            transition: all 0.2s ease;
        }

        .navbar-custom .dropdown-item:hover {
            color: #ecf0f1;
            background-color: rgba(52, 152, 219, 0.2);
        }

        .navbar-toggler {
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.25);
        }

       /* Main Content Spacing */
.main-content-wrapper {
    min-height: calc(100vh - 160px);
    padding-top: 20px;
    margin-left: 0;
    width: 100%;
}

.main-content-with-sidebar {
    margin-left: 0;
    width: 100%;
    transition: margin-left 0.3s ease;
}

@media (min-width: 768px) {
    .main-content-with-sidebar {
        margin-left: 0; /* Remove the 250px left margin */
        width: 100%; /* Use full width */
    }
}
        /* Footer Styles */
        .footer-custom {
            background: linear-gradient(90deg, #1A5D34 0%, #2E8B57 100%) !important;
            color: #ffffff;
            padding-top: 2rem;
            padding-bottom: 2rem;
            margin-top: 3rem;
        }

        .footer-custom h5 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .footer-custom p {
            color: rgba(255, 255, 255, 0.9);
        }

        /* Accessibility Toggle Button */
        .accessibility-toggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .accessibility-toggle:hover {
            background-color: #2980b9;
            transform: scale(1.05);
        }

        /* Skip link styles */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: #2c3e50;
            color: white;
            padding: 8px 12px;
            text-decoration: none;
            z-index: 10000;
            border-radius: 0 0 4px 4px;
            font-weight: 500;
        }

        .skip-link:focus {
            top: 0;
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
    </style>
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
            <nav class="navbar navbar-expand-lg navbar-custom fixed-top" aria-label="Main navigation">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <i class="fas fa-universal-access me-2"></i>
                        PWD System - Alaminos City
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
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
<nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm" aria-label="Main navigation">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('images/pwd_logo.jpg') }}" alt="PWD System">

        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Centered Navigation Links -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                       href="{{ route('home') }}">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('about') ? 'active' : '' }}"
                       href="{{ route('about') }}">
                        <i class="fas fa-info-circle me-2"></i>About Us
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}"
                       href="{{ route('contact') }}">
                        <i class="fas fa-envelope me-2"></i>Contact Us
                    </a>
                </li>
            </ul>

            <!-- Right Side - Register/Login -->
            <ul class="navbar-nav ms-auto flex flex-row space-x-3 align-items-center">
                <li class="nav-item">
                    <a class="btn btn-sm btn-nude-outline px-4 py-2"
                       href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-2"></i>Register
                    </a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-sm btn-nude-solid px-4 py-2"
                       href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-2"></i>Login
                    </a>
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
    <footer class="footer-custom mt-5 py-4" role="contentinfo">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>PWD System - Alaminos City</h5>
                    <p>Empowering Persons with Disabilities through employment and training opportunities.</p>
                </div>
                <div class="col-md-6 text-md-end">
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
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <!-- Custom JavaScript -->
    @vite(['resources/js/app.js'])

    @if(config('app.recaptcha.site_key'))
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif

    @yield('scripts')

    {{-- Navigation Language Switcher Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Page loaded - Initializing language switcher');

        // Get current language from localStorage or default to 'en'
        let currentLang = localStorage.getItem('site-language') || 'en';
        console.log('📌 Current language from localStorage:', currentLang);

        updateLanguageDisplay(currentLang);

        // Handle language switch clicks
        const languageButtons = document.querySelectorAll('.language-switch-btn');
        console.log('🔍 Found', languageButtons.length, 'language switch buttons');

        languageButtons.forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const targetLang = this.dataset.lang;

                console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                console.log('🖱️ LANGUAGE BUTTON CLICKED');
                console.log('   Target:', targetLang);
                console.log('   Current:', currentLang);

                if (targetLang === currentLang) {
                    console.log('📌 Already in', targetLang, '- skipping');
                    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                    return;
                }

                console.log('🌐 Starting language switch from', currentLang, 'to', targetLang);

                // Show loading state
                const navLangText = document.getElementById('currentLanguageNav');
                const originalText = navLangText ? navLangText.textContent : '';
                if (navLangText) {
                    navLangText.textContent = targetLang === 'tl' ? 'Lumilipat...' : 'Switching...';
                    console.log('⏳ Loading indicator shown');
                }

                try {
                    // Save preference via API
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    console.log('🔐 CSRF Token:', csrfToken ? csrfToken.substring(0, 15) + '...' : '❌ NOT FOUND');

                    if (csrfToken) {
                        console.log('📤 Saving language preference to server...');
                        const saveResponse = await fetch('/accessibility/quick-tool', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ language: targetLang })
                        });
                        console.log('📥 Save response:', saveResponse.status, saveResponse.ok ? '✅' : '❌');

                        // Check if response was successful
                        if (saveResponse.ok) {
                            const saveData = await saveResponse.json();
                            console.log('💾 Server save data:', saveData);
                        } else {
                            const errorText = await saveResponse.text();
                            console.error('❌ Server save failed:', errorText);
                        }
                    } else {
                        console.warn('⚠️ No CSRF token - skipping server save');
                    }

                    // Update localStorage
                    localStorage.setItem('site-language', targetLang);
                    console.log('💾 Language saved to localStorage');
                    currentLang = targetLang;

                    // Update display
                    updateLanguageDisplay(targetLang);
                    console.log('🎨 Display updated');

                    // Translate all elements with data-translate attribute
                    console.log('🔄 Starting translation process...');
                    await translatePage(targetLang);

                    console.log('✅✅✅ LANGUAGE SWITCH COMPLETE ✅✅✅');
                    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

                } catch (error) {
                    console.error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                    console.error('❌❌❌ ERROR SWITCHING LANGUAGE ❌❌❌');
                    console.error('Error:', error.message);
                    console.error('Stack:', error.stack);
                    console.error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                    if (navLangText) {
                        navLangText.textContent = originalText;
                    }
                    alert('Failed to switch language. Please check console and try again.');
                }
            });
        });

        // Function to update language display
        function updateLanguageDisplay(lang) {
            console.log('🎨 updateLanguageDisplay called with:', lang);
            const navLangText = document.getElementById('currentLanguageNav');
            if (navLangText) {
                navLangText.textContent = lang === 'tl' ? 'Tagalog' : 'English';
                console.log('✅ Display text set to:', navLangText.textContent);
            } else {
                console.error('❌ Element #currentLanguageNav not found!');
            }
        }

        // Function to translate all page content
        async function translatePage(targetLang) {
            console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
            console.log('🔍 translatePage() STARTED');
            console.log('   Target Language:', targetLang);
            console.log('   Page URL:', window.location.href);

            const elementsToTranslate = document.querySelectorAll('[data-translate]');

            console.log('🔎 Searching for [data-translate] elements...');
            console.log('   Found:', elementsToTranslate.length, 'elements');

            if (elementsToTranslate.length === 0) {
                console.warn('⚠️⚠️⚠️ NO TRANSLATABLE ELEMENTS FOUND!');
                console.warn('� Add data-translate="key" to HTML elements');
                console.warn('Example: <span data-translate="home">Home</span>');
                return;
            }

            // Log each element found
            console.log('� Translatable elements list:');
            elementsToTranslate.forEach((el, i) => {
                const key = el.dataset.translate;
                const text = el.textContent.trim().substring(0, 30);
                console.log(`   ${i+1}. [${key}] = "${text}${text.length === 30 ? '...' : ''}"`);
            });

            // Store original English text and collect translation keys
            const textsToTranslate = [];
            const translationMap = {};

            elementsToTranslate.forEach(el => {
                // Store original English text first time only
                if (!el.dataset.originalText) {
                    el.dataset.originalText = el.textContent.trim();
                    console.log('💾 Stored original:', el.dataset.originalText.substring(0, 20) + '...');
                }

                const key = el.dataset.translate;
                if (key) {
                    if (!translationMap[key]) {
                        translationMap[key] = [];
                        textsToTranslate.push(key);
                    }
                    translationMap[key].push(el);
                }
            });

            console.log('🔑 Unique translation keys:', textsToTranslate);
            console.log('📊 Total unique keys:', textsToTranslate.length);

            if (targetLang === 'en') {
                // Restore original English
                console.log('🔙 Restoring to English...');
                let restoredCount = 0;
                elementsToTranslate.forEach(el => {
                    if (el.dataset.originalText) {
                        const oldText = el.textContent;
                        el.textContent = el.dataset.originalText;
                        console.log(`   ✅ "${oldText.substring(0,20)}..." → "${el.textContent.substring(0,20)}..."`);
                        restoredCount++;
                    }
                });
                console.log('✅ Restored', restoredCount, 'elements to English');
                console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                return;
            }

            // Fetch translations from API
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('🔐 CSRF Token check:', csrfToken ? '✅ Found' : '❌ NOT FOUND');
                if (csrfToken) {
                    console.log('   Token preview:', csrfToken.substring(0, 15) + '...');
                }

                if (!csrfToken) {
                    console.error('❌ CRITICAL: No CSRF token found!');
                    console.error('💡 Solution: Add <meta name="csrf-token" content="{{ csrf_token() }}"> to <head>');
                    alert('Error: CSRF token not found. Please refresh the page.');
                    return;
                }

                console.log('📤 Preparing API request...');
                console.log('   Endpoint: /accessibility/translate-batch');
                console.log('   Method: POST');
                console.log('   Keys to translate:', textsToTranslate.length);

                const requestBody = {
                    texts: textsToTranslate,
                    target_lang: targetLang
                };
                console.log('   Request body:', JSON.stringify(requestBody, null, 2));

                const response = await fetch('/accessibility/translate-batch', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
                });

                console.log('📥 API Response received:');
                console.log('   Status:', response.status, response.statusText);
                console.log('   OK:', response.ok ? '✅' : '❌');

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('❌ API Error Response:', errorText);
                    throw new Error(`API returned ${response.status}: ${errorText}`);
                }

                const data = await response.json();
                console.log('📦 Translation data:', JSON.stringify(data, null, 2));

                if (data.success && data.translations) {
                    console.log('✅ Valid response format');
                    console.log('📝 Applying translations...');

                    let successCount = 0;
                    let failCount = 0;
                    Object.keys(translationMap).forEach(key => {
                        const translated = data.translations[key];
                        if (translated && translated !== key) {
                            console.log(`   ✅ [${key}] → "${translated}"`);
                            translationMap[key].forEach(el => {
                                const oldText = el.textContent.trim();
                                el.textContent = translated;
                                successCount++;
                                console.log(`      📝 Element updated: "${oldText.substring(0,20)}..." → "${translated.substring(0,20)}..."`);
                            });
                        } else {
                            console.warn(`   ⚠️ [${key}] - NO TRANSLATION or SAME AS KEY`);
                            failCount++;
                        }
                    });

                    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                    console.log('✅✅✅ TRANSLATION COMPLETE ✅✅✅');
                    console.log('   ✅ Successful:', successCount, 'elements');
                    if (failCount > 0) {
                        console.warn('   ⚠️ Missing:', failCount, 'translations');
                    }
                    console.log('   📊 Total:', successCount + failCount, 'keys processed');
                    console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

                } else {
                    console.error('❌ Invalid API response format!');
                    console.error('   Expected: {success: true, translations: {...}}');
                    console.error('   Received:', data);
                    throw new Error('Invalid response format from API');
                }
            } catch (error) {
                console.error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                console.error('❌❌❌ TRANSLATION ERROR ❌❌❌');
                console.error('   Message:', error.message);
                console.error('   Stack:', error.stack);
                console.error('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
                alert('❌ Translation failed!\n\nError: ' + error.message + '\n\nPlease check browser console (F12) for details.');
            }
        }

        // On page load, apply saved language preference
        console.log('🔄 Checking for saved language preference...');
        if (currentLang === 'tl') {
            console.log('   📌 Tagalog preference found - auto-translating...');
            setTimeout(() => translatePage('tl'), 500); // Small delay to ensure DOM is ready
        } else {
            console.log('   📌 English (default) - no translation needed');
        }

        console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        console.log('🎉 Language Switcher Ready!');
        console.log('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
    });
    </script>

    {{-- Include Modern Accessibility Widget --}}
    @include('partials.accessibility-widget')
</body>
</html>
