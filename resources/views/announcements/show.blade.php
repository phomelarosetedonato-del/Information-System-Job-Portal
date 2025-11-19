@extends('layouts.admin')

@section('title', 'View Announcement - Admin Panel')

@section('page-title', 'Announcement Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bullhorn"></i> {{ $announcement->title }}
        </h1>
        <div>
            <span class="badge bg-{{ $announcement->is_active ? 'success' : 'secondary' }} me-2">
                {{ $announcement->is_active ? 'Active' : 'Inactive' }}
            </span>
            <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Announcements
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Announcement Details
                    </h6>
                </div>
                <div class="card-body">
                        <!-- Announcement Content -->
                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title text-primary">Announcement Content</h5>
                                    <div class="announcement-content" style="white-space: pre-line; font-size: 1.1em; line-height: 1.6;">
                                        {{ $announcement->content }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Announcement Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Announcement Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-borderless">
                                            <tr>
                                                <td class="fw-bold" style="width: 40%">Status:</td>
                                                <td>
                                                    <span class="badge bg-{{ $announcement->is_active ? 'success' : 'secondary' }}">
                                                        {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Created By:</td>
                                                <td>{{ $announcement->creator->name ?? 'System Admin' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Created Date:</td>
                                                <td>{{ $announcement->created_at->format('F d, Y \a\t h:i A') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Last Updated:</td>
                                                <td>{{ $announcement->updated_at->format('F d, Y \a\t h:i A') }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-warning text-dark">
                                        <h6 class="mb-0">Visibility</h6>
                                    </div>
                                    <div class="card-body">
                                        @if($announcement->is_active)
                                            <div class="alert alert-success">
                                                <i class="fas fa-eye"></i>
                                                <strong>This announcement is visible</strong> to all PWD users on their dashboard.
                                            </div>
                                        @else
                                            <div class="alert alert-secondary">
                                                <i class="fas fa-eye-slash"></i>
                                                <strong>This announcement is hidden</strong> from PWD users.
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                            <div>
                                <a href="{{ route('admin.announcements.edit', $announcement->id) }}" class="btn btn-primary">
                                    <i class="fas fa-edit"></i> Edit Announcement
                                </a>
                                <form action="{{ route('admin.announcements.destroy', $announcement->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this announcement? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
