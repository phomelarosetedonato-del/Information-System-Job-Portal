<div class="pwd-sidebar">
    <div class="sidebar-header">
        <div class="user-info">
            <div class="user-avatar">
                @if(auth()->user()->pwdProfile && auth()->user()->pwdProfile->profile_photo)
                    <img src="{{ Storage::url(auth()->user()->pwdProfile->profile_photo) }}" alt="Profile Photo" class="avatar-img">
                @else
                    <div class="avatar-placeholder">
                        <i class="fas fa-user"></i>
                    </div>
                @endif
            </div>
            <div class="user-details">
                <h6 class="mb-0">{{ auth()->user()->name }}</h6>
                <small class="text-muted">PWD User</small>
            </div>
        </div>
        <button class="sidebar-toggle d-md-none" onclick="toggleSidebar()">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <!-- Dashboard -->
        <div class="nav-section">
            <h6 class="section-title">MAIN</h6>
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Job Opportunities -->
        <div class="nav-section">
            <h6 class="section-title">JOB OPPORTUNITIES</h6>
            <a href="{{ route('job-postings.public') }}" class="nav-link {{ request()->routeIs('job-postings.public*') ? 'active' : '' }}">
                <i class="fas fa-briefcase me-2"></i>
                <span>Browse Jobs</span>
                <span class="badge bg-primary ms-auto">
                    @php
                        try {
                            // Use the active scope if available, otherwise use the direct query
                            if (method_exists(app(\App\Models\JobPosting::class), 'scopeActive')) {
                                $activeJobsCount = \App\Models\JobPosting::active()->count();
                            } else {
                                $activeJobsCount = \App\Models\JobPosting::where('is_active', true)
                                    ->where('application_deadline', '>=', now())
                                    ->count();
                            }
                            echo $activeJobsCount;
                        } catch (Exception $e) {
                            echo "0";
                        }
                    @endphp
                </span>
            </a>

            <a href="{{ route('applications.index') }}" class="nav-link {{ request()->routeIs('applications.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list me-2"></i>
                <span>My Applications</span>
                <span class="badge bg-info ms-auto">{{ auth()->user()->jobApplications->count() }}</span>
            </a>
        </div>

        <!-- Training & Skills -->
        <div class="nav-section">
            <h6 class="section-title">TRAINING & SKILLS</h6>
            <a href="{{ route('skill-trainings.public') }}" class="nav-link {{ request()->routeIs('skill-trainings.public*') ? 'active' : '' }}">
                <i class="fas fa-graduation-cap me-2"></i>
                <span>Available Trainings</span>
                <span class="badge bg-success ms-auto">
                    @php
                        try {
                            $activeTrainingsCount = \App\Models\SkillTraining::where('is_active', true)
                                ->where('start_date', '>=', now())
                                ->count();
                            echo $activeTrainingsCount;
                        } catch (Exception $e) {
                            echo "0";
                        }
                    @endphp
                </span>
            </a>

            <a href="{{ route('enrollments.index') }}" class="nav-link {{ request()->routeIs('enrollments.*') ? 'active' : '' }}">
                <i class="fas fa-bookmark me-2"></i>
                <span>My Enrollments</span>
                <span class="badge bg-warning ms-auto">{{ auth()->user()->trainingEnrollments->count() }}</span>
            </a>
        </div>

        <!-- Documents -->
        <div class="nav-section">
            <h6 class="section-title">DOCUMENTS</h6>
            <a href="{{ route('documents.index') }}" class="nav-link {{ request()->routeIs('documents.*') ? 'active' : '' }}">
                <i class="fas fa-folder me-2"></i>
                <span>My Documents</span>
                <span class="badge bg-secondary ms-auto">{{ auth()->user()->documents->count() }}</span>
            </a>

            <a href="{{ route('documents.create') }}" class="nav-link {{ request()->routeIs('documents.create') ? 'active' : '' }}">
                <i class="fas fa-upload me-2"></i>
                <span>Upload Documents</span>
            </a>
        </div>

        <!-- Profile & Settings -->
        <div class="nav-section">
            <h6 class="section-title">PROFILE</h6>
            <a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                <i class="fas fa-user me-2"></i>
                <span>My Profile</span>
            </a>

            <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="fas fa-cog me-2"></i>
                <span>Edit Profile</span>
            </a>

            @if(!auth()->user()->hasPwdProfile() || !auth()->user()->isProfileComplete())
                <a href="{{ route('profile.pwd-complete-form') }}" class="nav-link {{ request()->routeIs('profile.pwd-complete*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check me-2"></i>
                    <span>Complete PWD Profile</span>
                    <span class="badge bg-danger ms-auto">!</span>
                </a>
            @endif
        </div>

        <!-- Support -->
        <div class="nav-section">
            <h6 class="section-title">SUPPORT</h6>
            <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                <i class="fas fa-bell me-2"></i>
                <span>Notifications</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-danger ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>

            <a href="{{ route('accessibility.settings') }}" class="nav-link {{ request()->routeIs('accessibility.*') ? 'active' : '' }}">
                <i class="fas fa-universal-access me-2"></i>
                <span>Accessibility</span>
            </a>

            <a href="{{ route('contact') }}" class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}">
                <i class="fas fa-headset me-2"></i>
                <span>Contact Support</span>
            </a>
        </div>

        <!-- Logout Section -->
        <div class="nav-section mt-auto">
            <div class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="cursor: pointer;">
                <i class="fas fa-sign-out-alt me-2"></i>
                <span>Logout</span>
            </div>
        </div>
    </nav>
</div>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay d-md-none" onclick="toggleSidebar()"></div>

<!-- Mobile Menu Button -->
<button class="mobile-menu-btn d-md-none" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

<style>
.pwd-sidebar {
    position: fixed;
    left: 0;
    top: 0;
    height: 100vh;
    width: 250px;
    background: linear-gradient(180deg, #2c3e50 0%, #3498db 100%);
    color: white;
    z-index: 1060;
    overflow-y: auto;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    transform: translateX(-100%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
}

@media (min-width: 768px) {
    .pwd-sidebar {
        transform: translateX(0);
    }
}

.pwd-sidebar.mobile-open {
    transform: translateX(0);
}

.sidebar-header {
    padding: 20px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    background: rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
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
}

.sidebar-toggle {
    background: none;
    border: none;
    color: white;
    font-size: 1.2rem;
    cursor: pointer;
}

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
}

.nav-link:hover {
    background: rgba(255,255,255,0.1);
    color: white;
    border-left-color: #3498db;
}

.nav-link.active {
    background: rgba(255,255,255,0.15);
    color: white;
    border-left-color: #e74c3c;
    font-weight: 600;
}

.nav-link.text-danger:hover {
    background: rgba(231, 76, 60, 0.2);
    color: #e74c3c !important;
    border-left-color: #e74c3c;
}

.nav-link i {
    width: 20px;
    text-align: center;
}

.badge {
    font-size: 0.7em;
    padding: 4px 8px;
}

/* Mobile menu button */
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

/* Sidebar overlay */
.sidebar-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1055;
    display: none;
}

.sidebar-overlay.mobile-open {
    display: block;
}
</style>

<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.pwd-sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar.classList.toggle('mobile-open');
    overlay.classList.toggle('mobile-open');
}

// Close sidebar when clicking on a link in mobile view
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        });
    });

    // Close sidebar when pressing escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const sidebar = document.querySelector('.pwd-sidebar');
            if (sidebar.classList.contains('mobile-open')) {
                toggleSidebar();
            }
        }
    });
});
</script>
