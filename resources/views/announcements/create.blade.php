@extends('layouts.admin')

@section('title', 'Create New Announcement - Admin Panel')

@section('page-title', 'Create New Announcement')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-bullhorn"></i> Create New Announcement
        </h1>
        <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Announcements
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="fas fa-exclamation-triangle"></i> Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-10 offset-lg-1">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Announcement Details
                    </h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.announcements.store') }}">
                        @csrf

                        <!-- Announcement Information -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fas fa-bullhorn"></i> Announcement Information
                            </h5>

                            <!-- Title -->
                            <div class="mb-3">
                                <label for="title" class="form-label">
                                    Announcement Title <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                       class="form-control @error('title') is-invalid @enderror"
                                       id="title"
                                       name="title"
                                       value="{{ old('title') }}"
                                       required
                                       placeholder="Enter announcement title">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Create a clear and descriptive title for your announcement.
                                </small>
                            </div>

                            <!-- Content -->
                            <div class="mb-3">
                                <label for="content" class="form-label">
                                    Announcement Content <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control @error('content') is-invalid @enderror"
                                          id="content"
                                          name="content"
                                          rows="10"
                                          required
                                          placeholder="Enter announcement content...">{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Write the full content of your announcement. You can use multiple paragraphs.
                                </small>
                            </div>
                        </div>

                        <!-- Visibility Settings -->
                        <div class="mb-4">
                            <h5 class="text-primary border-bottom pb-2">
                                <i class="fas fa-eye"></i> Visibility Settings
                            </h5>

                            <!-- Active Status -->
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="is_active"
                                           name="is_active"
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <strong>Make this announcement active</strong>
                                        <span class="text-muted d-block">When active, this announcement will be visible to all PWD users on their dashboard</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Active announcements will be displayed prominently on the PWD dashboard</li>
                                <li>You can edit or deactivate announcements anytime after creation</li>
                                <li>Inactive announcements will not be visible to users but remain in the system</li>
                                <li>The announcement will show your name and the creation date</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createConfirmModal">
                                <i class="fas fa-paper-plane"></i> Create Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Create Announcement Confirmation Modal -->
<div class="modal fade" id="createConfirmModal" tabindex="-1" aria-labelledby="createConfirmLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-primary">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createConfirmLabel">
                    <i class="fas fa-bullhorn me-2"></i>Create Announcement?
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">This announcement will be <strong>sent to all PWD users</strong>:</p>
                <div class="bg-light p-3 rounded mb-3">
                    <p class="mb-2"><strong>Title:</strong> <span id="previewTitle"></span></p>
                    <p class="mb-2"><strong>Content Preview:</strong></p>
                    <div class="bg-white p-2 rounded" style="max-height: 200px; overflow-y: auto;">
                        <span id="previewContent"></span>
                    </div>
                </div>
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-envelope me-2"></i>
                    <strong>Notifications will be sent via:</strong>
                    <ul class="mb-0 mt-2">
                        <li>📧 Email to all PWD users</li>
                        <li>📱 Dashboard notifications</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary" id="confirmCreateBtn">
                    <i class="fas fa-paper-plane me-1"></i>Create & Notify Users
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const createConfirmModal = document.getElementById('createConfirmModal');
            const confirmCreateBtn = document.getElementById('confirmCreateBtn');
            const titleInput = document.getElementById('title');
            const contentInput = document.getElementById('content');

            // Update preview when modal opens
            createConfirmModal.addEventListener('show.bs.modal', function() {
                const title = titleInput.value || 'No title provided';
                const content = contentInput.value || 'No content provided';

                document.getElementById('previewTitle').textContent = title;
                document.getElementById('previewContent').textContent = content;

                // Validate before opening modal
                if (!title.trim() || !content.trim()) {
                    alert('Please fill in both title and content before creating the announcement.');
                    this.style.display = 'none';
                }
            });

            // Handle confirmation
            confirmCreateBtn.addEventListener('click', function() {
                if (!titleInput.value.trim()) {
                    alert('Title is required.');
                    return;
                }
                if (!contentInput.value.trim()) {
                    alert('Content is required.');
                    return;
                }

                // Submit the form
                form.submit();
            });
        });
    </script>
@endsection
