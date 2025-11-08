<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal Navigation</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E8B57;        /* Sea Green - Trustworthy & Calming */
--secondary-color: #1A5D34;      /* Dark Green - Stability & Growth */
--accent-color: #4CAF50;         /* Vibrant Green - Success & Positivity */
--light-color: #F0F8F0;          /* Light Mint - Clean & Accessible */
--dark-color: #1C3D2A;           /* Forest Green - Professionalism */
        }

        body {
            padding-top: 70px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }

        /* Top Navigation Bar */
        .top-navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 70px;
            background: linear-gradient(90deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1060;
            display: flex;
            align-items: center;
            padding: 0 20px;
        }

        .nav-brand {
            color: white;
            font-weight: 700;
            font-size: 1.5rem;
            margin-right: 30px;
        }

        .nav-main {
            display: flex;
            align-items: center;
            flex: 1;
            overflow-x: auto;
        }

        .nav-item {
            position: relative;
            margin-right: 10px;
        }

        .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 8px 12px;
            border-radius: 5px;
            transition: all 0.3s ease;
            min-width: 70px;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }

        .nav-link i {
            font-size: 1.2rem;
            margin-bottom: 4px;
        }

        .nav-text {
            font-size: 0.75rem;
            text-align: center;
        }

        .badge {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.6rem;
            padding: 3px 6px;
        }

        /* Right side icons */
        .nav-right {
            display: flex;
            align-items: center;
            margin-left: auto;
        }

        .nav-icon {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            color: white;
            margin-left: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            text-decoration: none;
        }

        .nav-icon:hover {
            background: rgba(255,255,255,0.15);
            border-radius: 5px;
        }

        .nav-icon i {
            font-size: 1.2rem;
            margin-bottom: 4px;
        }

        .nav-icon-text {
            font-size: 0.7rem;
            text-align: center;
        }

        .profile-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            width: 220px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 1070;
            display: none;
            padding: 10px 0;
        }

        .profile-dropdown.show {
            display: block;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: var(--dark-color);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background: var(--light-color);
        }

        .dropdown-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
            color: var(--primary-color);
        }

        .dropdown-divider {
            height: 1px;
            background: #e0e0e0;
            margin: 5px 0;
        }

        /* Content area */
        .content-area {
            padding: 20px;
            min-height: calc(100vh - 70px);
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            border: none;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            font-weight: 600;
        }

        .stats-card {
            transition: transform 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .nav-text {
                display: none;
            }

            .nav-link {
                min-width: 50px;
                padding: 8px 5px;
            }

            .nav-brand {
                font-size: 1.2rem;
                margin-right: 15px;
            }

            .nav-icon {
                width: 50px;
                height: 50px;
            }

            .nav-icon-text {
                font-size: 0.6rem;
            }
        }

        /* Accessibility */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0,0,0,0);
            border: 0;
        }

        .nav-link:focus, .nav-icon:focus {
            outline: 2px solid white;
            outline-offset: 2px;
        }
    </style>
</head>
<body>
    <!-- Skip link for keyboard users -->
    <a href="#main-content" class="sr-only sr-only-focusable" style="z-index:9999">Skip to main content</a>
    <!-- Top Navigation Bar -->
    <nav class="top-navbar">
        <div class="nav-brand">PWD Portal</div>

    <div class="nav-main" role="navigation" aria-label="Primary navigation">
            <!-- Dashboard -->
            <div class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="fas fa-tachometer-alt"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>

            <!-- Browse Jobs -->
            <div class="nav-item">
                <a href="{{ route('job-postings.public') }}" class="nav-link {{ request()->routeIs('job-postings.public*') ? 'active' : '' }}" title="Browse Jobs" @if(request()->routeIs('job-postings.public*')) aria-current="page" @endif>
                    <i class="fas fa-briefcase"></i>
                    <span class="nav-text">Jobs</span>
                    <span class="badge bg-primary" aria-hidden="true">
                        @php
                            try {
                                if (method_exists(app(\App\Models\JobPosting::class), 'scopeActive')) {
                                    $activeJobsCount = \App\Models\JobPosting::active()->count();
                                } else {
                                    $activeJobsCount = \App\Models\JobPosting::where('is_active', true)
                                        ->where('application_deadline', '>=', now())
                                        ->orWhereNull('application_deadline')
                                        ->count();
                                }
                            } catch (Exception $e) {
                                $activeJobsCount = 0;
                            }
                        @endphp
                        {{ $activeJobsCount }}
                    </span>
                    <span class="sr-only">{{ $activeJobsCount }} active job postings</span>
                </a>
            </div>

            <!-- My Applications -->
            <div class="nav-item">
                <a href="{{ route('applications.index') }}" class="nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }}" title="My Applications">
                    <i class="fas fa-clipboard-list"></i>
                    <span class="nav-text">Applications</span>
                    <span class="badge bg-info">{{ auth()->user()->jobApplications->count() }}</span>
                </a>
            </div>

            <!-- Available Trainings -->
            <div class="nav-item">
                <a href="{{ route('skill-trainings.public.index') }}" class="nav-link {{ request()->routeIs('skill-trainings.public*') ? 'active' : '' }}" title="Available Trainings">
                    <i class="fas fa-graduation-cap"></i>
                    <span class="nav-text">Trainings</span>
                    <span class="badge bg-success">
                        @php
                            try {
                                $activeTrainingsCount = \App\Models\SkillTraining::where('is_active', true)
                                    ->where('end_date', '>=', now())
                                    ->count();
                                echo $activeTrainingsCount;
                            } catch (Exception $e) {
                                echo "0";
                            }
                        @endphp
                    </span>
                </a>
            </div>
        </div>

            <!-- Right side icons (accessibility toggles + user tools) -->
        <div class="nav-right" role="region" aria-label="User tools">
            <!-- Accessibility toggles removed (dashboard provides the PWD accessibility widget) -->
            <!-- Notifications -->
            <div class="nav-item">
                <a href="{{ route('notifications.index') }}" class="nav-icon" title="Notifications" aria-label="Notifications" @if(auth()->user()->unreadNotifications->count() > 0) aria-current="true" @endif>
                    <i class="fas fa-bell" aria-hidden="true"></i>
                    <span class="nav-icon-text">Notifications</span>
                    @php $unread = auth()->user()->unreadNotifications->count(); @endphp
                    @if($unread > 0)
                        <span class="badge bg-danger" aria-hidden="true">{{ $unread }}</span>
                        <span class="visually-hidden">You have {{ $unread }} unread notifications</span>
                    @else
                        <span class="visually-hidden">No unread notifications</span>
                    @endif
                    <span id="notifications-status" role="status" aria-live="polite" class="sr-only"></span>
                </a>
            </div>

            <!-- Documents -->
            <div class="nav-item">
                <a href="{{ route('documents.index') }}" class="nav-icon" title="My Documents" aria-label="My Documents">
                    <i class="fas fa-folder" aria-hidden="true"></i>
                    <span class="nav-icon-text">Documents</span>
                    <span class="badge bg-secondary" aria-hidden="true">{{ auth()->user()->documents->count() }}</span>
                    <span class="sr-only">{{ auth()->user()->documents->count() }} documents</span>
                </a>
            </div>

            <!-- My Enrollments -->
            <div class="nav-item">
                <a href="{{ route('enrollments.index') }}" class="nav-icon" title="My Enrollments">
                    <i class="fas fa-bookmark"></i>
                    <span class="nav-icon-text">Enrollments</span>
                    <span class="badge bg-warning">{{ auth()->user()->trainingEnrollments->count() }}</span>
                    <span class="sr-only">Enrollments</span>
                </a>
            </div>

            <!-- Profile -->
            <div class="nav-item">
                <div class="nav-icon" id="profileIcon" title="Profile" style="cursor: pointer;">
                    @if(auth()->user()->pwdProfile && auth()->user()->pwdProfile->profile_photo)
                        <img src="{{ Storage::url(auth()->user()->pwdProfile->profile_photo) }}" alt="Profile Photo" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover;">
                    @else
                        <i class="fas fa-user"></i>
                    @endif
                    <span class="nav-icon-text">Profile</span>
                    <span class="sr-only">Profile</span>
                </div>

                <!-- Profile Dropdown -->
                <div class="profile-dropdown" id="profileDropdown" role="menu" aria-label="Profile menu">
                    <a href="{{ route('profile.show') }}" class="dropdown-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                        <i class="fas fa-user"></i>
                        <span>My Profile</span>
                    </a>

                    <a href="{{ route('profile.edit') }}" class="dropdown-item {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>Edit Profile</span>
                    </a>

                    @if(!auth()->user()->hasPwdProfile() || !auth()->user()->isProfileComplete())
                        <a href="{{ route('profile.pwd-complete-form') }}" class="dropdown-item {{ request()->routeIs('profile.pwd-complete*') ? 'active' : '' }}">
                            <i class="fas fa-clipboard-check"></i>
                            <span>Complete PWD Profile</span>
                            <span class="badge bg-danger ms-auto">!</span>
                        </a>
                    @endif

                    <div class="dropdown-divider"></div>

                    <a href="{{ route('accessibility.settings') }}" class="dropdown-item {{ request()->routeIs('accessibility.*') ? 'active' : '' }}">
                        <i class="fas fa-universal-access"></i>
                        <span>Accessibility</span>
                    </a>

                    <a href="{{ route('contact') }}" class="dropdown-item {{ request()->routeIs('contact') ? 'active' : '' }}">
                        <i class="fas fa-headset"></i>
                        <span>Contact Support</span>
                    </a>

                    <div class="dropdown-divider"></div>

                    <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" role="menuitem">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>



    <!-- Logout Form -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        // Safe DOM helpers
        const el = id => document.getElementById(id);

        // Toggle profile dropdown with click and keyboard (Enter/Space)
        const profileIcon = el('profileIcon');
        const profileDropdown = el('profileDropdown');
        if (profileIcon && profileDropdown) {
            profileIcon.setAttribute('tabindex', '0');
            profileIcon.setAttribute('role', 'button');
            profileIcon.addEventListener('click', function(e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('show');
            });
            profileIcon.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    profileDropdown.classList.toggle('show');
                }
                if (e.key === 'Escape') {
                    profileDropdown.classList.remove('show');
                }
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (profileDropdown && !profileDropdown.contains(e.target) && profileIcon && !profileIcon.contains(e.target)) {
                profileDropdown.classList.remove('show');
            }
        });

        // Close dropdown when a dropdown item is clicked (for mobile)
        document.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', function() {
                if (profileDropdown) profileDropdown.classList.remove('show');
            });
        });

        // Accessibility toggles: high contrast and large text
        const toggleContrast = el('toggle-contrast');
        const toggleText = el('toggle-text');

        const applyPreference = () => {
            if (localStorage.getItem('pwd_high_contrast') === '1') document.body.classList.add('high-contrast'); else document.body.classList.remove('high-contrast');
            if (localStorage.getItem('pwd_large_text') === '1') document.body.classList.add('large-text'); else document.body.classList.remove('large-text');
            if (toggleContrast) toggleContrast.setAttribute('aria-pressed', localStorage.getItem('pwd_high_contrast') === '1' ? 'true' : 'false');
            if (toggleText) toggleText.setAttribute('aria-pressed', localStorage.getItem('pwd_large_text') === '1' ? 'true' : 'false');
        };

        if (toggleContrast) {
            toggleContrast.addEventListener('click', function(e) {
                const v = localStorage.getItem('pwd_high_contrast') === '1' ? '0' : '1';
                localStorage.setItem('pwd_high_contrast', v);
                applyPreference();
            });
        }

        if (toggleText) {
            toggleText.addEventListener('click', function(e) {
                const v = localStorage.getItem('pwd_large_text') === '1' ? '0' : '1';
                localStorage.setItem('pwd_large_text', v);
                applyPreference();
            });
        }

        // Apply preferences on load
        applyPreference();
    </script>
    <style>
        /* High contrast and large text helpers */
        .high-contrast { background-color: #000 !important; color: #fff !important; }
        .high-contrast .top-navbar { background: #000 !important; }
        .high-contrast .nav-link, .high-contrast .nav-icon-text { color: #fff !important; }
        .large-text { font-size: 1.125rem; }
        .nav-link:focus, .nav-icon:focus, .nav-icon button:focus, .dropdown-item:focus { outline: 3px solid #fff; outline-offset: 2px; }
        .visually-hidden { position: absolute !important; height: 1px; width: 1px; overflow: hidden; clip: rect(1px, 1px, 1px, 1px); white-space: nowrap; }
    </style>
</body>
</html>
