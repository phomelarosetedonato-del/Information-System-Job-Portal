@extends('layouts.app')

@section('title', 'Welcome - PWD Job Portal')

@section('content')

<!-- Hero Section -->
<section class="hero-section py-5" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); min-height: 85vh; display: flex; align-items: center;">
    <div class="container">
        <!-- Hero Image with Overlayed Filter Form -->
        <div class="hero-image-overlay mb-5 position-relative" style="max-height: 320px;">
            <img src="/images/landingpage.jpg" alt="PWD Job Portal Banner" class="img-fluid rounded shadow w-100 hero-bg-img" style="max-height: 320px; object-fit: cover;">
            <div class="hero-img-overlay-dark"></div>
            <div class="filter-overlay-form position-absolute top-50 start-50 translate-middle w-100" style="z-index: 2; max-width: 900px; left: 50%; transform: translate(-50%, -50%);">
                <div style="background: rgba(255,255,255,0.95); border-radius: 50px; box-shadow: 0 2px 16px rgba(16,185,129,0.12); padding: 24px 18px;">
                    <form method="GET" action="{{ route('home') }}" id="home-job-search-form" aria-label="Search jobs">
                        <div class="row g-2">
                            <div class="col-12 col-md-4 mb-2">
                                <input type="text" name="q" class="form-control form-control-lg" placeholder="Job title, company, or keyword" value="{{ request('q') }}">
                            </div>
                            <div class="col-12 col-md-4 mb-2">
                                <select name="location" class="form-select form-select-lg">
                                    <option value="">All Locations</option>
                                    @foreach($locations ?? [] as $loc)
                                        @if(is_object($loc) && isset($loc->name))
                                            <option value="{{ $loc->name }}" @if(request('location') == $loc->name) selected @endif>{{ $loc->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4 mb-2">
                                <select name="disability_type_id" class="form-select form-select-lg">
                                    <option value="">Any Disability Type</option>
                                    @foreach($disabilityTypes ?? [] as $dt)
                                        @if(is_object($dt) && isset($dt->id))
                                            <option value="{{ $dt->id }}" @if(request('disability_type_id') == $dt->id) selected @endif>{{ $dt->type }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <button type="submit" class="btn btn-green-solid btn-lg w-100" style="font-size: 1.25rem;">
                                    <i class="fas fa-search me-2"></i>Find Jobs
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @section('styles')
        @parent
        <style>
        .hero-image-overlay {
            max-height: 320px;
            overflow: hidden;
            position: relative;
        }
        .hero-bg-img {
            opacity: 0.45;
            transition: opacity 0.3s;
        }
        .hero-img-overlay-dark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.25);
            border-radius: 16px;
            z-index: 1;
            pointer-events: none;
        }
        .filter-overlay-form {
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            padding-left: 15px;
            padding-right: 15px;
        }
        @media (max-width: 991.98px) {
            .filter-overlay-form {
                max-width: 98vw;
                padding-left: 5px;
                padding-right: 5px;
            }
        }
        </style>
        @endsection
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
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; font-weight: bold; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                        1
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-user-plus text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Create Your Profile</h3>
                    <p class="text-gray-600">
                        Sign up for free and complete your PWD profile with your skills, qualifications, and work preferences. This helps us match you with the best opportunities.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 step-card">
                    <div class="step-number mx-auto mb-4"
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; font-weight: bold; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                        2
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-search text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Search & Apply</h3>
                    <p class="text-gray-600">
                        Browse inclusive job opportunities and training programs. Use filters to find roles that fit your abilities and interests, then apply in just a few clicks.
                    </p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4 step-card">
                    <div class="step-number mx-auto mb-4"
                         style="width: 80px; height: 80px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: white; font-weight: bold; box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);">
                        3
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-briefcase text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="h4 fw-bold mb-3">Get Hired & Grow</h3>
                    <p class="text-gray-600">
                        Connect with employers, attend interviews, and start your new career. Access ongoing support and training to help you succeed and grow professionally.
                    </p>
                </div>
            </div>

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
