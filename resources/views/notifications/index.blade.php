<!-- resources/views/notifications/index.blade.php -->

@extends('layouts.app')

@section('title', 'My Notifications')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-bell me-2"></i>My Notifications
                    </h4>
                    <div class="btn-group">
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="me-2">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-check-double me-1"></i>Mark All as Read
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('notifications.clear') }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to clear all notifications?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-1"></i>Clear All
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($notifications->count() > 0)
                        <div class="list-group">
                            @foreach($notifications as $notification)
                                <div class="list-group-item list-group-item-action {{ $notification->read() ? '' : 'list-group-item-primary' }}">
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="flex-grow-1 me-3">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <h6 class="mb-0">
                                                    @if($notification->data['type'] ?? false)
                                                        <span class="badge
                                                            @if($notification->data['type'] == 'job_application_status') bg-info
                                                            @elseif($notification->data['type'] == 'new_job_application') bg-warning
                                                            @elseif($notification->data['type'] == 'training_enrollment_status') bg-success
                                                            @else bg-secondary @endif me-2">
                                                            {{ str_replace('_', ' ', $notification->data['type']) }}
                                                        </span>
                                                    @endif
                                                    {{ $notification->data['message'] ?? 'Notification' }}
                                                </h6>
                                                @if(!$notification->read())
                                                    <span class="badge bg-danger">New</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $notification->created_at->format('M j, Y g:i A') }}
                                                ({{ $notification->created_at->diffForHumans() }})
                                            </small>
                                        </div>
                                        <div class="btn-group btn-group-sm">
                                            @if(isset($notification->data['url']) && $notification->data['url'] != '#')
                                                <a href="{{ $notification->data['url'] }}"
                                                   class="btn btn-outline-primary"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif
                                            @if(!$notification->read())
                                                <form action="{{ route('notifications.mark-read', $notification) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-success" title="Mark as Read">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('notifications.destroy', $notification) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger"
                                                        title="Delete Notification"
                                                        onclick="return confirm('Delete this notification?')">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 d-flex justify-content-between align-items-center">
                            <div class="text-muted">
                                Showing {{ $notifications->firstItem() }} to {{ $notifications->lastItem() }}
                                of {{ $notifications->total() }} notifications
                            </div>
                            <div>
                                {{ $notifications->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No notifications found</h5>
                            <p class="text-muted">You don't have any notifications at the moment.</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-home me-1"></i>Back to Dashboard
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item.list-group-item-primary {
    background-color: #e3f2fd;
    border-left: 4px solid #0d6efd;
}

.list-group-item {
    border-left: 4px solid transparent;
    transition: all 0.3s ease;
}

.list-group-item:hover {
    background-color: #f8f9fa;
}
</style>
@endsection
