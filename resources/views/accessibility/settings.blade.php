@extends('layouts.app')

@section('title', 'Accessibility Settings - PWD System')

@section('content')
<div class="container-fluid py-3 py-md-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0 h5 h4-md">
                        <i class="fas fa-universal-access me-2"></i>
                        Accessibility Settings
                    </h4>
                </div>

                <div class="card-body p-3 p-md-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3 mb-md-4" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class="text-muted mb-3 mb-md-4 small">Customize your browsing experience to suit your accessibility needs.</p>

                    <!-- Use direct URLs instead of named routes -->
                    <form method="POST" action="/accessibility/preferences">
                        @csrf

                        <!-- Text Size Settings -->
                        <div class="row mb-3 mb-md-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 h6 h5-md">
                                    <i class="fas fa-text-height me-2 text-primary"></i>
                                    Text Size & Display
                                </h5>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="font_size" class="form-label fw-bold">Font Size</label>
                                <select class="form-select @error('font_size') is-invalid @enderror"
                                        id="font_size" name="font_size">
                                    <option value="small" {{ old('font_size', $preferences['font_size']) == 'small' ? 'selected' : '' }}>Small</option>
                                    <option value="medium" {{ old('font_size', $preferences['font_size']) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="large" {{ old('font_size', $preferences['font_size']) == 'large' ? 'selected' : '' }}>Large</option>
                                    <option value="xlarge" {{ old('font_size', $preferences['font_size']) == 'xlarge' ? 'selected' : '' }}>Extra Large</option>
                                </select>
                                @error('font_size')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Adjust the text size to your preference.</div>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <label for="contrast" class="form-label fw-bold">Color Contrast</label>
                                <select class="form-select @error('contrast') is-invalid @enderror"
                                        id="contrast" name="contrast">
                                    <option value="normal" {{ old('contrast', $preferences['contrast']) == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="high" {{ old('contrast', $preferences['contrast']) == 'high' ? 'selected' : '' }}>High Contrast</option>
                                    <option value="very-high" {{ old('contrast', $preferences['contrast']) == 'very-high' ? 'selected' : '' }}>Very High Contrast</option>
                                </select>
                                @error('contrast')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Choose a color scheme that works best for you.</div>
                            </div>
                        </div>

                        <!-- Layout & Motion -->
                        <div class="row mb-3 mb-md-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 h6 h5-md">
                                    <i class="fas fa-desktop me-2 text-success"></i>
                                    Layout & Motion
                                </h5>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('simplified_layout') is-invalid @enderror"
                                           type="checkbox" id="simplified_layout" name="simplified_layout"
                                           value="1" {{ old('simplified_layout', $preferences['simplified_layout']) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="simplified_layout">
                                        Simplified Layout
                                    </label>
                                    @error('simplified_layout')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Use a cleaner, simplified interface with less visual clutter.</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('reduced_motion') is-invalid @enderror"
                                           type="checkbox" id="reduced_motion" name="reduced_motion"
                                           value="1" {{ old('reduced_motion', $preferences['reduced_motion']) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="reduced_motion">
                                        Reduced Motion
                                    </label>
                                    @error('reduced_motion')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Minimize animations and transitions for better focus.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Focus & Screen Reader -->
                        <div class="row mb-3 mb-md-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3 h6 h5-md">
                                    <i class="fas fa-low-vision me-2 text-info"></i>
                                    Focus & Screen Reader
                                </h5>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('high_visibility_focus') is-invalid @enderror"
                                           type="checkbox" id="high_visibility_focus" name="high_visibility_focus"
                                           value="1" {{ old('high_visibility_focus', $preferences['high_visibility_focus']) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="high_visibility_focus">
                                        High Visibility Focus
                                    </label>
                                    @error('high_visibility_focus')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Enhance focus indicators for better visibility.</div>
                                </div>
                            </div>

                            <div class="col-12 col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input @error('screen_reader_optimized') is-invalid @enderror"
                                           type="checkbox" id="screen_reader_optimized" name="screen_reader_optimized"
                                           value="1" {{ old('screen_reader_optimized', $preferences['screen_reader_optimized']) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-bold" for="screen_reader_optimized">
                                        Screen Reader Optimized
                                    </label>
                                    @error('screen_reader_optimized')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optimize the site for screen reader compatibility.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-3 mt-md-4">
                            <div class="col-12">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                                    </a>
                                    <div class="d-flex flex-column flex-sm-row gap-2">
                                        <a href="/accessibility/reset"
                                           class="btn btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to reset all accessibility settings to default?')">
                                            <i class="fas fa-undo me-1"></i> <span class="d-none d-sm-inline">Reset to Defaults</span><span class="d-inline d-sm-none">Reset</span>
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i> Save Settings
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Responsive heading sizes */
    .h4-md {
        font-size: 1.25rem;
    }
    .h5-md {
        font-size: 1.125rem;
    }
    @media (min-width: 768px) {
        .h4-md {
            font-size: 1.5rem;
        }
        .h5-md {
            font-size: 1.25rem;
        }
    }

    /* Better form styling */
    .form-check-input {
        cursor: pointer;
        width: 3rem;
        height: 1.5rem;
        flex-shrink: 0;
    }

    .form-check-label {
        cursor: pointer;
        user-select: none;
        padding-left: 0.5rem;
    }

    .form-check {
        display: flex;
        align-items: center;
        min-height: 3rem;
        padding: 0.75rem;
        background: rgba(0, 0, 0, 0.02);
        border-radius: 8px;
        transition: background 0.2s ease;
    }

    .form-check:hover {
        background: rgba(0, 0, 0, 0.04);
    }

    /* Mobile optimization */
    @media (max-width: 767px) {
        .container-fluid {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .card {
            border-radius: 8px;
        }

        .card-header h4 {
            font-size: 1.1rem;
        }

        .btn {
            padding: 0.625rem 1rem;
            font-size: 0.9rem;
            min-height: 44px;
            width: 100%;
        }

        .btn-group-mobile {
            width: 100%;
        }

        .form-label {
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .form-select, .form-control {
            font-size: 1rem;
            padding: 0.75rem;
            min-height: 48px;
            border-radius: 8px;
        }

        .form-text {
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        .form-check {
            min-height: 4rem;
            padding: 1rem;
        }

        .form-check-label {
            font-size: 0.95rem;
            line-height: 1.4;
        }

        .form-check-input {
            width: 3.5rem;
            height: 1.75rem;
            margin-right: 0.5rem;
        }

        .border-bottom {
            margin-bottom: 1rem !important;
        }

        .col-12.col-md-6 {
            margin-bottom: 1rem !important;
        }
    }

    /* Tablet optimization */
    @media (min-width: 768px) and (max-width: 991px) {
        .form-select, .form-control {
            font-size: 0.95rem;
        }
    }

    /* Better section spacing */
    .border-bottom {
        border-color: rgba(0, 0, 0, 0.1) !important;
    }

    /* Card styling */
    .card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
    }

    .card-header {
        border-bottom: 2px solid rgba(255, 255, 255, 0.2);
    }

    /* Form controls */
    .form-select, .form-control {
        border-radius: 8px;
        transition: all 0.3s ease;
        border: 2px solid #dee2e6;
    }

    .form-select:focus, .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    /* Switch styling */
    .form-switch .form-check-input {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3e%3c/svg%3e");
        transition: all 0.2s ease;
    }

    .form-switch .form-check-input:checked {
        background-color: #0d6efd;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }

    /* Touch-friendly improvements */
    @media (hover: none) and (pointer: coarse) {
        .btn {
            min-height: 48px;
        }

        .form-select, .form-control {
            min-height: 52px;
            font-size: 16px; /* Prevents zoom on iOS */
        }

        .form-check-input {
            width: 3.5rem;
            height: 1.75rem;
        }
    }

    /* Section headers */
    .row h5 {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .row h5 i {
        flex-shrink: 0;
    }

    /* Alert responsiveness */
    .alert {
        border-radius: 8px;
    }

    @media (max-width: 767px) {
        .alert {
            font-size: 0.9rem;
            padding: 0.75rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Apply saved settings from widget
    const savedFontSize = localStorage.getItem('accessibility-font-size');
    const savedContrast = localStorage.getItem('accessibility-contrast');

    if (savedFontSize) {
        document.getElementById('font_size').value = savedFontSize;
    }
    if (savedContrast) {
        document.getElementById('contrast').value = savedContrast;
    }

    // Sync with localStorage on change
    document.getElementById('font_size').addEventListener('change', function() {
        localStorage.setItem('accessibility-font-size', this.value);
        document.body.className = document.body.className.replace(/font-size-\w+/g, '');
        document.body.classList.add('font-size-' + this.value);
    });

    document.getElementById('contrast').addEventListener('change', function() {
        localStorage.setItem('accessibility-contrast', this.value);
        document.body.classList.remove('contrast-normal', 'contrast-high', 'contrast-very-high');
        if (this.value !== 'normal') {
            document.body.classList.add('contrast-' + this.value);
        }
    });

    // Form validation
    document.querySelector('form').addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
    });
});
</script>
@endsection
