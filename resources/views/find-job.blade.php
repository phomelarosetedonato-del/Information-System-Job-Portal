@extends('layouts.app')


@push('styles')
<link rel="stylesheet" href="{{ asset('resources/css/find-job-custom.css') }}">
@endpush

@section('title', 'Find Job - PWD Job Portal')

@section('content')
<section class="py-5" style="background: #f8fafc; min-height: 80vh;">
    <div class="container">
        <h2 class="mb-4 fw-bold text-success"><i class="fas fa-search me-2"></i>Find Job</h2>
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div style="background: #fff; border-radius: 50px; box-shadow: 0 2px 16px rgba(16,185,129,0.08); padding: 24px 18px; margin-bottom: 32px;">
                    <form method="GET" action="{{ route('find-job') }}" id="find-job-form" aria-label="Find jobs">
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
                                <style>
                                    #find-job-form button[type="submit"] {
                                        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
                                        border: none !important;
                                        color: #fff !important;
                                    }
                                    #find-job-form button[type="submit"]:hover {
                                        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
                                        color: #fff !important;
                                    }
                                </style>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- Job results would go here -->
                            <!-- Opportunities / Featured Job Opportunities -->
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

                                    @if(isset($featuredJobs) && $featuredJobs->count() > 0)
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
                                                            <i class="fas fa-map-marker-alt me-1"></i>{{ is_object($job->location) && isset($job->location->name) ? $job->location->name : ($job->location ?? 'Remote') }}
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
                                                    <a href="{{ route('login') }}" class="btn btn-apply-now w-100">Apply Now</a>
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

                            <!-- Training & Development / Free Skills Training Programs -->
                            @if(isset($upcomingTrainings) && $upcomingTrainings->count() > 0)
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
                                                            {{ (!empty($training->is_free) && $training->is_free) ? 'FREE' : ($training->fee ? '₱'.$training->fee : '') }}
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
            </div>
        </div>
    </div>
</section>

@endsection
