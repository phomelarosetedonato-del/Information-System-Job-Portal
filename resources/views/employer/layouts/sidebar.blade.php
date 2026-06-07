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
            <i class="fas fa-inbox"></i> Applications
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <i class="fas fa-chart-bar"></i> Analytics
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{ route('employer.analytics.overview') }}">Overview</a></li>
            <li><a class="dropdown-item" href="{{ route('employer.analytics.performance') }}">Performance</a></li>
            <li><a class="dropdown-item" href="{{ route('employer.analytics.jobs-performance') }}">Jobs Performance</a></li>
            <li><a class="dropdown-item" href="{{ route('employer.analytics.application-trends') }}">Application Trends</a></li>
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
    <li class="nav-item mt-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="fas fa-sign-out-alt"></i> Log Out
            </button>
        </form>
    </li>
</ul>
