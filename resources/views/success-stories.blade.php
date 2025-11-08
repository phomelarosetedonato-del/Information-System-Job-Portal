@extends('layouts.app')

@section('title', 'Success Stories - PWD Job Portal')

@section('content')
<!-- Success Stories Hero Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <h1 class="display-4 fw-bold mb-4 text-gray-800">Inspiring Success Stories</h1>
                <p class="lead text-gray-600 mb-4">
                    Discover how Persons with Disabilities have transformed their careers through our platform
                </p>
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <p class="text-gray-600">
                            Read real stories of PWD professionals who found meaningful employment,
                            upgraded their skills, and built successful careers with inclusive employers.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Stories Grid -->
<section class="py-5 bg-gray-50">
    <div class="container">
        @if($stories->count() > 0)
        <div class="row">
            @foreach($stories as $story)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card story-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="story-header mb-3">
                            <div class="d-flex align-items-center">
                                <div class="story-avatar me-3">
                                    @if($story->photo)
                                    <img src="{{ asset('storage/' . $story->photo) }}"
                                         alt="{{ $story->name }}"
                                         class="rounded-circle"
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                    <div class="bg-amber-100 text-amber-800 rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-user fa-2x"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1 text-gray-800">{{ $story->name }}</h6>
                                    <small class="text-gray-600">{{ $story->job_title }} at {{ $story->company }}</small>
                                </div>
                            </div>
                        </div>

                        <div class="story-meta mb-3">
                            @if($story->disability_type)
                            <span class="badge bg-amber-100 text-amber-800 mb-2">
                                {{ ucfirst($story->disability_type) }}
                            </span>
                            @endif
                            @if($story->salary_increase)
                            <span class="badge bg-green-100 text-green-800 mb-2">
                                Salary +{{ $story->salary_increase }}%
                            </span>
                            @endif
                        </div>

                        <p class="card-text text-gray-600">{{ Str::limit($story->story, 150) }}</p>

                        <div class="story-stats mt-3">
                            @if($story->previous_situation)
                            <div class="stat-item mb-2">
                                <small class="text-gray-500">
                                    <strong>Before:</strong> {{ Str::limit($story->previous_situation, 60) }}
                                </small>
                            </div>
                            @endif
                            @if($story->achievement)
                            <div class="stat-item">
                                <small class="text-gray-500">
                                    <strong>Achievement:</strong> {{ Str::limit($story->achievement, 60) }}
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-gray-500">
                                <i class="fas fa-calendar me-1"></i>
                                {{ $story->created_at->format('M d, Y') }}
                            </small>
                            <a href="{{ route('story.show', $story->id) }}" class="btn btn-nude-solid btn-sm">
                                Read Full Story
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($stories->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <nav aria-label="Success stories pagination">
                    {{ $stories->links() }}
                </nav>
            </div>
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-book-open fa-4x text-gray-400 mb-4"></i>
                    <h3 class="text-gray-800 mb-3">No Success Stories Yet</h3>
                    <p class="text-gray-600 mb-4">
                        We're gathering inspiring stories from our PWD community.
                        Check back soon to read about their career journeys!
                    </p>
                    <a href="{{ route('home') }}" class="btn btn-nude-solid">
                        <i class="fas fa-arrow-left me-2"></i>Back to Home
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-3 text-gray-800">Share Your Success Story</h2>
                <p class="lead mb-4 text-gray-600">
                    Have you found success through our platform? Inspire others by sharing your journey.
                </p>
                <div class="cta-buttons">
                    <a href="{{ route('contact') }}" class="btn btn-nude-solid btn-lg me-3">
                        <i class="fas fa-share-alt me-2"></i>Share Your Story
                    </a>
                    <a href="{{ route('home') }}" class="btn btn-nude-outline btn-lg">
                        <i class="fas fa-briefcase me-2"></i>Browse Opportunities
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('styles')
<style>
.story-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.story-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.story-avatar {
    flex-shrink: 0;
}

.empty-state {
    padding: 3rem 1rem;
}

.bg-amber-100 {
    background-color: #fef3c7;
}

.text-amber-800 {
    color: #92400e;
}

.bg-green-100 {
    background-color: #dcfce7;
}

.text-green-800 {
    color: #166534;
}

@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }

    .cta-buttons .btn {
        display: block;
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endsection
