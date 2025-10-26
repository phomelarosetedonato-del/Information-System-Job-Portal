<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PWD Employment Philippines</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #2c5aa0;
            --secondary-color: #f8f9fa;
            --accent-color: #ff6b35;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        .navbar {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }

        .nav-link {
            color: #555 !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: var(--primary-color) !important;
        }

        .hero-section {
            background: linear-gradient(rgba(44, 90, 160, 0.8), rgba(44, 90, 160, 0.9)), url('https://images.unsplash.com/photo-1551836026-d5c88ac6d0aa?ixlib=rb-4.0.3&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
            text-align: center;
        }

        .search-box {
            background-color: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: #1e4080;
            border-color: #1e4080;
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .section-title {
            color: var(--primary-color);
            margin-bottom: 30px;
            font-weight: 600;
        }

        .feature-box {
            text-align: center;
            padding: 20px;
            border-radius: 8px;
            transition: transform 0.3s;
            margin-bottom: 20px;
        }

        .feature-box:hover {
            transform: translateY(-5px);
        }

        .feature-icon {
            font-size: 40px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .stats-section {
            background-color: var(--secondary-color);
            padding: 60px 0;
        }

        .stat-number {
            font-size: 40px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .testimonial-card {
            border-left: 4px solid var(--primary-color);
            padding-left: 20px;
            margin-bottom: 30px;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            padding: 40px 0 20px;
        }

        .footer-links a {
            color: #ddd;
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
        }

        .footer-links a:hover {
            color: white;
        }

        .social-icons a {
            color: white;
            font-size: 20px;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">PWD EMPLOYMENT<br>ALAMINOS CITY</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Events</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Read First</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="/register" class="btn btn-primary">Join Now</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">PWD Employment Opportunities</h1>
            <p class="lead mb-5">Connecting Persons with Disabilities and Job Opportunities in the Philippines</p>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="search-box">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <input type="text" class="form-control" placeholder="Job Title or Keyword">
                            </div>
                            <div class="col-md-4">
                                <select class="form-select">
                                    <option selected>All Locations</option>
                                    <option>Metro Manila</option>
                                    <option>Luzon</option>
                                    <option>Visayas</option>
                                    <option>Mindanao</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select">
                                    <option selected>All Categories</option>
                                    <option>IT & Software</option>
                                    <option>Healthcare</option>
                                    <option>Education</option>
                                    <option>Customer Service</option>
                                    <option>Manufacturing</option>
                                </select>
                            </div>
                            <div class="col-12 text-center mt-3">
                                <button class="btn btn-primary btn-lg px-5">Find Jobs</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center">How We Help</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h4>For Job Seekers</h4>
                        <p>Find meaningful employment opportunities that match your skills and abilities.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h4>For Employers</h4>
                        <p>Connect with talented PWD candidates and build an inclusive workforce.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-box">
                        <div class="feature-icon">
                            <i class="fas fa-hands-helping"></i>
                        </div>
                        <h4>Support Services</h4>
                        <p>Access resources and support to ensure workplace accessibility and accommodation.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">500+</div>
                    <p>Companies Registered</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">2,000+</div>
                    <p>Job Seekers</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">1,500+</div>
                    <p>Jobs Posted</p>
                </div>
                <div class="col-md-3 col-6 mb-4">
                    <div class="stat-number">800+</div>
                    <p>Successful Placements</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container">
            <h2 class="section-title text-center">Success Stories</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="testimonial-card">
                        <p>"Thanks to PWD Employment Philippines, I found a job that accommodates my needs and values my skills. I've been working as a software developer for over a year now."</p>
                        <p class="fw-bold">- Maria Santos, Software Developer</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="testimonial-card">
                        <p>"As an employer, we've found amazing talent through this platform. The candidates are skilled, dedicated, and have brought diverse perspectives to our team."</p>
                        <p class="fw-bold">- John Reyes, HR Manager</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="section-title">Ready to Get Started?</h2>
            <p class="lead mb-4">Join thousands of job seekers and employers in creating inclusive workplaces.</p>
            <a href="/register" class="btn btn-primary btn-lg me-3">Create Account</a>
            <a href="/login" class="btn btn-outline-primary btn-lg">Sign up</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5>PWD Employment Opportunities</h5>
                    <p>Connecting Persons with Disabilities and Job Opportunities in the Alaminos City</p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-md-2 mb-4">
                    <h5>Quick Links</h5>
                    <div class="footer-links">
                        <a href="/">Home</a>
                        <a href="#">Companies</a>
                        <a href="#">Candidates</a>
                        <a href="#">Find Jobs</a>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Resources</h5>
                    <div class="footer-links">
                        <a href="#">Contact Us</a>
                        <a href="#">Read First</a>
                        <a href="#">FAQ</a>
                        <a href="#">Privacy Policy</a>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Contact Info</h5>
                    <p><i class="fas fa-map-marker-alt me-2"></i> Manila, Philippines</p>
                    <p><i class="fas fa-phone me-2"></i> +63 912 345 6789</p>
                    <p><i class="fas fa-envelope me-2"></i> info@pwdemployment.ph</p>
                </div>
            </div>
            <hr>
            <div class="text-center pt-2">
                <p>&copy; 2023 PWD Employment Philippines. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
