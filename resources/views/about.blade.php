@extends('layouts.app')

@section('title', 'About Us - PWD Job Portal')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-primary text-white py-5 mt-8">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold mb-4">About Our Mission</h1>
                <p class="lead mb-4">
                    Empowering Persons with Disabilities through inclusive employment opportunities and skill development
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Mission & Vision -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 mb-5">
                <div class="mission-card">
                    <div class="icon-wrapper mb-4">
                        <i class="fas fa-bullseye fa-3x text-primary"></i>
                    </div>
                    <h3 class="mb-3">Our Mission</h3>
                    <p class="text-muted">
                        To bridge the employment gap for Persons with Disabilities by connecting them with inclusive employers
                        and providing access to specialized training programs that enhance their skills and career prospects.
                    </p>
                </div>
            </div>
            <div class="col-lg-6 mb-5">
                <div class="vision-card">
                    <div class="icon-wrapper mb-4">
                        <i class="fas fa-eye fa-3x text-success"></i>
                    </div>
                    <h3 class="mb-3">Our Vision</h3>
                    <p class="text-muted">
                        A world where every Person with Disability has equal access to meaningful employment opportunities
                        and can achieve their full professional potential in an inclusive and accessible work environment.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col text-center">
                <h2 class="section-title">Our Impact</h2>
                <p class="text-muted">Making a difference in the lives of PWD professionals</p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <h3 class="text-primary fw-bold display-4">{{ $stats['jobs_posted'] ?? 0 }}+</h3>
                    <p class="text-muted mb-0">Jobs Posted</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <h3 class="text-success fw-bold display-4">{{ $stats['trainings_offered'] ?? 0 }}+</h3>
                    <p class="text-muted mb-0">Training Programs</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <h3 class="text-info fw-bold display-4">{{ $stats['partner_companies'] ?? 0 }}+</h3>
                    <p class="text-muted mb-0">Partner Companies</p>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-4">
                <div class="stat-item">
                    <h3 class="text-warning fw-bold display-4">{{ $stats['success_stories'] ?? 0 }}+</h3>
                    <p class="text-muted mb-0">Success Stories</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What We Do -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col text-center">
                <h2 class="section-title">What We Do</h2>
                <p class="text-muted">Comprehensive support for PWD career development</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-briefcase fa-3x text-primary"></i>
                    </div>
                    <h4 class="mb-3">Job Matching</h4>
                    <p class="text-muted">
                        Connect PWD professionals with employers who value diversity and provide accessible work environments.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-graduation-cap fa-3x text-success"></i>
                    </div>
                    <h4 class="mb-3">Skill Development</h4>
                    <p class="text-muted">
                        Offer specialized training programs designed to enhance skills and improve employability.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-hands-helping fa-3x text-info"></i>
                    </div>
                    <h4 class="mb-3">Career Guidance</h4>
                    <p class="text-muted">
                        Provide career counseling and mentorship to help navigate professional challenges and opportunities.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-building fa-3x text-warning"></i>
                    </div>
                    <h4 class="mb-3">Employer Partnership</h4>
                    <p class="text-muted">
                        Work with companies to create inclusive workplaces and implement accessibility best practices.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-universal-access fa-3x text-danger"></i>
                    </div>
                    <h4 class="mb-3">Accessibility Support</h4>
                    <p class="text-muted">
                        Ensure our platform and services are accessible to users with various types of disabilities.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-4">
                        <i class="fas fa-users fa-3x text-secondary"></i>
                    </div>
                    <h4 class="mb-3">Community Building</h4>
                    <p class="text-muted">
                        Foster a supportive community where PWD professionals can network and share experiences.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Values Section -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-5">
            <div class="col text-center">
                <h2 class="section-title">Our Values</h2>
                <p class="text-muted">The principles that guide our work</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="value-item d-flex">
                    <div class="value-icon me-4">
                        <i class="fas fa-balance-scale fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h4 class="mb-3">Inclusion</h4>
                        <p class="text-muted mb-0">
                            We believe in creating opportunities where everyone, regardless of ability, can participate fully and equally.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="value-item d-flex">
                    <div class="value-icon me-4">
                        <i class="fas fa-shield-alt fa-2x text-success"></i>
                    </div>
                    <div>
                        <h4 class="mb-3">Empowerment</h4>
                        <p class="text-muted mb-0">
                            We empower PWD professionals with the tools, skills, and confidence to achieve their career goals.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="value-item d-flex">
                    <div class="value-icon me-4">
                        <i class="fas fa-handshake fa-2x text-info"></i>
                    </div>
                    <div>
                        <h4 class="mb-3">Collaboration</h4>
                        <p class="text-muted mb-0">
                            We work together with employers, organizations, and the community to drive meaningful change.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="value-item d-flex">
                    <div class="value-icon me-4">
                        <i class="fas fa-rocket fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h4 class="mb-3">Innovation</h4>
                        <p class="text-muted mb-0">
                            We continuously innovate to improve accessibility and create better solutions for our community.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Team Section (Optional - you can remove if you don't have team info) -->
<section class="py-5">
    <div class="container">
        <div class="row mb-5">
            <div class="col text-center">
                <h2 class="section-title">Our Commitment</h2>
                <p class="text-muted">Dedicated to making a difference</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <p class="lead text-muted mb-4">
                    We are a team of passionate individuals committed to breaking down barriers and creating equal opportunities
                    for Persons with Disabilities in the workforce. Our platform is built on the belief that disability is not
                    inability, and that with the right support and opportunities, everyone can thrive professionally.
                </p>
                <p class="text-muted">
                    Through partnerships with forward-thinking employers, specialized training programs, and a supportive community,
                    we're working towards a more inclusive future where PWD professionals can access the same career opportunities
                    as their peers.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-3">Join Our Mission</h2>
                <p class="lead mb-4">Be part of the movement to create inclusive employment opportunities for Persons with Disabilities.</p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i>Join as Job Seeker
                    </a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-building me-2"></i>Partner With Us
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
    background: linear-gradient(135deg, #00695C 0%, #00695C 100%);
}

.section-title {
    position: relative;
    padding-bottom: 15px;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: #00695C;
}

.mission-card, .vision-card, .feature-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: none;
    padding: 2rem;
    border-radius: 10px;
    background: white;
    box-shadow: 0 2px 15px rgba(0,0,0,0.1);
    height: 100%;
}

.mission-card:hover, .vision-card:hover, .feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 25px rgba(0,0,0,0.15);
}

.feature-icon, .value-icon, .icon-wrapper {
    transition: transform 0.3s ease;
}

.feature-card:hover .feature-icon,
.value-item:hover .value-icon,
.mission-card:hover .icon-wrapper,
.vision-card:hover .icon-wrapper {
    transform: scale(1.1);
}

.stat-item h3 {
    font-size: 3rem;
}

.value-item {
    padding: 1.5rem;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.value-item:hover {
    transform: translateX(5px);
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }

    .stat-item h3 {
        font-size: 2rem;
    }

    .cta-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }

    .value-item {
        text-align: center;
        flex-direction: column;
    }

    .value-icon {
        margin-right: 0 !important;
        margin-bottom: 1rem;
    }
}
</style>
@endsection
