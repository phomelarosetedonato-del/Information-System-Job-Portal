@extends('layouts.app')

@section('content')
<div class="container mt-5 mb-5">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('contact-messages.index') }}" class="btn btn-link text-decoration-none">
                    <i class="fas fa-arrow-left"></i> Back to Messages
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Original Message Card -->
            <div class="card mb-4 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h4 class="mb-0">{{ $contact->subject }}</h4>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-folder"></i> {{ $contact->inquiry_type }}
                            </p>
                        </div>
                        @if ($contact->responded_at)
                            <span class="badge bg-success" style="font-size: 0.85rem;">Answered</span>
                        @else
                            <span class="badge bg-warning" style="font-size: 0.85rem;">Pending Response</span>
                        @endif
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted">
                            <strong>Submitted:</strong> {{ $contact->created_at->format('F d, Y \a\t H:i') }}<br>
                            <strong>From:</strong> {{ $contact->name }} ({{ $contact->email }})
                        </small>
                    </div>

                    <div class="bg-light p-4 rounded mb-3">
                        <p class="mb-0" style="white-space: pre-wrap; word-wrap: break-word;">{{ $contact->message }}</p>
                    </div>
                </div>
            </div>

            <!-- Response Card (if exists) -->
            @if ($contact->responded_at)
                <div class="card border-0 shadow-sm border-start border-4" style="border-color: #28a745 !important;">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="fas fa-reply text-success" style="font-size: 1.5rem;"></i>
                            <div>
                                <h5 class="mb-0">Our Response</h5>
                                <small class="text-muted">Responded on {{ $contact->responded_at->format('F d, Y \a\t H:i') }}</small>
                            </div>
                        </div>

                        <hr>

                        <div class="bg-light p-4 rounded">
                            <p class="mb-0" style="white-space: pre-wrap; word-wrap: break-word;">{{ $contact->response_notes }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-hourglass-end text-muted" style="font-size: 2rem;"></i>
                        <h5 class="mt-3">Waiting for Response</h5>
                        <p class="text-muted">Our team is reviewing your inquiry. You'll receive an email notification once we respond.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Message Details Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h6 class="mb-0">Message Details</h6>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-6 text-muted" style="font-size: 0.85rem;">Status</dt>
                        <dd class="col-sm-6">
                            @if ($contact->responded_at)
                                <span class="badge bg-success">Answered</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </dd>

                        <dt class="col-sm-6 text-muted" style="font-size: 0.85rem;">Inquiry Type</dt>
                        <dd class="col-sm-6">
                            <small>{{ $contact->inquiry_type }}</small>
                        </dd>

                        <dt class="col-sm-6 text-muted" style="font-size: 0.85rem;">Submitted</dt>
                        <dd class="col-sm-6">
                            <small>{{ $contact->created_at->format('M d, Y') }}</small>
                        </dd>

                        <dt class="col-sm-6 text-muted" style="font-size: 0.85rem;">Name</dt>
                        <dd class="col-sm-6">
                            <small>{{ $contact->name }}</small>
                        </dd>

                        <dt class="col-sm-6 text-muted" style="font-size: 0.85rem;">Email</dt>
                        <dd class="col-sm-6">
                            <small>{{ $contact->email }}</small>
                        </dd>

                        @if ($contact->responded_at)
                            <dt class="col-sm-6 text-muted" style="font-size: 0.85rem;">Responded</dt>
                            <dd class="col-sm-6">
                                <small>{{ $contact->responded_at->format('M d, Y') }}</small>
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <h6 class="mb-2">Need More Help?</h6>
                    <p class="small text-muted mb-3">
                        If you have additional questions about this response, feel free to submit another inquiry.
                    </p>
                    <a href="{{ route('contact') }}" class="btn btn-sm btn-primary w-100">
                        <i class="fas fa-paper-plane"></i> Submit New Inquiry
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
