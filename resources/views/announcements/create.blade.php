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
                            <button type="submit" class="btn btn-primary">
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

@section('scripts')
    @parent

    <script>
        // Character counter for content
        document.getElementById('content').addEventListener('input', function() {
            const content = this.value;
            const charCount = content.length;

            let countText = document.getElementById('charCount');
            if (!countText) {
                countText = document.createElement('small');
                countText.id = 'charCount';
                countText.className = 'form-text text-muted';
                this.parentNode.appendChild(countText);
            }

            countText.textContent = `${charCount} characters`;
        });

        // Auto-save draft to localStorage
        const titleInput = document.getElementById('title');
        const contentInput = document.getElementById('content');

        // Load draft on page load
        window.addEventListener('load', function() {
            const draftTitle = localStorage.getItem('announcement_draft_title');
            const draftContent = localStorage.getItem('announcement_draft_content');

            if (draftTitle && !titleInput.value) {
                titleInput.value = draftTitle;
            }
            if (draftContent && !contentInput.value) {
                contentInput.value = draftContent;
            }
        });

        // Save draft on input
        titleInput.addEventListener('input', function() {
            localStorage.setItem('announcement_draft_title', this.value);
        });

        contentInput.addEventListener('input', function() {
            localStorage.setItem('announcement_draft_content', this.value);
        });

        // Clear draft on successful submit
        document.querySelector('form').addEventListener('submit', function() {
            localStorage.removeItem('announcement_draft_title');
            localStorage.removeItem('announcement_draft_content');
        });
    </script>
@endsection
