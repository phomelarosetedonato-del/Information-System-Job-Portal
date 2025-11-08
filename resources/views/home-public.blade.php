@extends('layouts.app')

@section('title', 'Welcome - PWD Job Portal')

@section('content')

<!-- Hero Section -->
<section class="hero-section bg-white py-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4 text-gray-800">Unlocking Potential, Creating Opportunities</h1>
                <p class="lead mb-4 text-gray-600">
                    Connect with inclusive employers and access specialized training programs designed for Persons with Disabilities. Your next career breakthrough starts here.
                </p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-green-solid btn-lg me-3 mb-2">
                        <i class="fas fa-user-plus me-2"></i>Start Your Journey
                    </a>
                    <a href="#opportunities" class="btn btn-green-outline btn-lg mb-2">
                        <i class="fas fa-search me-2"></i>Browse Opportunities
                    </a>
                </div>
                <div class="mt-4">
                    <small class="text-gray-500">
                        <i class="fas fa-users me-1"></i>
                        Join <strong>1,500+</strong> PWD professionals who found their dream jobs
                    </small>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="hero-placeholder bg-gray-100 rounded p-5">
                    <i class="fas fa-users fa-5x text-gray-400"></i>
                    <p class="mt-3 text-gray-600">PWD Professionals Network</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="py-4 bg-gray-50">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <h3 class="text-green-600 fw-bold mb-1">{{ $featuredJobs->count() }}+</h3>
                    <p class="text-gray-600 mb-0">Active Jobs</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <h3 class="text-green-600 fw-bold mb-1">{{ $upcomingTrainings->count() }}+</h3>
                    <p class="text-gray-600 mb-0">Training Programs</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <h3 class="text-green-600 fw-bold mb-1">15+</h3>
                    <p class="text-gray-600 mb-0">Partner Companies</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-item">
                    <h3 class="text-green-600 fw-bold mb-1">98%</h3>
                    <p class="text-gray-600 mb-0">Satisfaction Rate</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Jobs -->
<section id="featured-jobs" class="py-5 bg-gray-50">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="section-title">Featured Job Opportunities</h2>
                <p class="text-gray-600">
                    Handpicked opportunities from inclusive employers
                </p>
            </div>
            <div class="col-auto">
                <a href="{{ route('login') }}" class="btn btn-green-outline">View All Jobs</a>
            </div>
        </div>

        @if($featuredJobs->count() > 0)
        <div class="row">
            @foreach($featuredJobs as $job)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card job-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge bg-green-100 text-green-800">{{ $job->employment_type ?? 'Full-time' }}</span>
                            <small class="text-gray-500">{{ $job->created_at->diffForHumans() }}</small>
                        </div>
                        <h5 class="card-title text-gray-800">{{ $job->title }}</h5>
                        <p class="company-name text-green-600 mb-2">{{ $job->company_name ?? 'Company' }}</p>
                        <div class="job-meta mb-3">
                            <small class="text-gray-600">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $job->location ?? 'Remote' }}
                            </small>
                            @if(!empty($job->salary_range))
                            <small class="text-gray-600 ms-3">
                                <i class="fas fa-money-bill-wave me-1"></i>{{ $job->salary_range }}
                            </small>
                            @endif
                        </div>
                        <p class="card-text text-gray-600 small">{{ Str::limit($job->description, 120) }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-top">
                        <a href="{{ route('login') }}" class="btn btn-green-solid w-100">Apply Now</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row">
            <div class="col-12 text-center py-4">
                <div class="empty-state">
                    <i class="fas fa-briefcase fa-3x text-gray-400 mb-3"></i>
                    <h4 class="text-gray-800">No Featured Jobs Available</h4>
                    <p class="text-gray-600">Check back later for new opportunities</p>
                    <a href="{{ route('login') }}" class="btn btn-green-outline">View All Jobs</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Training Programs -->
@if($upcomingTrainings->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="section-title">Skill Development Programs</h2>
                <p class="text-gray-600">
                    Enhance your skills with our specialized training programs
                </p>
            </div>
        </div>

        <div class="row">
            @foreach($upcomingTrainings as $training)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card training-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <span class="badge bg-green-100 text-green-800 mb-3">Enrolling Now</span>
                        <h5 class="card-title text-gray-800">{{ $training->title }}</h5>
                        <p class="text-gray-600 small">{{ Str::limit($training->description, 100) }}</p>

                        <div class="training-details">
                            @if($training->start_date)
                            <div class="detail-item mb-2">
                                <i class="fas fa-calendar text-green-600 me-2"></i>
                                <small class="text-gray-600">Starts: {{ $training->start_date->format('M d, Y') }}</small>
                            </div>
                            @endif
                            @if($training->duration)
                            <div class="detail-item mb-2">
                                <i class="fas fa-clock text-green-600 me-2"></i>
                                <small class="text-gray-600">Duration: {{ $training->duration }}</small>
                            </div>
                            @endif
                            <div class="detail-item mb-2">
                                <i class="fas fa-globe text-green-600 me-2"></i>
                                <small class="text-gray-600">Mode: {{ $training->mode ?? 'Online' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-green-600 fw-bold">
                                {{ (!empty($training->is_free) && $training->is_free) ? 'FREE' : '₱'.($training->fee ?? '0') }}
                            </span>
                            <a href="{{ route('login') }}" class="btn btn-green-solid btn-sm">Enroll Now</a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@else
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="section-title">Skill Development Programs</h2>
                <p class="text-gray-600">
                    No training programs available at the moment
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center py-4">
                <div class="empty-state">
                    <i class="fas fa-graduation-cap fa-3x text-gray-400 mb-3"></i>
                    <h4 class="text-gray-800">No Training Programs Available</h4>
                    <p class="text-gray-600">Check back later for new training opportunities</p>
                    <a href="{{ route('login') }}" class="btn btn-green-outline">View All Trainings</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Success Stories Section -->
<section class="py-5 bg-gray-50">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="section-title">Inspiring Success Stories</h2>
                <p class="text-gray-600">Real stories from PWD professionals who transformed their careers</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card story-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="story-header mb-3">
                            <div class="d-flex align-items-center">
                                <div class="story-avatar me-3">
                                    <div class="bg-green-100 text-green-800 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-gray-800">Rajesh Kumar</h6>
                                    <small class="text-gray-600">Software Developer at Tech Solutions</small>
                                </div>
                            </div>
                        </div>
                        <p class="card-text text-gray-600">"Despite my visual impairment, I found my dream job as a software developer through this platform. The inclusive employers and support system made all the difference."</p>
                        <div class="story-meta">
                            <small class="text-gray-500">
                                <i class="fas fa-chart-line me-1"></i>
                                Salary increased by 60%
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card story-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="story-header mb-3">
                            <div class="d-flex align-items-center">
                                <div class="story-avatar me-3">
                                    <div class="bg-green-100 text-green-800 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-gray-800">Priya Sharma</h6>
                                    <small class="text-gray-600">Content Writer at Creative Minds</small>
                                </div>
                            </div>
                        </div>
                        <p class="card-text text-gray-600">"As a person with mobility challenges, remote work opportunities through this portal changed my life. I now work with amazing companies from the comfort of my home."</p>
                        <div class="story-meta">
                            <small class="text-gray-500">
                                <i class="fas fa-chart-line me-1"></i>
                                Found job in 2 weeks
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card story-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="story-header mb-3">
                            <div class="d-flex align-items-center">
                                <div class="story-avatar me-3">
                                    <div class="bg-green-100 text-green-800 rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1 text-gray-800">Amit Patel</h6>
                                    <small class="text-gray-600">Customer Support Lead at ServicePro</small>
                                </div>
                            </div>
                        </div>
                        <p class="card-text text-gray-600">"The skill training programs helped me transition from manual work to a customer service role. The platform's career guidance was invaluable in my journey."</p>
                        <div class="story-meta">
                            <small class="text-gray-500">
                                <i class="fas fa-chart-line me-1"></i>
                                Career transition successful
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Partner Companies -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="section-title">Trusted by Inclusive Employers</h2>
                <p class="text-gray-600">Companies committed to diversity and inclusion</p>
            </div>
        </div>

        <div class="row">
            @foreach(['Tech Solutions', 'Creative Minds', 'ServicePro', 'Global Innovations', 'Future Systems', 'NextGen Tech'] as $company)
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="partner-logo text-center p-3 bg-white rounded shadow-sm border">
                    <div class="bg-gray-100 rounded p-3">
                        <i class="fas fa-building fa-2x text-gray-400"></i>
                    </div>
                    <small class="d-block mt-2 fw-bold text-gray-700">{{ $company }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-gray-50 border-top">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-3 text-gray-800">Ready to Start Your Journey?</h2>
                <p class="lead mb-4 text-gray-600">Join thousands of PWD professionals who have found meaningful employment through our platform.</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-green-solid btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i>Create Account
                    </a>
                    <a href="{{ route('about') }}" class="btn btn-green-outline btn-lg">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.hero-section {
    background: white;
}

.section-title {
    position: relative;
    padding-bottom: 15px;
    color: #1f2937;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 60px;
    height: 3px;
    background: #059669; /* green-600 */
}

.job-card, .story-card, .training-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.job-card:hover, .story-card:hover, .training-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
}

.partner-logo {
    transition: transform 0.3s ease;
}

.partner-logo:hover {
    transform: scale(1.05);
}

.stat-item h3 {
    font-size: 2rem;
}

.hero-placeholder {
    background: #f9fafb;
}

.empty-state {
    padding: 3rem 1rem;
}

/* Green Color Scheme */
.bg-green-50 {
    background-color: #f0fdf4;
}

.bg-green-100 {
    background-color: #dcfce7;
}

.bg-green-200 {
    background-color: #bbf7d0;
}

.text-green-600 {
    color: #059669;
}

.text-green-800 {
    color: #166534;
}

.border-green-100 {
    border-color: #dcfce7;
}

.border-green-200 {
    border-color: #bbf7d0;
}

/* Button Styles */
.btn-green-solid {
    background-color: #10b981;
    border-color: #10b981;
    color: white;
    transition: all 0.3s ease;
}

.btn-green-solid:hover {
    background-color: #059669;
    border-color: #059669;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.btn-green-outline {
    border-color: #10b981;
    color: #059669;
    transition: all 0.3s ease;
}

.btn-green-outline:hover {
    background-color: #10b981;
    border-color: #10b981;
    color: white;
    transform: translateY(-2px);
}

.btn-outline-green {
    border-color: #10b981;
    color: #059669;
}

.btn-outline-green:hover {
    background-color: #10b981;
    border-color: #10b981;
    color: white;
}

/* Custom background colors for other elements */
.bg-gray-50 {
    background-color: #f9fafb;
}

.bg-gray-100 {
    background-color: #f3f4f6;
}

.text-gray-600 {
    color: #4b5563;
}

.text-gray-700 {
    color: #374151;
}

.text-gray-800 {
    color: #1f2937;
}

.text-gray-500 {
    color: #6b7280;
}
</style>
@endsection

@section('scripts')
<script>
// Basic page interactions can be added here if needed
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection
