<!-- Accessibility Widget -->
<div class="accessibility-widget">
    @php
        // Completely safe language preference handling
        $currentLanguage = 'en';

        // If $preferences exists, handle it safely regardless of its type
        if (isset($preferences)) {
            if (is_array($preferences) && isset($preferences['language']) && is_string($preferences['language'])) {
                $currentLanguage = $preferences['language'];
            } elseif (is_string($preferences)) {
                // If it's a string, try to decode it
                $decoded = json_decode($preferences, true);
                if (is_array($decoded) && isset($decoded['language']) && is_string($decoded['language'])) {
                    $currentLanguage = $decoded['language'];
                }
            }
        }

        // Always fallback to checking cookie directly
        if (isset($_COOKIE['accessibility_preferences'])) {
            try {
                $cookieValue = $_COOKIE['accessibility_preferences'];
                $decodedCookie = json_decode(base64_decode($cookieValue), true);
                if (is_array($decodedCookie) && isset($decodedCookie['language']) && is_string($decodedCookie['language'])) {
                    $currentLanguage = $decodedCookie['language'];
                }
            } catch (Exception $e) {
                // Use default 'en' if cookie parsing fails
                $currentLanguage = 'en';
            }
        }

        // Final safety check - ensure $currentLanguage is a string
        $currentLanguage = is_string($currentLanguage) ? $currentLanguage : 'en';
    @endphp

    <button class="accessibility-toggle" id="accessibilityToggle" aria-label="Accessibility Options">
        <i class="fas fa-universal-access"></i>
    </button>

    <div class="accessibility-panel" id="accessibilityPanel">
        <div class="panel-header">
            <div>
                <h4 class="mb-0"><i class="fas fa-universal-access me-2"></i>Accessibility</h4>
                <small class="opacity-75">Customize your viewing experience</small>
            </div>
            <button type="button" class="btn-close btn-close-white" id="closeAccessibilityPanel" aria-label="Close accessibility panel"></button>
        </div>

        <div class="panel-body">
            <!-- Language Toggle -->
            <div class="setting-group">
                <h6><i class="fas fa-language me-2"></i>Language</h6>
                <div class="btn-group-setting">
                    <button class="btn-setting language-btn {{ $currentLanguage === 'en' ? 'active' : '' }}" data-language="en">
                        <span class="language-flag">🇺🇸</span> English
                    </button>
                    <button class="btn-setting language-btn {{ $currentLanguage === 'tl' ? 'active' : '' }}" data-language="tl">
                        <span class="language-flag">🇵🇭</span> Tagalog
                    </button>
                </div>
                <small class="text-muted d-block mt-2" id="currentLanguageText">
                    {{ $currentLanguage === 'en' ? 'Current language: English' : 'Kasalukuyang wika: Tagalog' }}
                </small>
            </div>

            <!-- Text Size Settings -->
            <div class="setting-group">
                <h6><i class="fas fa-text-height me-2"></i>Text Size</h6>
                <div class="btn-group-setting">
                    <button class="btn-setting" data-size="small">Small</button>
                    <button class="btn-setting active" data-size="medium">Medium</button>
                    <button class="btn-setting" data-size="large">Large</button>
                    <button class="btn-setting" data-size="xlarge">X-Large</button>
                </div>
            </div>

            <!-- Contrast Settings -->
            <div class="setting-group">
                <h6><i class="fas fa-adjust me-2"></i>Color & Contrast</h6>
                <div class="btn-group-setting">
                    <button class="btn-setting active" data-contrast="normal">Normal</button>
                    <button class="btn-setting" data-contrast="high">High</button>
                    <button class="btn-setting" data-contrast="very-high">Very High</button>
                </div>
            </div>

            <!-- Quick Presets -->
            <div class="setting-group">
                <h6><i class="fas fa-magic me-2"></i>Quick Presets</h6>
                <div class="preset-card" data-preset="vision">
                    <div class="preset-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h6 class="mb-1">Low Vision</h6>
                    <small class="text-muted">Larger text, high contrast</small>
                </div>
                <div class="preset-card" data-preset="dyslexia">
                    <div class="preset-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h6 class="mb-1">Dyslexia Friendly</h6>
                    <small class="text-muted">OpenDyslexic font, spacing</small>
                </div>
                <div class="preset-card" data-preset="motor">
                    <div class="preset-icon">
                        <i class="fas fa-mouse-pointer"></i>
                    </div>
                    <h6 class="mb-1">Motor Assistance</h6>
                    <small class="text-muted">Large buttons, keyboard nav</small>
                </div>
            </div>

            <!-- Additional Features -->
            <div class="setting-group">
                <h6><i class="fas fa-cog me-2"></i>Additional Features</h6>
                <div class="setting-option">
                    <span class="setting-label">Reduce Animations</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="reduceMotion">
                    </div>
                </div>
                <div class="setting-option">
                    <span class="setting-label">Highlight Focus</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="highlightFocus" checked>
                    </div>
                </div>
                <div class="setting-option">
                    <span class="setting-label">Simplify Layout</span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="simplifyLayout">
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="setting-group">
                <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                <div class="quick-actions">
                    <button class="btn-quick-action" onclick="speakPageTitle()">
                        <i class="fas fa-volume-up mb-2"></i><br>
                        <small>Read Aloud</small>
                    </button>
                    <button class="btn-quick-action" onclick="resetAccessibility()">
                        <i class="fas fa-redo mb-2"></i><br>
                        <small>Reset All</small>
                    </button>
                    <button class="btn-quick-action" onclick="printPage()">
                        <i class="fas fa-print mb-2"></i><br>
                        <small>Print Page</small>
                    </button>
                    <button class="btn-quick-action" data-bs-toggle="modal" data-bs-target="#keyboardShortcutsModal">
                        <i class="fas fa-keyboard mb-2"></i><br>
                        <small>Shortcuts</small>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Keyboard Shortcuts Modal -->
<div class="modal fade" id="keyboardShortcutsModal" tabindex="-1" aria-labelledby="keyboardShortcutsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="keyboardShortcutsModalLabel">Keyboard Shortcuts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="shortcut-item">
                    <kbd>Alt + A</kbd>
                    <span>Open Accessibility Panel</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Alt + 1</kbd>
                    <span>Increase Text Size</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Alt + 2</kbd>
                    <span>Decrease Text Size</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Alt + C</kbd>
                    <span>Toggle Contrast</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Alt + L</kbd>
                    <span>Toggle Language</span>
                </div>
                <div class="shortcut-item">
                    <kbd>Alt + R</kbd>
                    <span>Reset All Settings</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('accessibilityToggle');
    const panel = document.getElementById('accessibilityPanel');
    const closeBtn = document.getElementById('closeAccessibilityPanel');

    // Toggle panel visibility
    toggleBtn.addEventListener('click', function() {
        panel.classList.toggle('show');
    });

    closeBtn.addEventListener('click', function() {
        panel.classList.remove('show');
    });

    // Close panel when clicking outside
    document.addEventListener('click', function(event) {
        if (!panel.contains(event.target) && !toggleBtn.contains(event.target)) {
            panel.classList.remove('show');
        }
    });

    // Language controls
    document.querySelectorAll('.language-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const language = this.dataset.language;

            fetch('/accessibility/quick-tool', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    tool: 'language',
                    language: language
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update active state
                    document.querySelectorAll('.language-btn').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    // Update language text
                    document.getElementById('currentLanguageText').textContent =
                        language === 'tl' ? 'Kasalukuyang wika: Tagalog' : 'Current language: English';

                    // Reload page to apply language changes
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });

    // Font size controls
    document.querySelectorAll('[data-size]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('[data-size]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Remove all font size classes
            document.body.className = document.body.className.replace(/font-size-\w+/g, '');
            // Add selected font size class
            document.body.classList.add('font-size-' + this.dataset.size);

            // Save to localStorage
            localStorage.setItem('accessibility-font-size', this.dataset.size);
        });
    });

    // Contrast controls
    document.querySelectorAll('[data-contrast]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('[data-contrast]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            // Remove all contrast classes
            document.body.className = document.body.className.replace(/contrast-\w+/g, '');
            // Add selected contrast class
            document.body.classList.add('contrast-' + this.dataset.contrast);

            // Save to localStorage
            localStorage.setItem('accessibility-contrast', this.dataset.contrast);
        });
    });

    // Preset cards
    document.querySelectorAll('[data-preset]').forEach(card => {
        card.addEventListener('click', function() {
            const preset = this.dataset.preset;

            // Apply preset settings
            switch(preset) {
                case 'vision':
                    setFontSize('xlarge');
                    setContrast('high');
                    break;
                case 'dyslexia':
                    setFontSize('large');
                    document.body.classList.add('dyslexia-font');
                    break;
                case 'motor':
                    setFontSize('large');
                    document.body.classList.add('motor-friendly');
                    break;
            }
        });
    });

    // Additional settings
    document.getElementById('reduceMotion').addEventListener('change', function() {
        if (this.checked) {
            document.body.classList.add('reduce-motion');
        } else {
            document.body.classList.remove('reduce-motion');
        }
        localStorage.setItem('accessibility-reduce-motion', this.checked);
    });

    document.getElementById('highlightFocus').addEventListener('change', function() {
        if (this.checked) {
            document.body.classList.remove('no-focus-outline');
        } else {
            document.body.classList.add('no-focus-outline');
        }
        localStorage.setItem('accessibility-highlight-focus', this.checked);
    });

    document.getElementById('simplifyLayout').addEventListener('change', function() {
        if (this.checked) {
            document.body.classList.add('simplified-layout');
        } else {
            document.body.classList.remove('simplified-layout');
        }
        localStorage.setItem('accessibility-simplify-layout', this.checked);
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.altKey) {
            switch(e.key) {
                case 'a':
                case 'A':
                    e.preventDefault();
                    panel.classList.toggle('show');
                    break;
                case '1':
                    e.preventDefault();
                    increaseTextSize();
                    break;
                case '2':
                    e.preventDefault();
                    decreaseTextSize();
                    break;
                case 'c':
                case 'C':
                    e.preventDefault();
                    toggleContrast();
                    break;
                case 'l':
                case 'L':
                    e.preventDefault();
                    toggleLanguage();
                    break;
                case 'r':
                case 'R':
                    e.preventDefault();
                    resetAccessibility();
                    break;
            }
        }
    });

    // Load saved settings
    loadSavedSettings();

    // Helper functions
    function setFontSize(size) {
        document.querySelectorAll('[data-size]').forEach(b => b.classList.remove('active'));
        const targetBtn = document.querySelector(`[data-size="${size}"]`);
        if (targetBtn) {
            targetBtn.classList.add('active');
            document.body.className = document.body.className.replace(/font-size-\w+/g, '');
            document.body.classList.add('font-size-' + size);
        }
    }

    function setContrast(contrast) {
        document.querySelectorAll('[data-contrast]').forEach(b => b.classList.remove('active'));
        const targetBtn = document.querySelector(`[data-contrast="${contrast}"]`);
        if (targetBtn) {
            targetBtn.classList.add('active');
            document.body.className = document.body.className.replace(/contrast-\w+/g, '');
            document.body.classList.add('contrast-' + contrast);
        }
    }

    function increaseTextSize() {
        const sizes = ['small', 'medium', 'large', 'xlarge'];
        const current = document.querySelector('[data-size].active')?.dataset.size || 'medium';
        const currentIndex = sizes.indexOf(current);
        if (currentIndex < sizes.length - 1) {
            setFontSize(sizes[currentIndex + 1]);
        }
    }

    function decreaseTextSize() {
        const sizes = ['small', 'medium', 'large', 'xlarge'];
        const current = document.querySelector('[data-size].active')?.dataset.size || 'medium';
        const currentIndex = sizes.indexOf(current);
        if (currentIndex > 0) {
            setFontSize(sizes[currentIndex - 1]);
        }
    }

    function toggleContrast() {
        const contrasts = ['normal', 'high', 'very-high'];
        const current = document.querySelector('[data-contrast].active')?.dataset.contrast || 'normal';
        const currentIndex = contrasts.indexOf(current);
        const nextIndex = (currentIndex + 1) % contrasts.length;
        setContrast(contrasts[nextIndex]);
    }

    function toggleLanguage() {
        const currentLang = document.querySelector('.language-btn.active').dataset.language;
        const newLang = currentLang === 'en' ? 'tl' : 'en';
        document.querySelector(`[data-language="${newLang}"]`).click();
    }

    // Global functions for quick actions
    window.speakPageTitle = function() {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(document.title);
            window.speechSynthesis.speak(utterance);
        } else {
            alert('Text-to-speech is not supported in your browser.');
        }
    };

    window.resetAccessibility = function() {
        setFontSize('medium');
        setContrast('normal');
        document.getElementById('reduceMotion').checked = false;
        document.getElementById('highlightFocus').checked = true;
        document.getElementById('simplifyLayout').checked = false;
        document.body.classList.remove('dyslexia-font', 'motor-friendly', 'reduce-motion', 'simplified-layout', 'no-focus-outline');

        // Clear localStorage
        localStorage.removeItem('accessibility-font-size');
        localStorage.removeItem('accessibility-contrast');
        localStorage.removeItem('accessibility-reduce-motion');
        localStorage.removeItem('accessibility-highlight-focus');
        localStorage.removeItem('accessibility-simplify-layout');
    };

    window.printPage = function() {
        window.print();
    };

    function loadSavedSettings() {
        const fontSize = localStorage.getItem('accessibility-font-size');
        const contrast = localStorage.getItem('accessibility-contrast');
        const reduceMotion = localStorage.getItem('accessibility-reduce-motion') === 'true';
        const highlightFocus = localStorage.getItem('accessibility-highlight-focus') !== 'false';
        const simplifyLayout = localStorage.getItem('accessibility-simplify-layout') === 'true';

        if (fontSize) setFontSize(fontSize);
        if (contrast) setContrast(contrast);
        if (reduceMotion) {
            document.getElementById('reduceMotion').checked = true;
            document.body.classList.add('reduce-motion');
        }
        if (!highlightFocus) {
            document.getElementById('highlightFocus').checked = false;
            document.body.classList.add('no-focus-outline');
        }
        if (simplifyLayout) {
            document.getElementById('simplifyLayout').checked = true;
            document.body.classList.add('simplified-layout');
        }
    }
});
</script>

<style>
/* Language-specific styles */
.language-flag {
    font-size: 1.2em;
    margin-right: 5px;
}

.lang-tl {
    /* Add any Tagalog-specific styles if needed */
}

/* Additional CSS for new features */
.reduce-motion * {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
}

.dyslexia-font {
    font-family: "OpenDyslexic", "Comic Sans MS", cursive, sans-serif !important;
    line-height: 1.6 !important;
    letter-spacing: 1px !important;
}

.motor-friendly .btn,
.motor-friendly .nav-link,
.motor-friendly .form-control {
    min-height: 44px !important;
    min-width: 44px !important;
    padding: 12px 16px !important;
}

.shortcut-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px solid #dee2e6;
}

.shortcut-item:last-child {
    border-bottom: none;
}

kbd {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    padding: 2px 6px;
    font-size: 0.875em;
    font-family: monospace;
}

/* Enhanced panel header styling */
.panel-header {
    background: linear-gradient(135deg, var(--accessibility-primary), var(--accessibility-secondary));
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    position: sticky;
    top: 0;
    z-index: 1;
}

.panel-header h4 {
    font-weight: 600;
}

.panel-header small {
    font-size: 0.875rem;
}

.btn-close-white {
    filter: invert(1) grayscale(100%) brightness(200%);
}
</style>
