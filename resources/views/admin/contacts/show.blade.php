@extends('layouts.admin')

@section('title', 'Contact Message - Admin Panel')

@section('page-title', 'View Contact Message')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-envelope-open me-2"></i>Message from {{ $contact->name }}
        </h1>
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Back to Messages
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Message Details Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">{{ $contact->subject }}</h5>
                        <small class="text-muted">{{ $contact->created_at->format('F j, Y \a\t g:i A') }}</small>
                    </div>
                    <div>
                        <span class="badge bg-{{ $contact->inquiry_type_badge_color }}">
                            {{ $contact->inquiry_type_display }}
                        </span>
                        @if($contact->responded_at)
                            <span class="badge bg-success ms-2">
                                <i class="fas fa-check me-1"></i>Responded
                            </span>
                        @elseif($contact->is_read)
                            <span class="badge bg-info ms-2">
                                <i class="fas fa-eye me-1"></i>Read
                            </span>
                        @else
                            <span class="badge bg-warning text-dark ms-2">
                                <i class="fas fa-envelope me-1"></i>Unread
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Sender Info -->
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">From</small>
                                <strong class="text-dark">{{ $contact->name }}</strong>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted d-block mb-1">Email Address</small>
                                <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                    {{ $contact->email }}
                                </a>
                            </div>
                        </div>
                        @if($contact->ip_address)
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">IP Address</small>
                                    <code class="text-muted">{{ $contact->ip_address }}</code>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Message Content -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3">
                            <i class="fas fa-comment me-2"></i>Message
                        </h6>
                        <div class="p-4 bg-white border rounded" style="line-height: 1.7; white-space: pre-wrap; word-wrap: break-word;">
                            {{ $contact->message }}
                        </div>
                    </div>

                    <!-- Response Section -->
                    @if($contact->responded_at)
                        <div class="alert alert-success border-0">
                            <h6 class="fw-bold mb-2">
                                <i class="fas fa-check-circle me-2"></i>Response Status
                            </h6>
                            <p class="mb-2">
                                <strong>Responded On:</strong> {{ $contact->responded_at->format('F j, Y \a\t g:i A') }}
                            </p>
                            @if($contact->response_notes)
                                <div class="mt-3 p-3 bg-white rounded">
                                    <strong class="d-block mb-2">Response Notes:</strong>
                                    <p class="text-muted mb-0" style="white-space: pre-wrap;">{{ $contact->response_notes }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Response Form -->
                        <div class="card bg-light border-0">
                            <div class="card-header bg-white">
                                <h6 class="mb-0 fw-bold">
                                    <i class="fas fa-reply me-2"></i>Add Response Notes
                                </h6>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('admin.contacts.respond', $contact->id) }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="response_notes" class="form-label">Response Notes</label>
                                        <textarea class="form-control @error('response_notes') is-invalid @enderror"
                                                  id="response_notes" name="response_notes" rows="5" required
                                                  placeholder="Add your response or notes here..."></textarea>
                                        @error('response_notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted d-block mt-2">
                                            These notes are for internal tracking only. Consider sending a direct email to {{ $contact->email }} for customer communication.
                                        </small>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check me-2"></i>Mark as Responded
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if(!$contact->is_read)
                            <form method="POST" action="{{ route('admin.contacts.mark-read', $contact->id) }}" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="fas fa-check me-2"></i>Mark as Read
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.contacts.mark-unread', $contact->id) }}" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-envelope me-2"></i>Mark as Unread
                                </button>
                            </form>
                        @endif

                        <a href="mailto:{{ $contact->email }}" class="btn btn-outline-success">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </a>

                        <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}"
                              onsubmit="return confirm('Are you sure you want to delete this message?')" class="d-grid">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>Delete Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Message Info -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Status</small>
                        @if($contact->responded_at)
                            <strong class="text-success">
                                <i class="fas fa-check-circle me-1"></i>Responded
                            </strong>
                        @elseif($contact->is_read)
                            <strong class="text-info">
                                <i class="fas fa-eye me-1"></i>Read
                            </strong>
                        @else
                            <strong class="text-warning">
                                <i class="fas fa-envelope me-1"></i>Unread
                            </strong>
                        @endif
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Inquiry Type</small>
                        <strong class="text-dark">{{ $contact->inquiry_type_display }}</strong>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted d-block mb-1">Submitted</small>
                        <strong class="text-dark">{{ $contact->created_at->format('M d, Y g:i A') }}</strong>
                    </div>

                    @if($contact->responded_at)
                        <div>
                            <small class="text-muted d-block mb-1">Responded</small>
                            <strong class="text-dark">{{ $contact->responded_at->format('M d, Y g:i A') }}</strong>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
