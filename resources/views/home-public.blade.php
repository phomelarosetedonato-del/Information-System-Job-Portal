@extends('layouts.app')

@section('title', 'Welcome - PWD Job Portal')

@section('content')

<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); min-height: 85vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <!-- Main Badge -->
                <div class="mb-4">
                    <span class="badge bg-success bg-opacity-10 text-success px-4 py-2 rounded-pill border border-success border-opacity-25">
                        <i class="fas fa-universal-access me-2"></i>
                        <span>Empowering PWD Professionals</span>
                    </span>
                </div>

                <!-- Main Heading -->
                <h1 class="display-3 fw-bold mb-4 text-gray-900" style="line-height: 1.2;">
                    <span>Your Abilities</span>,<br>
                    <span class="text-success">Our Priority</span>
                </h1>

                <p class="lead mb-4 text-gray-700" style="font-size: 1.25rem; line-height: 1.8;">
                    <span>Connect with inclusive employers who value your unique talents. Access specialized training and find meaningful employment opportunities designed specifically for Persons with Disabilities.</span>
                </p>

                <!-- Key Features -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon-sm bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-wheelchair text-success"></i>
                        </div>
                        <span class="text-gray-700"><strong><span>Fully Accessible Platform</span></strong> - <span>Screen reader compatible, keyboard navigation</span></span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon-sm bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-handshake text-success"></i>
                        </div>
                        <span class="text-gray-700"><strong><span>PWD-Friendly Employers</span></strong> - <span>Pre-vetted inclusive companies</span></span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="feature-icon-sm bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-graduation-cap text-success"></i>
                        </div>
                        <span class="text-gray-700"><strong><span>Free Skills Training</span></strong> - <span>Enhance your employability</span></span>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="hero-buttons d-flex flex-wrap gap-3 mb-4">
                    <a href="{{ route('register') }}" class="btn btn-success btn-lg px-5 py-3 shadow-lg"
                       style="border-radius: 50px; font-weight: 600; font-size: 1.1rem;">
                        <i class="fas fa-rocket me-2"></i><span>Start Your Journey</span>
                    </a>
                    <a href="#featured-jobs" class="btn btn-outline-success btn-lg px-5 py-3"
                       style="border-radius: 50px; font-weight: 600; font-size: 1.1rem; border-width: 2px;">
                        <i class="fas fa-briefcase me-2"></i><span>View Jobs</span>
                    </a>
                </div>

                <!-- Trust Indicators -->
                <div class="mt-4 pt-3 border-top">
                    <div class="row g-3 text-center text-lg-start">
                        <div class="col-auto">
                            <small class="text-gray-600 d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-2"></i>
                                <strong><span>100% Free</span></strong> <span>for Job Seekers</span>
                            </small>
                        </div>
                        <div class="col-auto">
                            <small class="text-gray-600 d-flex align-items-center">
                                <i class="fas fa-shield-alt text-success me-2"></i>
                                <strong><span>Data Protected</span></strong> <span>& Secure</span>
                            </small>
                        </div>
                        <div class="col-auto">
                            <small class="text-gray-600 d-flex align-items-center">
                                <i class="fas fa-heart text-success me-2"></i>
                                <strong><span>1000+</span></strong> <span>Success Stories</span>
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hero Image/Illustration Side -->
            <div class="col-lg-6">
                <div class="hero-visual position-relative">
                    <!-- Main Visual Card -->
                    <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 30px;">
                        <div class="card-body p-5 bg-white">
                            <div class="text-center mb-4">
                                <div class="hero-icon-large mb-4 mx-auto"
                                     style="width: 200px; height: 200px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                                            border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-universal-access" style="font-size: 5rem; color: white;"></i>
                                </div>
                                <h3 class="fw-bold text-gray-800 mb-3">Join Our Community</h3>
                                <p class="text-gray-600 mb-4">
                                    Thousands of PWD professionals have found their dream careers through our platform.
                                </p>
                            </div>

                            <!-- Mini Stats -->
                            <div class="row g-3 text-center">
                                <div class="col-6">
                                    <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                        <h4 class="text-success fw-bold mb-1">{{ $featuredJobs->count() }}+</h4>
                                        <small class="text-gray-600"><span>Active Jobs</span></small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-success bg-opacity-10 rounded-3">
                                        <h4 class="text-success fw-bold mb-1">{{ $upcomingTrainings->count() }}+</h4>
                                        <small class="text-gray-600"><span>Training Programs</span></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Floating Badges -->
                    <div class="floating-badge badge bg-white shadow-lg position-absolute"
                         style="top: 20px; right: -20px; padding: 15px 20px; animation: float 3s ease-in-out infinite;">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong><span>Verified Employers</span></strong>
                    </div>

                    <div class="floating-badge badge bg-white shadow-lg position-absolute"
                         style="bottom: 40px; left: -20px; padding: 15px 20px; animation: float 3s ease-in-out infinite 1.5s;">
                        <i class="fas fa-award text-warning me-2"></i>
                        <strong><span>Government Recognized</span></strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Accessibility Features Banner -->
<section class="py-5 bg-white border-top border-bottom">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-3 text-center text-lg-start">
                <h3 class="h5 fw-bold text-gray-800 mb-2">
                    <i class="fas fa-universal-access text-success me-2"></i>
                    <span>Accessibility First</span>
                </h3>
                <p class="text-gray-600 small mb-0"><span>Built with PWD needs in mind</span></p>
            </div>
            <div class="col-lg-9">
                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            <i class="fas fa-keyboard text-success mb-2" style="font-size: 2rem;"></i>
                            <p class="small mb-0 fw-semibold"><span>Keyboard Navigation</span></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            <i class="fas fa-volume-up text-success mb-2" style="font-size: 2rem;"></i>
                            <p class="small mb-0 fw-semibold"><span>Screen Reader</span></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            <i class="fas fa-text-height text-success mb-2" style="font-size: 2rem;"></i>
                            <p class="small mb-0 fw-semibold"><span>Adjustable Text</span></p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="text-center p-3 bg-light rounded-3">
                            <i class="fas fa-adjust text-success mb-2" style="font-size: 2rem;"></i>
                            <p class="small mb-0 fw-semibold"><span>High Contrast</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-5 bg-gray-50">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h1 fw-bold text-gray-900 mb-3">How It Works</h2>
            <p class="lead text-gray-600">Three simple steps to launch your career</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 step-card">
                    <div class="step-number mx-auto mb-4"
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669);
                                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                                font-size: 2rem; color: white; font-weight: bold; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                        1
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-user-plus text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Create Your Profile</h3>
                    <p class="text-gray-600">
                        Sign up for free and complete your PWD profile with your skills, qualifications,
                        and accommodation needs. Your information is secure and private.
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 step-card">
                    <div class="step-number mx-auto mb-4"
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669);
                                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                                font-size: 2rem; color: white; font-weight: bold; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                        2
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-search text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Browse & Apply</h3>
                    <p class="text-gray-600">
                        Explore PWD-friendly job opportunities from verified inclusive employers.
                        Filter by disability type, accommodation available, and work arrangement.
                    </p>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 step-card">
                    <div class="step-number mx-auto mb-4"
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669);
                                border-radius: 50%; display: flex; align-items: center; justify-content: center;
                                font-size: 2rem; color: white; font-weight: bold; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                        3
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-briefcase text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Get Hired</h3>
                    <p class="text-gray-600">
                        Connect with employers who value diversity. Receive interview invitations,
                        access training programs, and start your meaningful career journey.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Jobs -->
<section id="featured-jobs" class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5 align-items-end">
            <div class="col-lg-8">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3">
                    <i class="fas fa-briefcase me-2"></i>Opportunities
                </span>
                <h2 class="display-5 fw-bold text-gray-900 mb-2">Featured Job Opportunities</h2>
                <p class="lead text-gray-600">
                    Handpicked opportunities from verified PWD-friendly employers
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg px-4">
                    <i class="fas fa-arrow-right me-2"></i>View All Jobs
                </a>
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
<section class="py-5 bg-gray-50">
    <div class="container">
        <div class="row mb-5 align-items-end">
            <div class="col-lg-8">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3">
                    <i class="fas fa-graduation-cap me-2"></i>Training & Development
                </span>
                <h2 class="display-5 fw-bold text-gray-900 mb-2">Free Skills Training Programs</h2>
                <p class="lead text-gray-600">
                    Enhance your employability with specialized training designed for PWD professionals
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <a href="{{ route('login') }}" class="btn btn-outline-success btn-lg px-4">
                    <i class="fas fa-arrow-right me-2"></i>View All Training
                </a>
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
<section class="py-5 bg-gray-50">
    <div class="container">
        <div class="row mb-5">
            <div class="col text-center">
                <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3">
                    <i class="fas fa-graduation-cap me-2"></i>Training & Development
                </span>
                <h2 class="display-5 fw-bold text-gray-900 mb-2">Free Skills Training Programs</h2>
                <p class="lead text-gray-600">
                    New training programs coming soon!
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
<section class="py-5 bg-white">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3">
                <i class="fas fa-star me-2"></i>Success Stories
            </span>
            <h2 class="display-5 fw-bold text-gray-900 mb-3">Inspiring Success Stories</h2>
            <p class="lead text-gray-600">Real stories from PWD professionals who transformed their careers</p>
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
<section class="py-5 bg-gray-50">
    <div class="container">
        <div class="text-center mb-5">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3">
                <i class="fas fa-handshake me-2"></i>Our Partners
            </span>
            <h2 class="display-5 fw-bold text-gray-900 mb-3">Trusted by Inclusive Employers</h2>
            <p class="lead text-gray-600">Companies committed to diversity, equity, and inclusion</p>
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
<section class="py-5 position-relative overflow-hidden"
         style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
    <!-- Background Pattern -->
    <div class="position-absolute w-100 h-100 top-0 start-0" style="opacity: 0.1;">
        <div class="position-absolute" style="top: -50px; right: -50px; width: 300px; height: 300px;
             background: white; border-radius: 50%;"></div>
        <div class="position-absolute" style="bottom: -100px; left: -100px; width: 400px; height: 400px;
             background: white; border-radius: 50%;"></div>
    </div>

    <div class="container py-5 position-relative" style="z-index: 1;">
        <div class="row text-center">
            <div class="col-lg-10 mx-auto">
                <div class="mb-4">
                    <span class="badge bg-white text-success px-4 py-2 rounded-pill mb-3"
                          style="font-size: 1rem;">
                        <i class="fas fa-rocket me-2"></i>Join Us Today
                    </span>
                </div>

                <h2 class="display-4 fw-bold text-white mb-4">
                    Ready to Start Your Career Journey?
                </h2>
                <p class="lead text-white mb-5" style="font-size: 1.3rem; opacity: 0.95;">
                    Join <strong>1,000+ PWD professionals</strong> who have found meaningful employment
                    through our platform. Your dream job is waiting!
                </p>

                <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
                    <a href="{{ route('register') }}"
                       class="btn btn-light btn-lg px-5 py-3 shadow-lg"
                       style="border-radius: 50px; font-weight: 600; font-size: 1.1rem;">
                        <i class="fas fa-user-plus me-2"></i>Create Free Account
                    </a>
                    <a href="{{ route('about') }}"
                       class="btn btn-outline-light btn-lg px-5 py-3"
                       style="border-radius: 50px; font-weight: 600; font-size: 1.1rem; border-width: 2px;">
                        <i class="fas fa-info-circle me-2"></i>Learn More
                    </a>
                </div>

                <!-- Bottom Trust Indicators -->
                <div class="row g-4 mt-4">
                    <div class="col-md-4">
                        <div class="text-white">
                            <i class="fas fa-shield-check mb-2" style="font-size: 2.5rem;"></i>
                            <h4 class="fw-bold mb-1">Secure & Private</h4>
                            <p class="mb-0 opacity-75">Your data is protected</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-white">
                            <i class="fas fa-award mb-2" style="font-size: 2.5rem;"></i>
                            <h4 class="fw-bold mb-1">Government Certified</h4>
                            <p class="mb-0 opacity-75">Officially recognized platform</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-white">
                            <i class="fas fa-headset mb-2" style="font-size: 2.5rem;"></i>
                            <h4 class="fw-bold mb-1">24/7 Support</h4>
                            <p class="mb-0 opacity-75">We're here to help</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
/* Hero Section Animations */
@keyframes float {
    0%, 100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-20px);
    }
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-section {
    animation: fadeInUp 0.8s ease-out;
}

.floating-badge {
    animation: float 3s ease-in-out infinite;
}

/* Feature Icon Styles */
.feature-icon-sm {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

/* Step Cards */
.step-card {
    transition: all 0.3s ease;
    border-radius: 20px;
}

.step-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(16, 185, 129, 0.2) !important;
}

.step-number {
    transition: all 0.3s ease;
}

.step-card:hover .step-number {
    transform: scale(1.1) rotate(5deg);
}

/* Job & Training Cards */
.job-card, .story-card, .training-card {
    transition: all 0.3s ease;
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.job-card:hover, .story-card:hover, .training-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 30px rgba(16, 185, 129, 0.2);
}

/* Partner Logo */
.partner-logo {
    transition: all 0.3s ease;
    border-radius: 12px;
}

.partner-logo:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

/* Empty State */
.empty-state {
    padding: 4rem 2rem;
    background: #f9fafb;
    border-radius: 20px;
}

/* Hero Visual Card */
.hero-visual .card {
    transition: all 0.3s ease;
}

.hero-visual .card:hover {
    transform: translateY(-5px);
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

.text-gray-900 {
    color: #111827;
}

.text-gray-500 {
    color: #6b7280;
}

/* Improved Readability */
body {
    line-height: 1.7;
}

h1, h2, h3, h4, h5, h6 {
    line-height: 1.3;
}

/* Badge Styles */
.badge {
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Button Hover Effects */
.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border: none;
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}

.btn-success:hover {
    background: linear-gradient(135deg, #059669 0%, #047857 100%);
    box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
}

/* Accessibility Improvements */
.btn:focus, .card:focus {
    outline: 3px solid #10b981;
    outline-offset: 2px;
}

/* Responsive Typography */
@media (max-width: 768px) {
    .display-3 {
        font-size: 2.5rem;
    }

    .display-4 {
        font-size: 2rem;
    }

    .display-5 {
        font-size: 1.75rem;
    }

    .lead {
        font-size: 1.1rem;
    }

    .hero-section {
        min-height: auto !important;
        padding: 3rem 0 !important;
    }

    .floating-badge {
        display: none;
    }
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
