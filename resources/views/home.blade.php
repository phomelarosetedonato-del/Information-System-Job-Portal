@extends('layouts.app')

@section('title', 'PWD Employment Opportunities - Alaminos City')

@section('content')
<!-- ADD THE ENTIRE HOMEPAGE CONTENT HERE -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold text-white mb-4 animate-fade-in">
                    Empowering <span class="text-warning">Persons with Disabilities</span> Through Employment
                </h1>
                <p class="lead text-white mb-5 animate-slide-up">
                    Connecting talented PWD individuals with inclusive employers in Alaminos City and beyond.
                    Build your career or find exceptional talent for your organization.
                </p>
                <div class="d-flex flex-wrap gap-3 animate-slide-up">
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4 py-3 fw-semibold">
                        <i class="fas fa-user-plus me-2"></i>Join as Job Seeker
                    </a>
                    <a href="{{ route('register') }}?type=employer" class="btn btn-outline-light btn-lg px-4 py-3 fw-semibold">
                        <i class="fas fa-building me-2"></i>Register as Employer
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image-container">
                    <img src="https://images.unsplash.com/photo-1573164713714-d95e436ab8d6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80"
                         alt="Diverse team working together" class="img-fluid rounded-3 shadow-lg">
                    <div class="floating-badge bg-success">
                        <i class="fas fa-users me-2"></i>800+ Successful Placements
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Stats -->
<section class="quick-stats py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-6 text-center">
                <div class="stat-card">
                    <div class="stat-number text-primary">500+</div>
                    <p class="stat-label">Companies Registered</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="stat-card">
                    <div class="stat-number text-success">2,000+</div>
                    <p class="stat-label">Active Job Seekers</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="stat-card">
                    <div class="stat-number text-warning">1,500+</div>
                    <p class="stat-label">Jobs Available</p>
                </div>
            </div>
            <div class="col-md-3 col-6 text-center">
                <div class="stat-card">
                    <div class="stat-number text-info">95%</div>
                    <p class="stat-label">Satisfaction Rate</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">How We Make a Difference</h2>
            <p class="lead text-muted">Comprehensive support for both job seekers and employers</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon bg-primary">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <h4>For Job Seekers</h4>
                    <p>Find meaningful employment that matches your skills, with accommodations and support throughout your journey.</p>
                    <ul class="feature-list">
                        <li>Accessibility-focused job matching</li>
                        <li>Career counseling and guidance</li>
                        <li>Resume building assistance</li>
                        <li>Interview preparation</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon bg-success">
                        <i class="fas fa-building"></i>
                    </div>
                    <h4>For Employers</h4>
                    <p>Connect with talented PWD candidates and build an inclusive, diverse workforce that drives innovation.</p>
                    <ul class="feature-list">
                        <li>Access to qualified PWD talent</li>
                        <li>Workplace accommodation guidance</li>
                        <li>Diversity and inclusion training</li>
                        <li>Government incentive information</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon bg-warning">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <h4>Support Services</h4>
                    <p>Comprehensive support system ensuring success for both employees and employers in the workplace.</p>
                    <ul class="feature-list">
                        <li>Accessibility assessments</li>
                        <li>Assistive technology guidance</li>
                        <li>Workplace modification support</li>
                        <li>Ongoing career development</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Job Categories -->
<section class="categories-section py-5 bg-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Popular Job Categories</h2>
            <p class="lead text-muted">Explore opportunities across various industries</p>
        </div>

        <div class="row g-3">
            <div class="col-lg-3 col-md-6">
                <div class="category-card">
                    <i class="fas fa-laptop-code category-icon"></i>
                    <h5>IT & Technology</h5>
                    <span class="badge bg-primary">120+ Jobs</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="category-card">
                    <i class="fas fa-stethoscope category-icon"></i>
                    <h5>Healthcare</h5>
                    <span class="badge bg-success">85+ Jobs</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="category-card">
                    <i class="fas fa-chalkboard-teacher category-icon"></i>
                    <h5>Education</h5>
                    <span class="badge bg-info">65+ Jobs</span>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="category-card">
                    <i class="fas fa-headset category-icon"></i>
                    <h5>Customer Service</h5>
                    <span class="badge bg-warning">150+ Jobs</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories -->
<section class="testimonials-section py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Success Stories</h2>
            <p class="lead text-muted">Hear from our community members</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="testimonial-text">
                            "Thanks to PWD Employment Alaminos, I found a remote software development job that accommodates my mobility needs. The support team helped me with workplace setup and I've been thriving for over two years now."
                        </p>
                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1494790108755-2616b612b786?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="Maria Santos" class="author-image">
                            <div>
                                <h6 class="mb-0">Maria Santos</h6>
                                <small class="text-muted">Software Developer</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="testimonial-card">
                    <div class="testimonial-content">
                        <div class="quote-icon">
                            <i class="fas fa-quote-left"></i>
                        </div>
                        <p class="testimonial-text">
                            "As an employer, we've hired three amazing team members through this platform. The candidates are skilled, dedicated, and have brought incredible diversity to our company culture. The matching process was seamless."
                        </p>
                        <div class="testimonial-author">
                            <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="John Reyes" class="author-image">
                            <div>
                                <h6 class="mb-0">John Reyes</h6>
                                <small class="text-muted">HR Manager, Tech Solutions Inc.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h3 class="mb-3">Ready to Start Your Journey?</h3>
                <p class="mb-0">Join thousands of job seekers and employers creating inclusive workplaces in Alaminos City.</p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ route('register') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-user-plus me-2"></i>Join Now
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-sign-in-alt me-2"></i>Sign In
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 100px 0;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://images.unsplash.com/photo-1551836026-d5c88ac6d0aa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
        background-size: cover;
        background-position: center;
        opacity: 0.1;
    }

    .min-vh-80 {
        min-height: 80vh;
    }

    .hero-image-container {
        position: relative;
    }

    .floating-badge {
        position: absolute;
        top: -10px;
        right: -10px;
        padding: 12px 20px;
        border-radius: 50px;
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }

    /* Animations */
    .animate-fade-in {
        animation: fadeIn 1s ease-in;
    }

    .animate-slide-up {
        animation: slideUp 0.8s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Quick Stats */
    .stat-card {
        padding: 20px;
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1;
    }

    .stat-label {
        color: #6c757d;
        font-weight: 500;
        margin: 0;
    }

    /* Feature Cards */
    .feature-card {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #f0f0f0;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        color: white;
        font-size: 1.8rem;
    }

    .feature-card h4 {
        color: #2c3e50;
        margin-bottom: 1rem;
        font-weight: 600;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin-top: 1.5rem;
    }

    .feature-list li {
        padding: 0.3rem 0;
        position: relative;
        padding-left: 1.5rem;
    }

    .feature-list li::before {
        content: '✓';
        position: absolute;
        left: 0;
        color: #28a745;
        font-weight: bold;
    }

    /* Category Cards */
    .category-card {
        background: white;
        padding: 2rem 1rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .category-card:hover {
        border-color: #667eea;
        transform: translateY(-3px);
    }

    .category-icon {
        font-size: 2.5rem;
        color: #667eea;
        margin-bottom: 1rem;
    }

    .category-card h5 {
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    /* Testimonials */
    .testimonial-card {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 25px rgba(0,0,0,0.08);
        height: 100%;
        border-left: 4px solid #667eea;
    }

    .testimonial-content {
        position: relative;
    }

    .quote-icon {
        position: absolute;
        top: -10px;
        left: -10px;
        background: #667eea;
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    .testimonial-text {
        font-style: italic;
        color: #555;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .testimonial-author {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .author-image {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
    }

    /* Section Titles */
    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        position: relative;
        display: inline-block;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-section {
            padding: 60px 0;
            text-align: center;
        }

        .display-4 {
            font-size: 2rem;
        }

        .section-title {
            font-size: 2rem;
        }

        .stat-number {
            font-size: 2rem;
        }
    }
</style>
@endpush
