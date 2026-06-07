@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="mb-0">My Contact Messages</h1>
            <p class="text-muted">View all your inquiries and responses from our team</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('contact') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> New Inquiry
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Total Messages</h6>
                    <h2 class="mb-0">{{ $totalMessages }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Answered</h6>
                    <h2 class="mb-0 text-success">{{ $answeredCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Unanswered</h6>
                    <h2 class="mb-0 text-warning">{{ $unansweredCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 bg-light">
                <div class="card-body">
                    <h6 class="text-muted mb-2">Response Rate</h6>
                    <h2 class="mb-0">{{ $totalMessages > 0 ? round(($answeredCount / $totalMessages) * 100) : 0 }}%</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-4">
        <ul class="nav nav-tabs border-0">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('contact-messages.index') }}">
                    All Messages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'unanswered' ? 'active' : '' }}" href="{{ route('contact-messages.index', ['status' => 'unanswered']) }}">
                    Unanswered
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') === 'answered' ? 'active' : '' }}" href="{{ route('contact-messages.index', ['status' => 'answered']) }}">
                    Answered
                </a>
            </li>
        </ul>
    </div>

    <!-- Messages List -->
    @forelse ($messages as $message)
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col">
                        <div class="d-flex align-items-start gap-2 mb-2">
                            <h5 class="mb-0">{{ $message->subject }}</h5>
                            @if ($message->responded_at)
                                <span class="badge bg-success">Answered</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-folder"></i> {{ $message->inquiry_type }}
                            <span class="ms-3">
                                <i class="fas fa-calendar"></i> {{ $message->created_at->format('M d, Y H:i') }}
                            </span>
                        </p>
                        <p class="text-truncate mb-0">{{ $message->message }}</p>
                    </div>
                    <div class="col-md-2 text-end">
                        <a href="{{ route('contact-messages.show', $message) }}" class="btn btn-sm btn-outline-primary">
                            View Details <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info" role="alert">
            <i class="fas fa-info-circle"></i>
            <strong>No messages found.</strong>
            <a href="{{ route('contact') }}">Submit a new inquiry</a> to get started.
        </div>
    @endforelse

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $messages->links() }}
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        border: none;
        border-bottom: 3px solid transparent;
        border-radius: 0;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #0d6efd;
        color: #0d6efd;
    }

    .nav-tabs .nav-link.active {
        border-bottom-color: #0d6efd;
        color: #0d6efd;
        background-color: transparent;
    }
</style>
@endsection
