@extends('layouts.app')

@section('title', 'Accessibility Features - PWD System')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h1 class="h4 mb-0">Accessibility Features</h1>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This system is designed to be accessible to all users, including those with disabilities.
                    </div>

                    <h2 class="h5 text-primary">Available Accessibility Features</h2>

                    <div class="row mt-4">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 text-primary">
                                        <i class="fas fa-language me-2"></i>Language Translation
                                    </h3>
                                    <p>Use the Google Translate widget in the accessibility toolbar to translate content between English and Tagalog.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 text-primary">
                                        <i class="fas fa-text-height me-2"></i>Text Size Adjustment
                                    </h3>
                                    <p>Use the A+, A-, and Reset buttons to adjust text size according to your preference.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 text-primary">
                                        <i class="fas fa-adjust me-2"></i>High Contrast Mode
                                    </h3>
                                    <p>Toggle high contrast mode for better visibility in different lighting conditions.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h3 class="h6 text-primary">
                                        <i class="fas fa-keyboard me-2"></i>Keyboard Navigation
                                    </h3>
                                    <p>Navigate the entire system using keyboard only. Use Tab to move and Enter to activate.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h2 class="h5 text-primary mt-4">Keyboard Shortcuts</h2>
                    <ul>
                        <li><kbd>Tab</kbd> - Navigate between elements</li>
                        <li><kbd>Enter</kbd> - Activate buttons and links</li>
                        <li><kbd>Escape</kbd> - Close modals and dialogs</li>
                        <li><kbd>Arrow Keys</kbd> - Navigate dropdown menus</li>
                    </ul>

                    <h2 class="h5 text-primary mt-4">Screen Reader Compatibility</h2>
                    <p>This system is compatible with screen readers like JAWS, NVDA, and VoiceOver. All images have alternative text, and interactive elements have proper ARIA labels.</p>

                    <div class="alert alert-warning mt-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Need Help?</strong> If you encounter any accessibility issues, please contact the system administrator.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
