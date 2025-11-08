@extends('layouts.app')

@section('title', ($story->name ?? 'Success Story') . ' - PWD Job Portal')

@section('content')
<!-- Story Detail Header -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('success-stories') }}">Success Stories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Story</li>
                    </ol>
                </nav>
            </div>
        </div>

        @if($story)
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3 text-gray-800">{{ $story->name }}'s Success Story</h1>
                <p class="lead text-gray-600 mb-4">{{ $story->job_title }} at {{ $story->company }}</p>

                <div class="story-meta mb-4">
                    @if($story->disability_type)
                    <span class="badge bg-amber-100 text-amber-800 me-2 mb-2">
                        {{ ucfirst($story->disability_type) }}
                    </span>
                    @endif
                    @if($story->salary_increase)
                    <span class="badge bg-green-100 text-green-800 me-2 mb-2">
                        Salary Increased by {{ $story->salary_increase }}%
                    </span>
                    @endif
                    <span class="text-gray-500">
                        <i class="fas fa-calendar me-1"></i>
                        Published {{ $story->created_at->format('F d, Y') }}
                    </span>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                @if($story->photo)
                <img src="{{ asset('storage/' . $story->photo) }}"
                     alt="{{ $story->name }}"
                     class="img-fluid rounded-circle shadow"
                     style="width: 200px; height: 200px; object-fit: cover;">
                @else
                <div class="bg-amber-100 text-amber-800 rounded-circle d-flex align-items-center justify-content-center mx-auto"
                     style="width: 200px; height: 200px;">
                    <i class="fas fa-user fa-5x"></i>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Story Content -->
@if($story)
<section class="py-5 bg-gray-50">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        <!-- Before Situation -->
                        @if($story->previous_situation)
                        <div class="story-section mb-5">
                            <h3 class="text-amber-600 mb-3">The Challenge</h3>
                            <p class="text-gray-700 lead">{{ $story->previous_situation }}</p>
                        </div>
                        @endif

                        <!-- The Journey -->
                        <div class="story-section mb-5">
                            <h3 class="text-amber-600 mb-3">The Journey</h3>
                            <div class="text-gray-700">
                                {!! nl2br(e($story->story)) !!}
                            </div>
                        </div>

                        <!-- Achievement -->
                        @if($story->achievement)
                        <div class="story-section mb-5">
                            <h3 class="text-amber-600 mb-3">The Achievement</h3>
                            <p class="text-gray-700 lead">{{ $story->achievement }}</p>
                        </div>
                        @endif

                        <!-- Key Takeaways -->
                        @if($story->key_takeaways)
                        <div class="story-section">
                            <h3 class="text-amber-600 mb-3">Key Takeaways</h3>
                            <div class="text-gray-700">
                                {!! nl2br(e($story->key_takeaways)) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Stories -->
@if($relatedStories->count() > 0)
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-4">
            <div class="col">
                <h2 class="section-title">More Inspiring Stories</h2>
                <p class="text-gray-600">Discover other success stories from our PWD community</p>
            </div>
        </div>

        <div class="row">
            @foreach($relatedStories as $relatedStory)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card story-card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="story-header mb-3">
                            <div class="d-flex align-items-center">
                                <div class="story-avatar me-3">
                                    @if($relatedStory->photo)
                                    <img src="{{ asset('storage/' . $relatedStory->photo) }}"
                                         alt="{{ $relatedStory->name }}"
                                         class="rounded-circle"
                                         style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                    <div class="bg-amber-100 text-amber-800 rounded-circle d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    @endif
                                </div>
                                <div>
                                    <h6 class="mb-1 text-gray-800">{{ $relatedStory->name }}</h6>
                                    <small class="text-gray-600">{{ $relatedStory->job_title }}</small>
                                </div>
                            </div>
                        </div>
                        <p class="card-text text-gray-600 small">{{ Str::limit($relatedStory->story, 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-top">
                        <a href="{{ route('story.show', $relatedStory->id) }}" class="btn btn-nude-solid btn-sm w-100">
                            Read Story
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5 bg-gray-50">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-3 text-gray-800">Ready to Create Your Success Story?</h2>
                <p class="lead mb-4 text-gray-600">
                    Join thousands of PWD professionals who have transformed their careers through our platform.
                </p>
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="btn btn-nude-solid btn-lg me-3">
                        <i class="fas fa-user-plus me-2"></i>Start Your Journey
                    </a>
                    <a href="{{ route('success-stories') }}" class="btn btn-nude-outline btn-lg">
                        <i class="fas fa-book-open me-2"></i>View All Stories
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@else
<!-- Story Not Found -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <i class="fas fa-search fa-4x text-gray-400 mb-4"></i>
                    <h3 class="text-gray-800 mb-3">Story Not Found</h3>
                    <p class="text-gray-600 mb-4">
                        The success story you're looking for doesn't exist or has been removed.
                    </p>
                    <a href="{{ route('success-stories') }}" class="btn btn-nude-solid">
                        <i class="fas fa-arrow-left me-2"></i>Back to Success Stories
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@section('styles')
<style>
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
    background: #d97706;
}

.story-section {
    border-left: 4px solid #d97706;
    padding-left: 20px;
}

.story-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.story-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15);
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

.empty-state {
    padding: 3rem 1rem;
}

@media (max-width: 768px) {
    .display-5 {
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
