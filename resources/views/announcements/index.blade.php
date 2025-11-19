@extends('layouts.admin')

@section('title', 'Announcements Management - Admin Panel')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bullhorn"></i> Announcements Management
        </h1>
        <div class="btn-group">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create New Announcement
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Announcements
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $announcements->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bullhorn fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Active Announcements
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $announcements->where('is_active', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Inactive Announcements
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $announcements->where('is_active', false)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye-slash fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Recent (This Week)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $announcements->where('created_at', '>=', now()->subWeek())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Announcements Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i> All Announcements
            </h6>
            <small class="text-muted">{{ $announcements->total() }} total announcements</small>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="25%">Title</th>
                            <th width="35%">Content Preview</th>
                            <th width="10%">Status</th>
                            <th width="10%">Created</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $index => $announcement)
                            <tr>
                                <td>{{ $announcements->firstItem() + $index }}</td>
                                <td>
                                    <strong>{{ $announcement->title }}</strong>
                                    @if($announcement->created_at->isToday())
                                        <span class="badge badge-info badge-sm ml-1">New</span>
                                    @endif
                                </td>
                                <td class="text-muted small">{{ Str::limit($announcement->content, 100) }}</td>
                                <td>
                                    <span class="badge badge-{{ $announcement->is_active ? 'success' : 'secondary' }}">
                                        <i class="fas fa-{{ $announcement->is_active ? 'eye' : 'eye-slash' }}"></i>
                                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $announcement->created_at->format('M d, Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $announcement->created_at->format('h:i A') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.announcements.show', $announcement->id) }}"
                                           class="btn btn-sm btn-info"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.announcements.destroy', $announcement->id) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Are you sure you want to delete this announcement? This action cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-bullhorn fa-3x mb-3 d-block"></i>
                                        <h5>No Announcements Yet</h5>
                                        <p class="mb-3">Get started by creating your first announcement.</p>
                                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i> Create First Announcement
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($announcements->hasPages())
                <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                    <div class="text-muted small">
                        Showing {{ $announcements->firstItem() ?? 0 }} to {{ $announcements->lastItem() ?? 0 }} of {{ $announcements->total() }} entries
                    </div>
                    <nav aria-label="Announcements pagination">
                        {{ $announcements->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
    @parent
    <style>
        /* Pagination styling for admin theme */
        .pagination {
            margin-bottom: 0;
        }
        .page-link {
            color: #4e73df;
            border-color: #dddfeb;
        }
        .page-link:hover {
            color: #224abe;
            background-color: #eaecf4;
            border-color: #dddfeb;
        }
        .page-item.active .page-link {
            background-color: #4e73df;
            border-color: #4e73df;
        }
        .page-item.disabled .page-link {
            color: #858796;
            background-color: #fff;
            border-color: #dddfeb;
        }
    </style>
@endsection

@section('scripts')
    @parent

    <script>
        // Confirm delete with enhanced message
        document.querySelectorAll('form[onsubmit]').forEach(form => {
            form.addEventListener('submit', function(e) {
                const announcementTitle = this.closest('tr').querySelector('strong').textContent;
                if (!confirm(`Are you sure you want to delete the announcement "${announcementTitle}"?\n\nThis action cannot be undone.`)) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        // Auto-hide success message after 5 seconds
        const successAlert = document.querySelector('.alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.remove(), 500);
            }, 5000);
        }
    </script>
@endsection
