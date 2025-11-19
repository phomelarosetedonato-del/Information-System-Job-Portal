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
                $decodedCookie = json_decode($cookieValue, true);
                if (!$decodedCookie) {
                    $decodedCookie = json_decode(base64_decode($cookieValue), true);
                }
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

    <button class="accessibility-toggle" id="accessibilityToggle" aria-label="{{ $currentLanguage === 'tl' ? 'Mga Opsyon sa Accessibility' : 'Accessibility Options' }}">
        <i class="fas fa-universal-access"></i>
    </button>

    <div class="accessibility-panel" id="accessibilityPanel">
        <div class="panel-header">
            <div>
                <h4 class="mb-0"><i class="fas fa-universal-access me-2"></i><span data-translate="accessibility">{{ $currentLanguage === 'tl' ? 'Accessibility' : 'Accessibility' }}</span></h4>
                <small class="opacity-75"><span data-translate="customize_experience">{{ $currentLanguage === 'tl' ? 'Ipasadya ang iyong karanasan sa pagtingin' : 'Customize your viewing experience' }}</span></small>
            </div>
            <button type="button" class="btn-close btn-close-white" id="closeAccessibilityPanel" aria-label="{{ $currentLanguage === 'tl' ? 'Isara ang panel ng accessibility' : 'Close accessibility panel' }}"></button>
        </div>

        <div class="panel-body">
            <!-- Text Size Settings -->
            <div class="setting-group">
                <h6><i class="fas fa-text-height me-2"></i><span data-translate="text_size">{{ $currentLanguage === 'tl' ? 'Laki ng Teksto' : 'Text Size' }}</span></h6>
                <div class="btn-group-setting">
                    <button class="btn-setting" data-size="small"><span data-translate="small">{{ $currentLanguage === 'tl' ? 'Maliit' : 'Small' }}</span></button>
                    <button class="btn-setting active" data-size="medium"><span data-translate="medium">{{ $currentLanguage === 'tl' ? 'Katamtaman' : 'Medium' }}</span></button>
                    <button class="btn-setting" data-size="large"><span data-translate="large">{{ $currentLanguage === 'tl' ? 'Malaki' : 'Large' }}</span></button>
                    <button class="btn-setting" data-size="xlarge"><span data-translate="xlarge">{{ $currentLanguage === 'tl' ? 'Napakalaki' : 'X-Large' }}</span></button>
                </div>
            </div>

            <!-- Contrast Settings -->
            <div class="setting-group">
                <h6><i class="fas fa-adjust me-2"></i><span data-translate="contrast">{{ $currentLanguage === 'tl' ? 'Kulay at Kontrast' : 'Color & Contrast' }}</span></h6>
                <div class="btn-group-setting">
                    <button class="btn-setting active" data-contrast="normal"><span data-translate="normal">{{ $currentLanguage === 'tl' ? 'Normal' : 'Normal' }}</span></button>
                    <button class="btn-setting" data-contrast="high"><span data-translate="high">{{ $currentLanguage === 'tl' ? 'Mataas' : 'High' }}</span></button>
                    <button class="btn-setting" data-contrast="very-high"><span data-translate="very_high">{{ $currentLanguage === 'tl' ? 'Napakataas' : 'Very High' }}</span></button>
                </div>
            </div>

            <!-- Quick Presets -->
            <div class="setting-group">
                <h6><i class="fas fa-magic me-2"></i><span data-translate="quick_presets">{{ $currentLanguage === 'tl' ? 'Mabilisang Preset' : 'Quick Presets' }}</span></h6>
                <div class="preset-card" data-preset="vision">
                    <div class="preset-icon">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h6 class="mb-1"><span data-translate="low_vision">{{ $currentLanguage === 'tl' ? 'Mahinang Paningin' : 'Low Vision' }}</span></h6>
                    <small class="text-muted"><span data-translate="low_vision_desc">{{ $currentLanguage === 'tl' ? 'Mas malaking teksto, mataas na kontrast' : 'Larger text, high contrast' }}</span></small>
                </div>
                <div class="preset-card" data-preset="dyslexia">
                    <div class="preset-icon">
                        <i class="fas fa-book"></i>
                    </div>
                    <h6 class="mb-1"><span data-translate="dyslexia">{{ $currentLanguage === 'tl' ? 'Angkop para sa Dyslexia' : 'Dyslexia Friendly' }}</span></h6>
                    <small class="text-muted"><span data-translate="dyslexia_desc">{{ $currentLanguage === 'tl' ? 'OpenDyslexic na font, spacing' : 'OpenDyslexic font, spacing' }}</span></small>
                </div>
                <div class="preset-card" data-preset="motor">
                    <div class="preset-icon">
                        <i class="fas fa-mouse-pointer"></i>
                    </div>
                    <h6 class="mb-1"><span data-translate="motor">{{ $currentLanguage === 'tl' ? 'Tulong sa Motor' : 'Motor Assistance' }}</span></h6>
                    <small class="text-muted"><span data-translate="motor_desc">{{ $currentLanguage === 'tl' ? 'Malalaking butones, keyboard navigation' : 'Large buttons, keyboard nav' }}</span></small>
                </div>
            </div>

            <!-- Additional Features -->
            <div class="setting-group">
                <h6><i class="fas fa-cog me-2"></i><span data-translate="additional">{{ $currentLanguage === 'tl' ? 'Karagdagang Mga Tampok' : 'Additional Features' }}</span></h6>
                <div class="setting-option">
                    <span class="setting-label"><span data-translate="reduce_animations">{{ $currentLanguage === 'tl' ? 'Bawasan ang mga Animasyon' : 'Reduce Animations' }}</span></span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="reduceMotion">
                    </div>
                </div>
                <div class="setting-option">
                    <span class="setting-label"><span data-translate="highlight_focus">{{ $currentLanguage === 'tl' ? 'I-highlight ang Focus' : 'Highlight Focus' }}</span></span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="highlightFocus" checked>
                    </div>
                </div>
                <div class="setting-option">
                    <span class="setting-label"><span data-translate="simplify_layout">{{ $currentLanguage === 'tl' ? 'Pasimplehin ang Layout' : 'Simplify Layout' }}</span></span>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="simplifyLayout">
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="setting-group">
                <h6><i class="fas fa-bolt me-2"></i><span data-translate="quick_actions">{{ $currentLanguage === 'tl' ? 'Mabilisang Mga Aksyon' : 'Quick Actions' }}</span></h6>
                <div class="quick-actions">
                    <button class="btn-quick-action" onclick="speakPageTitle()" aria-label="{{ $currentLanguage === 'tl' ? 'Basahin nang malakas' : 'Read aloud' }}">
                        <i class="fas fa-volume-up mb-2"></i><br>
                        <small><span data-translate="read_aloud">{{ $currentLanguage === 'tl' ? 'Basahin' : 'Read Aloud' }}</span></small>
                    </button>
                    <button class="btn-quick-action" onclick="resetAccessibility()" aria-label="{{ $currentLanguage === 'tl' ? 'I-reset ang lahat' : 'Reset all' }}">
                        <i class="fas fa-redo mb-2"></i><br>
                        <small><span data-translate="reset_all">{{ $currentLanguage === 'tl' ? 'I-reset' : 'Reset All' }}</span></small>
                    </button>
                    <button class="btn-quick-action" onclick="printPage()" aria-label="{{ $currentLanguage === 'tl' ? 'I-print ang pahina' : 'Print page' }}">
                        <i class="fas fa-print mb-2"></i><br>
                        <small><span data-translate="print">{{ $currentLanguage === 'tl' ? 'I-print' : 'Print Page' }}</span></small>
                    </button>
                    <button class="btn-quick-action" data-bs-toggle="modal" data-bs-target="#keyboardShortcutsModal" aria-label="{{ $currentLanguage === 'tl' ? 'Mga shortcut' : 'Shortcuts' }}">
                        <i class="fas fa-keyboard mb-2"></i><br>
                        <small><span data-translate="shortcuts">{{ $currentLanguage === 'tl' ? 'Shortcuts' : 'Shortcuts' }}</span></small>
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

    // Font size controls
                let translatedCount = 0;

                // If switching back to English, restore original text
                if (targetLang === 'en') {
                    elementsToTranslate.forEach(el => {
                        if (el.dataset.originalText) {
                            el.textContent = el.dataset.originalText;
                            translatedCount++;
                        }
                    });
                } else {
                    // Translate to Tagalog
                    Object.keys(translationMap).forEach(key => {
                        const translated = data.translations[key];
                        if (translated) {
                            translationMap[key].forEach(el => {
                                el.textContent = translated;
                                translatedCount++;
                            });
                        } else {
                            console.warn('⚠️ No translation found for:', key);
                        }
                    });
                }

                console.log('✅ Successfully translated', translatedCount, 'elements');

                if (translatedCount > 0) {
                    showNotification(
                        targetLang === 'tl'
                            ? 'Matagumpay na isinalin sa Tagalog!'
                            : 'Successfully translated to English!',
                        'success'
                    );
                }
            } else {
                console.error('❌ Translation failed:', data);
                showNotification('Translation failed. Please try again.', 'error');
            }
        } catch (error) {
            console.error('❌ Translation error:', error);
            showNotification('Network error during translation.', 'error');
        }
    }

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

            showNotification('Text size updated to ' + this.dataset.size);
        });
    });

    // Contrast controls with improved visibility
    document.querySelectorAll('[data-contrast]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('[data-contrast]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const contrastLevel = this.dataset.contrast;

            // Remove all contrast classes
            document.body.classList.remove('contrast-normal', 'contrast-high', 'contrast-very-high');

            // Add selected contrast class
            if (contrastLevel !== 'normal') {
                document.body.classList.add('contrast-' + contrastLevel);
            }

            // Apply contrast to the accessibility panel itself
            const panel = document.getElementById('accessibilityPanel');
            panel.classList.remove('contrast-normal', 'contrast-high', 'contrast-very-high');
            if (contrastLevel !== 'normal') {
                panel.classList.add('contrast-' + contrastLevel);
            }

            // Save to localStorage
            localStorage.setItem('accessibility-contrast', contrastLevel);

            // Show notification
            let message = '';
            switch(contrastLevel) {
                case 'high':
                    message = 'High contrast mode activated. Enhanced visibility for better readability.';
                    break;
                case 'very-high':
                    message = 'Very high contrast mode activated. Maximum visibility with yellow on black.';
                    break;
                default:
                    message = 'Normal contrast restored.';
            }
            showNotification(message);

            // Send to server
            fetch('/accessibility/quick-tool', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    tool: 'contrast',
                    action: contrastLevel
                })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Contrast updated:', data);
            })
            .catch(error => console.error('Error:', error));
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

    // Helper function to show notifications
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 80px;
            right: 20px;
            background: ${type === 'success' ? 'linear-gradient(135deg, #10b981, #059669)' : '#333'};
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            z-index: 10000;
            animation: slideInRight 0.3s ease;
            max-width: 300px;
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Global functions for quick actions
    window.speakPageTitle = function() {
        if ('speechSynthesis' in window) {
            const currentLang = localStorage.getItem('accessibility-language') || 'en';
            const utterance = new SpeechSynthesisUtterance(document.title);
            utterance.lang = currentLang === 'tl' ? 'tl-PH' : 'en-US';
            window.speechSynthesis.speak(utterance);
            showNotification('Reading page title aloud...', 'info');
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
        if (contrast) {
            setContrast(contrast);
            // Apply contrast immediately on page load
            if (contrast !== 'normal') {
                document.body.classList.add('contrast-' + contrast);
                const panel = document.getElementById('accessibilityPanel');
                if (panel) {
                    panel.classList.add('contrast-' + contrast);
                }
            }
        }
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

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>

<style>
/* ========================================
   ACCESSIBILITY WIDGET CORE STYLES
   ======================================== */

.accessibility-widget {
    position: fixed !important;
    right: 20px !important;
    bottom: 20px !important;
    z-index: 10000 !important;
}

.accessibility-toggle {
    width: 60px !important;
    height: 60px !important;
    border-radius: 50% !important;
    background: linear-gradient(135deg, #2E8B57 0%, #1A5D34 100%) !important;
    color: white !important;
    border: none !important;
    box-shadow: 0 4px 20px rgba(46, 139, 87, 0.4) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
    z-index: 10001 !important;
    animation: pulse-green 2s infinite;
}

@keyframes pulse-green {
    0%, 100% {
        box-shadow: 0 4px 20px rgba(46, 139, 87, 0.4);
    }
    50% {
        box-shadow: 0 4px 30px rgba(46, 139, 87, 0.7);
    }
}

.accessibility-toggle:hover {
    transform: scale(1.1) !important;
    background: linear-gradient(135deg, #1A5D34 0%, #2E8B57 100%) !important;
    box-shadow: 0 6px 25px rgba(46, 139, 87, 0.6) !important;
}

.accessibility-toggle i {
    font-size: 1.8rem !important;
}

.accessibility-panel {
    position: fixed !important;
    right: 20px !important;
    bottom: 95px !important;
    width: 380px !important;
    max-height: 80vh !important;
    background: white !important;
    border-radius: 16px !important;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2) !important;
    border: 2px solid #2E8B57 !important;
    z-index: 10002 !important;
    display: none !important;
    overflow: hidden !important;
}

.accessibility-panel.show {
    display: block !important;
    animation: slideUpPanel 0.3s ease;
}

@keyframes slideUpPanel {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.panel-body {
    padding: 20px !important;
    max-height: calc(80vh - 80px) !important;
    overflow-y: auto !important;
}

.setting-group {
    margin-bottom: 24px !important;
    padding-bottom: 20px !important;
    border-bottom: 1px solid #e9ecef !important;
}

.setting-group:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
}

.setting-group h6 {
    color: #2E8B57 !important;
    font-weight: 600 !important;
    margin-bottom: 12px !important;
    font-size: 0.95rem !important;
}

.btn-group-setting {
    display: flex !important;
    gap: 8px !important;
    flex-wrap: wrap !important;
}

.btn-setting {
    flex: 1 !important;
    min-width: 80px !important;
    padding: 10px 12px !important;
    border: 2px solid #e9ecef !important;
    border-radius: 8px !important;
    background: white !important;
    color: #495057 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
}

.btn-setting:hover {
    border-color: #2E8B57 !important;
    background: #f0f8f5 !important;
    transform: translateY(-2px) !important;
}

.btn-setting.active {
    background: #2E8B57 !important;
    border-color: #2E8B57 !important;
    color: white !important;
    font-weight: 600 !important;
}

.preset-card {
    padding: 12px !important;
    border: 2px solid #e9ecef !important;
    border-radius: 8px !important;
    background: white !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    margin-bottom: 8px !important;
}

.preset-card:hover {
    border-color: #2E8B57 !important;
    background: #f0f8f5 !important;
    transform: translateX(4px) !important;
}

.preset-card h6 {
    color: #2E8B57 !important;
    font-size: 0.9rem !important;
    margin-bottom: 4px !important;
}

.preset-card small {
    color: #6c757d !important;
    font-size: 0.8rem !important;
}

.form-check-input {
    width: 1.2em !important;
    height: 1.2em !important;
    cursor: pointer !important;
}

.form-check-label {
    cursor: pointer !important;
    user-select: none !important;
}

.btn-quick-action {
    width: 100% !important;
    padding: 10px !important;
    margin-bottom: 8px !important;
    border: 2px solid #e9ecef !important;
    border-radius: 8px !important;
    background: white !important;
    color: #495057 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
    gap: 8px !important;
}

.btn-quick-action:hover {
    border-color: #2E8B57 !important;
    background: #f0f8f5 !important;
}

/* Language-specific styles */
.language-flag {
    font-size: 1.2em;
    margin-right: 5px;
}

.lang-tl {
    /* Add any Tagalog-specific styles if needed */
}

/* Responsive styles */
@media (max-width: 768px) {
    .accessibility-panel {
        right: 10px !important;
        bottom: 85px !important;
        width: calc(100vw - 20px) !important;
        max-width: 380px !important;
    }

    .accessibility-toggle {
        width: 50px !important;
        height: 50px !important;
        right: 10px !important;
        bottom: 10px !important;
    }

    .accessibility-toggle i {
        font-size: 1.5rem !important;
    }
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
    background: linear-gradient(135deg, #10b981, #059669);
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

/* High Contrast Mode Improvements */
.contrast-high {
    background-color: #ffffff !important;
    color: #000000 !important;
}

.contrast-high * {
    color: #000000 !important;
}

.contrast-high .accessibility-toggle {
    background: #ffffff !important;
    color: #000000 !important;
    border: 3px solid #000000 !important;
}

.contrast-high .accessibility-panel {
    background: #ffffff !important;
    color: #000000 !important;
    border: 3px solid #000000 !important;
}

.contrast-high .panel-header {
    background: #000000 !important;
    color: #ffffff !important;
}

.contrast-high .panel-header * {
    color: #ffffff !important;
}

.contrast-high .btn-setting,
.contrast-high .preset-card,
.contrast-high .btn-quick-action {
    background: #ffffff !important;
    color: #000000 !important;
    border: 2px solid #000000 !important;
}

.contrast-high .btn-setting:hover,
.contrast-high .preset-card:hover,
.contrast-high .btn-quick-action:hover {
    background: #000000 !important;
    color: #ffffff !important;
}

.contrast-high .btn-setting:hover *,
.contrast-high .preset-card:hover *,
.contrast-high .btn-quick-action:hover * {
    color: #ffffff !important;
}

.contrast-high .btn-setting.active {
    background: #000000 !important;
    color: #ffffff !important;
    border: 3px solid #000000 !important;
}

.contrast-high .btn-setting.active * {
    color: #ffffff !important;
}

.contrast-high .form-check-input {
    border: 2px solid #000000 !important;
    background-color: #ffffff !important;
}

.contrast-high .form-check-input:checked {
    background-color: #000000 !important;
    border-color: #000000 !important;
}

.contrast-high .card,
.contrast-high .btn:not(.btn-setting):not(.btn-quick-action),
.contrast-high .form-control,
.contrast-high .nav-link,
.contrast-high input,
.contrast-high textarea,
.contrast-high select {
    background: #ffffff !important;
    color: #000000 !important;
    border-color: #000000 !important;
}

.contrast-high a {
    color: #000000 !important;
    text-decoration: underline !important;
}

.contrast-high .modal-content,
.contrast-high .modal-header,
.contrast-high .modal-body {
    background: #ffffff !important;
    color: #000000 !important;
    border-color: #000000 !important;
}

.contrast-high .dropdown-menu,
.contrast-high .dropdown-item {
    background: #ffffff !important;
    color: #000000 !important;
}

.contrast-high .dropdown-item:hover {
    background: #000000 !important;
    color: #ffffff !important;
}

.contrast-high table,
.contrast-high th,
.contrast-high td {
    background: #ffffff !important;
    color: #000000 !important;
    border-color: #000000 !important;
}

.contrast-high .badge {
    background: #000000 !important;
    color: #ffffff !important;
}

/* Very High Contrast Mode - Maximum Visibility */
.contrast-very-high {
    background-color: #000000 !important;
    color: #ffff00 !important;
}

.contrast-very-high * {
    color: #ffff00 !important;
}

.contrast-very-high .accessibility-toggle {
    background: #000000 !important;
    color: #ffff00 !important;
    border: 4px solid #ffff00 !important;
}

.contrast-very-high .accessibility-panel {
    background: #000000 !important;
    color: #ffff00 !important;
    border: 4px solid #ffff00 !important;
}

.contrast-very-high .panel-header {
    background: #000000 !important;
    color: #ffff00 !important;
    border-bottom: 4px solid #ffff00 !important;
}

.contrast-very-high .btn-setting,
.contrast-very-high .preset-card,
.contrast-very-high .btn-quick-action {
    background: #000000 !important;
    color: #ffff00 !important;
    border: 3px solid #ffff00 !important;
}

.contrast-very-high .btn-setting:hover,
.contrast-very-high .preset-card:hover,
.contrast-very-high .btn-quick-action:hover {
    background: #ffff00 !important;
    color: #000000 !important;
}

.contrast-very-high .btn-setting.active {
    background: #ffff00 !important;
    color: #000000 !important;
    border: 4px solid #ffff00 !important;
    font-weight: bold !important;
}

.contrast-very-high .form-check-input {
    border: 3px solid #ffff00 !important;
    background-color: #000000 !important;
}

.contrast-very-high .form-check-input:checked {
    background-color: #ffff00 !important;
    border-color: #ffff00 !important;
}

.contrast-very-high .form-check-input:checked::before {
    background-color: #000000 !important;
}

.contrast-very-high .setting-group h6,
.contrast-very-high .setting-label,
.contrast-very-high .text-muted,
.contrast-very-high small,
.contrast-very-high span,
.contrast-very-high p,
.contrast-very-high h1,
.contrast-very-high h2,
.contrast-very-high h3,
.contrast-very-high h4,
.contrast-very-high h5,
.contrast-very-high h6 {
    color: #ffff00 !important;
}

.contrast-very-high .card,
.contrast-very-high .btn,
.contrast-very-high .form-control,
.contrast-very-high .nav-link,
.contrast-very-high input,
.contrast-very-high textarea,
.contrast-very-high select {
    background: #000000 !important;
    color: #ffff00 !important;
    border-color: #ffff00 !important;
}

.contrast-very-high a {
    color: #ffff00 !important;
    text-decoration: underline !important;
}

.contrast-very-high .btn-close-white {
    filter: brightness(0) saturate(100%) invert(87%) sepia(97%) saturate(2444%) hue-rotate(359deg) brightness(107%) contrast(106%) !important;
}

.contrast-very-high .modal-content,
.contrast-very-high .modal-header,
.contrast-very-high .modal-body {
    background: #000000 !important;
    color: #ffff00 !important;
    border-color: #ffff00 !important;
}

.contrast-very-high .dropdown-menu,
.contrast-very-high .dropdown-item {
    background: #000000 !important;
    color: #ffff00 !important;
}

.contrast-very-high .dropdown-item:hover {
    background: #ffff00 !important;
    color: #000000 !important;
}

.contrast-very-high table,
.contrast-very-high th,
.contrast-very-high td {
    background: #000000 !important;
    color: #ffff00 !important;
    border-color: #ffff00 !important;
}

.contrast-very-high .badge {
    background: #ffff00 !important;
    color: #000000 !important;
}

.contrast-very-high i,
.contrast-very-high .fas,
.contrast-very-high .far,
.contrast-very-high .fab {
    color: #ffff00 !important;
}
</style>
