@extends('layouts.admin')

@section('title', 'Admin Notifications - PWD System')

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header -->
    <div class="dashboard-header bg-white border-bottom py-4">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="h3 mb-2 text-dark">
                        <i class="fas fa-bell me-2 text-primary"></i>
                        Notifications Center
                    </h1>
                    <p class="mb-0 text-muted">Manage all system notifications and alerts</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="btn-group">
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-check-double me-1"></i> Mark All Read
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('notifications.clear') }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to clear all notifications?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-1"></i> Clear All
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Notification Stats -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-bell fa-2x text-primary"></i>
                        </div>
                        <h3 class="h4 text-primary mb-1">{{ $notifications->total() }}</h3>
                        <p class="text-muted mb-0">Total Notifications</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-envelope fa-2x text-warning"></i>
                        </div>
                        <h3 class="h4 text-warning mb-1">{{ auth()->user()->unreadNotifications->count() }}</h3>
                        <p class="text-muted mb-0">Unread Notifications</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-briefcase fa-2x text-info"></i>
                        </div>
                        <h3 class="h4 text-info mb-1">{{ \App\Models\JobApplication::where('status', 'pending')->count() }}</h3>
                        <p class="text-muted mb-0">Pending Applications</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body text-center py-4">
                        <div class="mb-3">
                            <i class="fas fa-user-tie fa-2x text-success"></i>
                        </div>
                        <h3 class="h4 text-success mb-1">{{ \App\Models\User::where('employer_verification_status', 'pending')->count() }}</h3>
                        <p class="text-muted mb-0">Pending Verifications</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Recent Notifications
                    </h5>
                    <span class="badge bg-primary rounded-pill">{{ $notifications->total() }}</span>
                </div>
            </div>

            <div class="card-body p-0">
                @if($notifications->count() > 0)
                    <div class="notification-list">
                        @foreach($notifications as $notification)
                            <div class="notification-card {{ $notification->read_at ? 'read' : 'unread' }} border-bottom p-4 cursor-pointer"
                                 data-bs-toggle="modal"
                                 data-bs-target="#notificationModal{{ $loop->index }}"
                                 onclick="markAsRead('{{ $notification->id }}')">
                                <div class="d-flex align-items-start gap-3">
                                    <!-- Notification Icon -->
                                    <div class="flex-shrink-0">
                                        @if(!$notification->read_at)
                                            <div class="notification-unread-badge">
                                                <i class="fas fa-circle-notch fa-spin text-primary"></i>
                                            </div>
                                        @else
                                            <div class="notification-icon-bg">
                                                @php
                                                    $type = $notification->data['type'] ?? 'system';
                                                    $iconClass = match($type) {
                                                        'new_job_application' => 'fas fa-file-alt text-warning',
                                                        'application_approved' => 'fas fa-check-circle text-success',
                                                        'application_rejected' => 'fas fa-times-circle text-danger',
                                                        'application_shortlisted' => 'fas fa-star text-info',
                                                        default => 'fas fa-bell text-secondary'
                                                    };
                                                @endphp
                                                <i class="{{ $iconClass }}"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Notification Content -->
                                    <div class="flex-grow-1 min-width-0">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1 fw-bold text-dark">
                                                    {{ $notification->data['message'] ?? 'Notification' }}
                                                </h6>
                                                <small class="text-muted">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <span class="badge
                                                @if($notification->data['type'] == 'new_job_application') bg-warning
                                                @elseif($notification->data['type'] == 'application_approved') bg-success
                                                @elseif($notification->data['type'] == 'application_rejected') bg-danger
                                                @elseif($notification->data['type'] == 'application_shortlisted') bg-info
                                                @else bg-secondary @endif
                                                text-white">
                                                {{ str_replace('_', ' ', ucfirst($notification->data['type'] ?? 'system')) }}
                                            </span>
                                        </div>

                                        @if(isset($notification->data['job_title']))
                                            <p class="mb-2 text-muted small">
                                                <i class="fas fa-briefcase me-1"></i>
                                                <strong>{{ $notification->data['job_title'] }}</strong>
                                                @if(isset($notification->data['applicant_name']))
                                                    - <strong>{{ $notification->data['applicant_name'] }}</strong>
                                                @endif
                                            </p>
                                        @endif

                                        @if(isset($notification->data['details']))
                                            <p class="mb-0 text-muted text-truncate">{{ $notification->data['details'] }}</p>
                                        @endif
                                    </div>

                                    <!-- Action Buttons (prevent modal on click) -->
                                    <div class="flex-shrink-0 ms-3" onclick="event.stopPropagation()">
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if(!$notification->read_at)
                                                <form method="POST" action="{{ route('notifications.mark-read', $notification->id) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-primary" title="Mark as read">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if(isset($notification->data['url']) && $notification->data['url'] != '#')
                                                <a href="{{ $notification->data['url'] }}" class="btn btn-outline-info" title="View Details" target="_blank">
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            @endif

                                            <form method="POST" action="{{ route('notifications.destroy', $notification->id) }}" class="d-inline"
                                                  onsubmit="return confirm('Delete this notification?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notification Detail Modal -->
                            <div class="modal fade" id="notificationModal{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow-lg">
                                        <!-- Modal Header -->
                                        <div class="modal-header bg-gradient border-0 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h5 class="modal-title fw-bold mb-2">
                                                            {{ $notification->data['message'] ?? 'Notification' }}
                                                        </h5>
                                                        <small class="text-white opacity-75">
                                                            <i class="fas fa-clock me-1"></i>
                                                            {{ $notification->created_at->format('F j, Y \a\t g:i A') }}
                                                        </small>
                                                    </div>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Body -->
                                        <div class="modal-body p-4">
                                            <div class="notification-detail-content">
                                                <!-- Type Badge -->
                                                <div class="mb-3">
                                                    <span class="badge
                                                        @if($notification->data['type'] == 'new_job_application') bg-warning
                                                        @elseif($notification->data['type'] == 'application_approved') bg-success
                                                        @elseif($notification->data['type'] == 'application_rejected') bg-danger
                                                        @elseif($notification->data['type'] == 'application_shortlisted') bg-info
                                                        @else bg-secondary @endif
                                                        text-white fs-6">
                                                        {{ str_replace('_', ' ', ucfirst($notification->data['type'] ?? 'system')) }}
                                                    </span>
                                                    @if(!$notification->read_at)
                                                        <span class="badge bg-light text-dark ms-2">
                                                            <i class="fas fa-envelope me-1"></i>Unread
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Main Message -->
                                                <div class="mb-4">
                                                    <h6 class="fw-bold text-dark mb-3">Message</h6>
                                                    <p class="text-muted lh-lg">
                                                        {{ $notification->data['message'] ?? 'No message available' }}
                                                    </p>
                                                </div>

                                                <!-- Job Details -->
                                                @if(isset($notification->data['job_title']))
                                                    <div class="mb-4 p-3 bg-light rounded-3">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="fas fa-briefcase me-2 text-primary"></i>Job Information
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-2">
                                                                <small class="text-muted d-block">Position Title</small>
                                                                <strong class="text-dark">{{ $notification->data['job_title'] }}</strong>
                                                            </div>
                                                            @if(isset($notification->data['company']))
                                                                <div class="col-md-6 mb-2">
                                                                    <small class="text-muted d-block">Company</small>
                                                                    <strong class="text-dark">{{ $notification->data['company'] }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Applicant Details -->
                                                @if(isset($notification->data['applicant_name']))
                                                    <div class="mb-4 p-3 bg-light rounded-3">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="fas fa-user me-2 text-primary"></i>Applicant Information
                                                        </h6>
                                                        <div class="row">
                                                            <div class="col-md-6 mb-2">
                                                                <small class="text-muted d-block">Name</small>
                                                                <strong class="text-dark">{{ $notification->data['applicant_name'] }}</strong>
                                                            </div>
                                                            @if(isset($notification->data['applicant_email']))
                                                                <div class="col-md-6 mb-2">
                                                                    <small class="text-muted d-block">Email</small>
                                                                    <strong class="text-dark">{{ $notification->data['applicant_email'] }}</strong>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Details Section -->
                                                @if(isset($notification->data['details']))
                                                    <div class="mb-4">
                                                        <h6 class="fw-bold text-dark mb-3">
                                                            <i class="fas fa-info-circle me-2 text-primary"></i>Additional Details
                                                        </h6>
                                                        <p class="text-muted lh-lg">
                                                            {{ $notification->data['details'] }}
                                                        </p>
                                                    </div>
                                                @endif

                                                <!-- Status Information -->
                                                @if(isset($notification->data['status']))
                                                    <div class="mb-4 p-3 rounded-3" style="background:
                                                        @if($notification->data['status'] == 'approved') #d4edda
                                                        @elseif($notification->data['status'] == 'rejected') #f8d7da
                                                        @elseif($notification->data['status'] == 'shortlisted') #cfe2ff
                                                        @else #e7d4f5 @endif;">
                                                        <h6 class="fw-bold text-dark mb-2">
                                                            <i class="fas fa-circle-info me-2"></i>Current Status
                                                        </h6>
                                                        <p class="text-dark fw-semibold mb-0">
                                                            {{ ucfirst($notification->data['status']) }}
                                                        </p>
                                                    </div>
                                                @endif

                                                <!-- Metadata -->
                                                <div class="border-top pt-3 mt-4">
                                                    <small class="text-muted">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Notification ID: <code>{{ $notification->id }}</code>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Footer -->
                                        <div class="modal-footer border-top bg-light">
                                            @if(isset($notification->data['url']) && $notification->data['url'] != '#')
                                                <a href="{{ $notification->data['url'] }}" class="btn btn-primary" target="_blank">
                                                    <i class="fas fa-external-link-alt me-2"></i>View Full Details
                                                </a>
                                            @endif

                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                <i class="fas fa-times me-1"></i>Close
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="card-footer bg-light border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }}
                                of {{ $notifications->total() }} notifications
                            </div>
                            <nav>
                                {{ $notifications->onEachSide(1)->links() }}
                            </nav>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="py-4">
                            <i class="fas fa-bell-slash fa-4x text-muted mb-4"></i>
                            <h3 class="text-muted mb-3">No Notifications</h3>
                            <p class="text-muted mb-0">
                                You're all caught up! There are no notifications at the moment.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.notification-unread-badge {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    color: white;
    font-size: 18px;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.notification-icon-bg {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f4ff;
    border-radius: 8px;
    font-size: 18px;
}

.notification-card {
    transition: all 0.3s ease;
    background: #fff;
    cursor: pointer;
    user-select: none;
}

.notification-card.unread {
    background: linear-gradient(90deg, #f0f4ff 0%, #ffffff 100%);
    border-left: 4px solid #667eea !important;
}

.notification-card:hover {
    background-color: #f8f9fa;
    box-shadow: 0 2px 12px rgba(102, 126, 234, 0.15);
    transform: translateY(-1px);
}

.notification-card:active {
    transform: translateY(0);
}

/* Prevent interaction with buttons from triggering modal */
.notification-card .btn-group {
    pointer-events: auto;
}

.notification-card .btn-group button,
.notification-card .btn-group a {
    cursor: pointer;
}

/* Modal Styling */
.modal-content {
    border-radius: 12px;
    overflow: hidden;
}

.modal-header {
    padding: 1.5rem !important;
}

.modal-body {
    background: #f8f9fa;
    max-height: 60vh;
    overflow-y: auto;
}

.notification-detail-content {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
}

.notification-detail-content h6 {
    color: #2c3e50;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    margin-bottom: 1rem !important;
}

.notification-detail-content p {
    font-size: 0.95rem;
    line-height: 1.6;
}

.modal-footer {
    padding: 1rem 1.5rem !important;
    background: #f8f9fa !important;
    border-top: 1px solid #e9ecef !important;
}

.notification-list .border-bottom:last-child {
    border-bottom: none !important;
}

.badge {
    font-weight: 500;
    font-size: 0.7rem;
    padding: 0.35rem 0.6rem;
}

.min-width-0 {
    min-width: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .notification-card {
        padding: 1rem !important;
    }

    .notification-card .flex-shrink-0.ms-3 {
        margin-left: 0 !important;
        margin-top: 1rem;
    }

    .notification-card .d-flex {
        flex-direction: column;
    }

    .btn-group-sm {
        width: 100%;
    }

    .btn-group-sm .btn {
        flex: 1;
    }
}
</style>

<script>
function markAsRead(notificationId) {
    // Mark notification as read when modal is opened
    fetch(`/admin/notifications/${notificationId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    }).then(response => {
        if (response.ok) {
            // Find and update all notification cards with this ID
            const cards = document.querySelectorAll(`[onclick="markAsRead('${notificationId}')"]`);
            cards.forEach(card => {
                card.classList.remove('unread');
                card.classList.add('read');

                // Remove the unread spinning icon and replace with normal icon
                const unreadBadge = card.querySelector('.notification-unread-badge');
                if (unreadBadge) {
                    unreadBadge.remove();
                }
            });

            // Update the unread count badge in the sidebar if it exists
            const unreadBadge = document.querySelector('.notification-badge');
            if (unreadBadge) {
                const count = parseInt(unreadBadge.textContent);
                if (count > 0) {
                    unreadBadge.textContent = count - 1;
                    // Remove badge if count reaches 0
                    if (count - 1 === 0) {
                        unreadBadge.style.display = 'none';
                    }
                }
            }
        }
    }).catch(error => console.error('Error:', error));
}
</script>
@endsection
