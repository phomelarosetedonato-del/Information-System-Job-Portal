@extends('layouts.app')

@section('title', 'Accessibility Settings - PWD System')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-universal-access me-2"></i>
                        Accessibility Settings
                    </h4>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <p class="text-muted mb-4">Customize your browsing experience to suit your accessibility needs.</p>

                    <!-- Use direct URLs instead of named routes -->
                    <form method="POST" action="/accessibility/preferences">
                        @csrf

                        <!-- Text Size Settings -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-text-height me-2 text-primary"></i>
                                    Text Size & Display
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
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

                            <div class="col-md-6 mb-3">
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
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-desktop me-2 text-success"></i>
                                    Layout & Motion
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
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

                            <div class="col-md-6 mb-3">
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
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="border-bottom pb-2 mb-3">
                                    <i class="fas fa-low-vision me-2 text-info"></i>
                                    Focus & Screen Reader
                                </h5>
                            </div>

                            <div class="col-md-6 mb-3">
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

                            <div class="col-md-6 mb-3">
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
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
                                    </a>
                                    <div>
                                        <a href="/accessibility/reset"
                                           class="btn btn-outline-danger me-2"
                                           onclick="return confirm('Are you sure you want to reset all accessibility settings to default?')">
                                            <i class="fas fa-undo me-1"></i> Reset to Defaults
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
