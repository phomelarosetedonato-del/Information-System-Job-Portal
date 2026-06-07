@extends('layouts.admin')

@section('title', 'Contact Messages - Admin Panel')

@section('page-title', 'Contact Messages')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-envelope me-2"></i>Contact Messages
        </h1>
        <div>
            <a href="{{ route('admin.contacts.export') }}" class="btn btn-secondary btn-sm" title="Export to CSV">
                <i class="fas fa-download me-1"></i>Export
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="h4 text-primary mb-1">{{ $contacts->total() }}</h3>
                    <p class="text-muted mb-0 small">Total Messages</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="h4 text-warning mb-1">{{ $unreadCount }}</h3>
                    <p class="text-muted mb-0 small">Unread Messages</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="h4 text-info mb-1">{{ $unrespondedCount }}</h3>
                    <p class="text-muted mb-0 small">Unresponded Messages</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.contacts.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Name, email, or message..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Messages</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
                        <option value="responded" {{ request('status') === 'responded' ? 'selected' : '' }}>Responded</option>
                        <option value="unresponded" {{ request('status') === 'unresponded' ? 'selected' : '' }}>Unresponded</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-semibold">Inquiry Type</label>
                    <select name="inquiry_type" class="form-select form-select-sm">
                        <option value="">All Types</option>
                        @foreach($inquiryTypes as $key => $label)
                            <option value="{{ $key }}" {{ request('inquiry_type') === $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm flex-grow-1">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>Messages
            </h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 5%;"><input type="checkbox" id="selectAll" class="form-check-input"></th>
                        <th style="width: 15%;">From</th>
                        <th style="width: 15%;">Email</th>
                        <th style="width: 15%;">Inquiry Type</th>
                        <th style="width: 30%;">Subject</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Date</th>
                        <th style="width: 5%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contacts as $contact)
                        <tr class="{{ !$contact->is_read ? 'table-light' : '' }}">
                            <td>
                                <input type="checkbox" class="form-check-input message-checkbox" value="{{ $contact->id }}">
                            </td>
                            <td>
                                <strong>{{ $contact->name }}</strong>
                                @if(!$contact->is_read)
                                    <span class="badge bg-warning ms-2" title="Unread">New</span>
                                @endif
                            </td>
                            <td>
                                <code class="text-muted small">{{ $contact->email }}</code>
                            </td>
                            <td>
                                <span class="badge bg-{{ $contact->inquiry_type_badge_color }}">
                                    {{ $contact->inquiry_type_display }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="text-decoration-none">
                                    {{ Str::limit($contact->subject, 40) }}
                                </a>
                            </td>
                            <td>
                                @if($contact->responded_at)
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Responded
                                    </span>
                                @elseif($contact->is_read)
                                    <span class="badge bg-info">
                                        <i class="fas fa-eye me-1"></i>Read
                                    </span>
                                @else
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-envelope me-1"></i>Unread
                                    </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $contact->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.contacts.show', $contact->id) }}" class="btn btn-outline-primary"
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}"
                                          style="display: inline;" onsubmit="return confirm('Delete this message?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                <p>No contact messages found.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $contacts->links() }}
    </div>
</div>

<style>
.table-hover tbody tr:hover {
    background-color: #f8f9fa !important;
}
</style>
@endsection
