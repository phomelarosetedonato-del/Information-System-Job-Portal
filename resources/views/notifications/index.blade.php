@extends('layouts.app')

@section('title', 'Inbox - PWD System')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-11 col-xl-10">
            <!-- Gmail-style header -->
            <div class="inbox-header bg-white rounded-top shadow-sm border-bottom">
                <div class="d-flex justify-content-between align-items-center p-3">
                    <div class="d-flex align-items-center gap-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="fas fa-inbox me-2 text-primary"></i>Inbox
                        </h4>
                        <span class="badge bg-primary rounded-pill">{{ $notifications->total() }}</span>
                    </div>
                    <div class="d-flex gap-2">
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm" title="Mark all as read">
                                    <i class="fas fa-check-double me-1"></i>
                                    <span class="d-none d-md-inline">Mark All Read</span>
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('notifications.clear') }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Are you sure you want to clear all notifications?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Clear all">
                                <i class="fas fa-trash me-1"></i>
                                <span class="d-none d-md-inline">Clear All</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3 mb-0" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3 mb-0" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Notifications List (Gmail style) -->
            <div class="inbox-body bg-white shadow-sm rounded-bottom">
                @if($notifications->count() > 0)
                    <div class="notification-list">
                        @foreach($notifications as $notification)
                            <div class="notification-item {{ $notification->read() ? 'read' : 'unread' }}"
                                 data-notification-id="{{ $notification->id }}"
                                 data-bs-toggle="modal"
                                 data-bs-target="#notificationModal{{ $notification->id }}"
                                 onclick="markAsRead('{{ $notification->id }}', {{ $notification->read() ? 'true' : 'false' }})">
                                <div class="notification-checkbox">
                                    <input type="checkbox" class="form-check-input" onclick="event.stopPropagation()">
                                </div>
                                <div class="notification-icon">
                                    @if(!$notification->read())
                                        <div class="unread-dot"></div>
                                    @else
                                        <i class="fas fa-envelope-open text-muted"></i>
                                    @endif
                                </div>
                                <div class="notification-content flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="notification-sender">
                                            <span class="fw-bold">
                                                @if($notification->data['type'] ?? false)
                                                    @if($notification->data['type'] == 'job_application_status')
                                                        <i class="fas fa-briefcase me-1 text-info"></i>Job Application
                                                    @elseif($notification->data['type'] == 'new_job_application')
                                                        <i class="fas fa-file-alt me-1 text-warning"></i>New Application
                                                    @elseif($notification->data['type'] == 'training_enrollment_status')
                                                        <i class="fas fa-graduation-cap me-1 text-success"></i>Training Update
                                                    @else
                                                        <i class="fas fa-bell me-1 text-secondary"></i>System
                                                    @endif
                                                @else
                                                    <i class="fas fa-bell me-1"></i>Notification
                                                @endif
                                            </span>
                                        </div>
                                        <div class="notification-time text-muted small">
                                            {{ $notification->created_at->format('M j') }}
                                        </div>
                                    </div>
                                    <div class="notification-subject">
                                        {{ $notification->data['message'] ?? 'Notification' }}
                                    </div>
                                    <div class="notification-preview text-muted small">
                                        @if(isset($notification->data['details']))
                                            {{ Str::limit($notification->data['details'], 80) }}
                                        @else
                                            Click to view details
                                        @endif
                                    </div>
                                </div>
                                <div class="notification-actions">
                                    <button type="button" class="btn btn-sm btn-link text-danger"
                                            onclick="event.stopPropagation(); deleteNotification('{{ $notification->id }}')"
                                            title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Modal for notification details (Gmail style) -->
                            <div class="modal fade" id="notificationModal{{ $notification->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header border-0 pb-0">
                                            <div class="w-100">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <h5 class="modal-title fw-bold">
                                                        @if($notification->data['type'] ?? false)
                                                            <span class="badge bg-{{
                                                                $notification->data['type'] == 'job_application_status' ? 'info' :
                                                                ($notification->data['type'] == 'new_job_application' ? 'warning' :
                                                                ($notification->data['type'] == 'training_enrollment_status' ? 'success' : 'secondary'))
                                                            }} me-2">
                                                                {{ ucwords(str_replace('_', ' ', $notification->data['type'])) }}
                                                            </span>
                                                        @endif
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="text-muted small mt-2">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ $notification->created_at->format('l, F j, Y \a\t g:i A') }}
                                                    <span class="ms-2">({{ $notification->created_at->diffForHumans() }})</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-body pt-3">
                                            <div class="notification-detail-message">
                                                <h6 class="fw-bold mb-3">{{ $notification->data['message'] ?? 'Notification' }}</h6>
                                                @if(isset($notification->data['details']))
                                                    <p class="mb-3">{{ $notification->data['details'] }}</p>
                                                @endif

                                                @if(isset($notification->data['job_title']))
                                                    <div class="alert alert-light border">
                                                        <strong><i class="fas fa-briefcase me-2"></i>Job Title:</strong> {{ $notification->data['job_title'] }}
                                                    </div>
                                                @endif

                                                @if(isset($notification->data['status']))
                                                    <div class="alert alert-{{
                                                        $notification->data['status'] == 'accepted' ? 'success' :
                                                        ($notification->data['status'] == 'rejected' ? 'danger' : 'info')
                                                    }}">
                                                        <strong><i class="fas fa-info-circle me-2"></i>Status:</strong>
                                                        {{ ucwords($notification->data['status']) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            @if(isset($notification->data['url']) && $notification->data['url'] != '#')
                                                <a href="{{ $notification->data['url'] }}" class="btn btn-primary">
                                                    <i class="fas fa-external-link-alt me-1"></i>View Details
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="inbox-footer p-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $notifications->firstItem() }}-{{ $notifications->lastItem() }} of {{ $notifications->total() }}
                            </div>
                            <div>
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">Your inbox is empty</h5>
                        <p class="text-muted">You don't have any notifications at the moment.</p>
                        <a href="{{ route('dashboard') }}" class="btn btn-primary mt-3">
                            <i class="fas fa-home me-1"></i>Back to Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden forms for actions -->
<form id="markReadForm" method="POST" style="display: none;">
    @csrf
</form>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
/* Gmail-inspired styling */
.inbox-header {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    background: #ffffff !important;
    padding: 1.25rem 1.5rem !important;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
    border-bottom: 1px solid #e0e0e0;
}

.inbox-header h4 {
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: -0.5px;
    color: #202124;
}

.inbox-header h4 i {
    color: #667eea;
}

.inbox-header .badge {
    font-size: 0.85rem;
    padding: 0.35rem 0.75rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border: none;
}

.inbox-header .btn {
    border: 2px solid #e0e0e0;
    background-color: #ffffff;
    color: #5f6368;
    font-weight: 600;
    transition: all 0.3s ease;
}

.inbox-header .btn-outline-primary {
    border-color: #667eea;
    color: #667eea;
}

.inbox-header .btn-outline-primary:hover {
    background-color: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.inbox-header .btn-outline-danger {
    border-color: #dc3545;
    color: #dc3545;
}

.inbox-header .btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

.inbox-body {
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
    min-height: 400px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
    border: 1px solid #e0e0e0;
    border-top: none;
}

.notification-list {
    display: flex;
    flex-direction: column;
}

.notification-item {
    display: flex;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid #e8eaed;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    gap: 14px;
    position: relative;
}

.notification-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 0;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    transition: width 0.25s ease;
}

.notification-item:hover::before {
    width: 4px;
}

.notification-item:hover {
    background-color: #f8f9fa;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transform: translateX(2px);
}

.notification-item.unread {
    background: linear-gradient(90deg, #f0f4ff 0%, #ffffff 100%);
    border-left: 4px solid #667eea;
    box-shadow: 0 1px 3px rgba(102, 126, 234, 0.1);
}

.notification-item.unread::before {
    width: 4px;
}

.notification-item.read {
    background-color: #fff;
    color: #5f6368;
}

.notification-item.read .notification-sender,
.notification-item.read .notification-subject {
    font-weight: normal !important;
}

.notification-checkbox {
    width: 24px;
    display: flex;
    align-items: center;
}

.notification-checkbox .form-check-input {
    width: 18px;
    height: 18px;
    border: 2px solid #dadce0;
    cursor: pointer;
    transition: all 0.2s ease;
}

.notification-checkbox .form-check-input:checked {
    background-color: #667eea;
    border-color: #667eea;
}

.notification-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: #f1f3f4;
    transition: all 0.3s ease;
}

.notification-item:hover .notification-icon {
    background-color: #e8eaed;
    transform: scale(1.1);
}

.unread-dot {
    width: 12px;
    height: 12px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.05);
    }
}

.notification-content {
    min-width: 0;
    flex-grow: 1;
}

.notification-sender {
    font-size: 14px;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.notification-sender i {
    font-size: 13px;
}

.notification-item.unread .notification-sender,
.notification-item.unread .notification-subject {
    font-weight: 600;
    color: #202124;
}

.notification-subject {
    font-size: 14.5px;
    color: #202124;
    margin-bottom: 4px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.4;
}

.notification-preview {
    font-size: 13px;
    color: #5f6368;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

.notification-time {
    font-size: 12px;
    white-space: nowrap;
    min-width: 60px;
    text-align: right;
    color: #5f6368;
    font-weight: 500;
}

.notification-actions {
    opacity: 0;
    transition: opacity 0.2s ease;
    display: flex;
    gap: 4px;
}

.notification-item:hover .notification-actions {
    opacity: 1;
}

.notification-actions .btn {
    padding: 6px 10px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.notification-actions .btn:hover {
    background-color: #fee;
    transform: scale(1.1);
}

/* Modal styling */
.modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
    overflow: hidden;
}

.modal-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 1.5rem;
}

.modal-header .modal-title {
    font-size: 1.1rem;
}

.modal-header .badge {
    font-size: 0.8rem;
    padding: 0.4rem 0.8rem;
    background-color: rgba(255, 255, 255, 0.25) !important;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.modal-header .text-muted {
    color: rgba(255, 255, 255, 0.9) !important;
}

.modal-header .btn-close {
    filter: brightness(0) invert(1);
    opacity: 0.8;
}

.modal-header .btn-close:hover {
    opacity: 1;
}

.modal-body {
    padding: 2rem;
}

.notification-detail-message {
    line-height: 1.7;
}

.notification-detail-message h6 {
    font-size: 1.15rem;
    color: #202124;
    font-weight: 600;
}

.notification-detail-message p {
    color: #5f6368;
    font-size: 0.95rem;
}

.notification-detail-message .alert {
    border-radius: 10px;
    border: none;
    font-size: 0.9rem;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.modal-footer {
    padding: 1.25rem 2rem;
    background-color: #f8f9fa;
}

.modal-footer .btn {
    padding: 0.625rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.modal-footer .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
}

.modal-footer .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

/* Inbox footer */
.inbox-footer {
    background-color: #f8f9fa;
    border-bottom-left-radius: 12px;
    border-bottom-right-radius: 12px;
}

/* Pagination improvements */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border-radius: 6px;
    margin: 0 2px;
    border: 1px solid #e8eaed;
    color: #5f6368;
    font-weight: 500;
}

.page-link:hover {
    background-color: #f8f9fa;
    border-color: #667eea;
    color: #667eea;
}

.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}

/* Empty state */
.text-center.py-5 {
    padding: 4rem 2rem !important;
}

.text-center.py-5 i {
    opacity: 0.4;
}

.text-center.py-5 .btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 0.75rem 2rem;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    transition: all 0.3s ease;
}

.text-center.py-5 .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
}

/* Alert improvements */
.alert {
    border-radius: 10px;
    border: none;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert-success {
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    color: #155724;
}

.alert-danger {
    background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
    color: #721c24;
}

/* Badge improvements */
.badge {
    font-weight: 500;
    padding: 0.35rem 0.75rem;
    border-radius: 6px;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }

    .inbox-header {
        border-radius: 8px 8px 0 0;
        padding: 1rem !important;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }

    .inbox-header h4 {
        font-size: 1.1rem;
    }

    .inbox-header h4 i {
        font-size: 1rem;
    }

    .inbox-header .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .inbox-header .d-flex.gap-2 {
        gap: 0.5rem !important;
    }

    .inbox-header .btn {
        font-size: 0.85rem;
        padding: 0.5rem 0.75rem;
        border-width: 1.5px;
    }

    .inbox-body {
        border-radius: 0 0 8px 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.12) !important;
        border-left: 1px solid #e0e0e0;
        border-right: 1px solid #e0e0e0;
    }

    .notification-item {
        padding: 14px 12px;
        gap: 10px;
    }

    .notification-item:hover {
        transform: none;
    }

    .notification-checkbox {
        display: none;
    }

    .notification-icon {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
    }

    .notification-content {
        flex: 1;
        min-width: 0;
    }

    .notification-time {
        font-size: 11px;
        min-width: 42px;
    }

    .notification-subject,
    .notification-sender {
        font-size: 13px;
    }

    .notification-preview {
        font-size: 12px;
    }

    .notification-actions {
        opacity: 1;
        margin-left: 4px;
    }

    .notification-actions .btn {
        padding: 8px 10px;
        min-width: 40px;
        min-height: 40px;
    }

    .modal-dialog {
        margin: 0.5rem;
    }

    .modal-content {
        border-radius: 12px;
    }

    .modal-header {
        padding: 1.25rem;
    }

    .modal-header .modal-title {
        font-size: 1rem;
    }

    .modal-header .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }

    .modal-header .text-muted {
        font-size: 0.8rem;
    }

    .modal-body {
        padding: 1.25rem;
    }

    .modal-body h6 {
        font-size: 1rem;
    }

    .modal-body p,
    .modal-body .alert {
        font-size: 0.9rem;
    }

    .modal-footer {
        padding: 1rem 1.25rem;
    }

    .modal-footer .btn {
        font-size: 0.9rem;
        padding: 0.5rem 1rem;
    }

    .inbox-footer {
        padding: 0.75rem !important;
        border-radius: 0 0 8px 8px;
    }

    .inbox-footer .text-muted {
        font-size: 0.8rem;
    }

    .pagination {
        font-size: 0.85rem;
    }

    .page-link {
        padding: 0.4rem 0.75rem;
    }

    .alert {
        font-size: 0.9rem;
        padding: 0.875rem 1rem;
        margin-top: 0.5rem !important;
    }
}

/* Touch improvements for mobile */
@media (hover: none) and (pointer: coarse) {
    .notification-item {
        padding: 16px 12px;
    }

    .notification-actions .btn {
        padding: 8px 12px;
        min-width: 44px;
        min-height: 44px;
    }
}
</style>

<script>
function markAsRead(notificationId, alreadyRead) {
    if (alreadyRead) return;

    const form = document.getElementById('markReadForm');
    form.action = `/notifications/${notificationId}/mark-read`;

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        }
    }).then(response => {
        if (response.ok) {
            const notificationItem = document.querySelector(`[data-notification-id="${notificationId}"]`);
            if (notificationItem) {
                notificationItem.classList.remove('unread');
                notificationItem.classList.add('read');

                const unreadDot = notificationItem.querySelector('.unread-dot');
                if (unreadDot) {
                    unreadDot.outerHTML = '<i class="fas fa-envelope-open text-muted"></i>';
                }
            }
        }
    });
}

function deleteNotification(notificationId) {
    if (!confirm('Delete this notification?')) return;

    const form = document.getElementById('deleteForm');
    form.action = `/notifications/${notificationId}`;
    form.submit();
}
</script>
@endsection
