import './bootstrap';

// Accessibility functionality
class AccessibilityManager {
    constructor() {
        this.init();
    }

    init() {
        this.setupAccessibilityPanel();
        this.setupPwdSidebar();
        this.loadSavedSettings();
        this.setupKeyboardShortcuts();
        this.setupGoogleTranslateStyling();
    }

    setupAccessibilityPanel() {
        const accessibilityToggle = document.getElementById('accessibilityToggle');
        const accessibilityPanel = document.getElementById('accessibilityPanel');
        const closeAccessibilityPanel = document.getElementById('closeAccessibilityPanel');

        if (accessibilityToggle && accessibilityPanel) {
            accessibilityToggle.addEventListener('click', () => {
                accessibilityPanel.classList.add('show');
            });

            if (closeAccessibilityPanel) {
                closeAccessibilityPanel.addEventListener('click', () => {
                    accessibilityPanel.classList.remove('show');
                });
            }

            // Close panel when clicking outside
            document.addEventListener('click', (event) => {
                if (accessibilityPanel.classList.contains('show') &&
                    !accessibilityPanel.contains(event.target) &&
                    !accessibilityToggle.contains(event.target)) {
                    accessibilityPanel.classList.remove('show');
                }
            });
        }

        // Text size settings
        const textSizeButtons = document.querySelectorAll('[data-size]');
        textSizeButtons.forEach(button => {
            button.addEventListener('click', () => {
                textSizeButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                this.applyTextSize(button.getAttribute('data-size'));
            });
        });

        // Contrast settings
        const contrastButtons = document.querySelectorAll('[data-contrast]');
        contrastButtons.forEach(button => {
            button.addEventListener('click', () => {
                contrastButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                this.applyContrast(button.getAttribute('data-contrast'));
            });
        });

        // Preset cards
        const presetCards = document.querySelectorAll('.preset-card');
        presetCards.forEach(card => {
            card.addEventListener('click', () => {
                this.applyPreset(card.getAttribute('data-preset'));
            });
        });

        // Additional features
        this.setupAdditionalFeatures();
    }

    setupAdditionalFeatures() {
        // Reduce motion
        const reduceMotion = document.getElementById('reduceMotion');
        if (reduceMotion) {
            reduceMotion.addEventListener('change', () => {
                if (reduceMotion.checked) {
                    document.body.style.animation = 'none';
                    document.body.style.transition = 'none';
                } else {
                    document.body.style.animation = '';
                    document.body.style.transition = '';
                }
                localStorage.setItem('reduceMotion', reduceMotion.checked);
            });
        }

        // Highlight focus
        const highlightFocus = document.getElementById('highlightFocus');
        if (highlightFocus) {
            highlightFocus.addEventListener('change', () => {
                if (!highlightFocus.checked) {
                    document.body.classList.add('no-focus-outline');
                } else {
                    document.body.classList.remove('no-focus-outline');
                }
                localStorage.setItem('highlightFocus', highlightFocus.checked);
            });
        }

        // Simplify layout
        const simplifyLayout = document.getElementById('simplifyLayout');
        if (simplifyLayout) {
            simplifyLayout.addEventListener('change', () => {
                if (simplifyLayout.checked) {
                    document.body.classList.add('simplified-layout');
                    document.querySelectorAll('.card, .navbar, .btn').forEach(el => {
                        el.style.boxShadow = 'none';
                        el.style.borderRadius = '0';
                    });
                } else {
                    document.body.classList.remove('simplified-layout');
                    document.querySelectorAll('.card, .navbar, .btn').forEach(el => {
                        el.style.boxShadow = '';
                        el.style.borderRadius = '';
                    });
                }
                localStorage.setItem('simplifyLayout', simplifyLayout.checked);
            });
        }
    }

    setupPwdSidebar() {
        const pwdSidebarToggle = document.getElementById('pwdSidebarToggle');
        const pwdSidebar = document.querySelector('.pwd-sidebar');
        const mainContent = document.querySelector('.main-content');

        if (pwdSidebarToggle && pwdSidebar && mainContent) {
            pwdSidebarToggle.addEventListener('click', () => {
                pwdSidebar.classList.toggle('hidden');
                mainContent.classList.toggle('full-width');

                // Update icon
                const icon = pwdSidebarToggle.querySelector('i');
                if (pwdSidebar.classList.contains('hidden')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-chevron-right');
                } else {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-bars');
                }
            });
        }
    }

    setupKeyboardShortcuts() {
        document.addEventListener('keydown', (e) => {
            if (e.altKey) {
                const pwdSidebarToggle = document.getElementById('pwdSidebarToggle');
                const accessibilityPanel = document.getElementById('accessibilityPanel');

                switch(e.key) {
                    case 'a':
                        e.preventDefault();
                        if (accessibilityPanel) {
                            accessibilityPanel.classList.toggle('show');
                        }
                        break;
                    case 'r':
                        e.preventDefault();
                        this.resetAccessibility();
                        break;
                    case 's':
                        e.preventDefault();
                        document.querySelector('.skip-link')?.focus();
                        break;
                    case 'p':
                        e.preventDefault();
                        if (pwdSidebarToggle) {
                            pwdSidebarToggle.click();
                        }
                        break;
                }
            }
        });
    }

    setupGoogleTranslateStyling() {
        // Handle Google Translate styling
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    const googte = document.querySelector('.goog-te-gadget');
                    if (googte) {
                        const span = googte.querySelector('span');
                        if (span) span.style.display = 'none';

                        const select = googte.querySelector('select');
                        if (select) {
                            select.className = 'form-select form-select-sm';
                            select.style.width = '100%';
                        }
                    }
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

    applyTextSize(size) {
        document.body.classList.remove('font-size-small', 'font-size-medium', 'font-size-large', 'font-size-xlarge');

        switch(size) {
            case 'small':
                document.body.classList.add('font-size-small');
                break;
            case 'medium':
                document.body.classList.add('font-size-medium');
                break;
            case 'large':
                document.body.classList.add('font-size-large');
                break;
            case 'xlarge':
                document.body.classList.add('font-size-xlarge');
                break;
        }

        localStorage.setItem('textSize', size);
        this.announceToScreenReader(`Text size set to ${size}`);
    }

    applyContrast(contrast) {
        document.body.classList.remove('contrast-high', 'contrast-very-high', 'high-contrast');

        switch(contrast) {
            case 'high':
                document.body.classList.add('contrast-high');
                break;
            case 'very-high':
                document.body.classList.add('contrast-very-high', 'high-contrast');
                break;
        }

        localStorage.setItem('contrast', contrast);
        this.announceToScreenReader(`Contrast set to ${contrast}`);
    }

    applyPreset(preset) {
        this.resetAccessibility();

        switch(preset) {
            case 'vision':
                this.applyTextSize('xlarge');
                this.applyContrast('very-high');
                this.announceToScreenReader('Low vision preset applied');
                break;
            case 'dyslexia':
                this.applyTextSize('large');
                document.body.style.fontFamily = 'OpenDyslexic, Arial, sans-serif';
                document.body.style.lineHeight = '1.8';
                this.announceToScreenReader('Dyslexia friendly preset applied');
                break;
            case 'motor':
                this.applyTextSize('large');
                const buttons = document.querySelectorAll('button, a, input, select');
                buttons.forEach(btn => {
                    btn.style.minHeight = '44px';
                    btn.style.minWidth = '44px';
                });
                this.announceToScreenReader('Motor assistance preset applied');
                break;
        }
    }

    resetAccessibility() {
        document.body.classList.remove(
            'font-size-small', 'font-size-medium', 'font-size-large', 'font-size-xlarge',
            'contrast-high', 'contrast-very-high', 'high-contrast'
        );
        document.body.style.fontFamily = '';
        document.body.style.lineHeight = '';

        // Reset interactive elements
        const buttons = document.querySelectorAll('button, a, input, select');
        buttons.forEach(btn => {
            btn.style.minHeight = '';
            btn.style.minWidth = '';
        });

        // Reset switches
        const reduceMotion = document.getElementById('reduceMotion');
        const highlightFocus = document.getElementById('highlightFocus');
        const simplifyLayout = document.getElementById('simplifyLayout');

        if (reduceMotion) reduceMotion.checked = false;
        if (highlightFocus) highlightFocus.checked = true;
        if (simplifyLayout) simplifyLayout.checked = false;

        // Reset button active states
        document.querySelectorAll('.btn-setting').forEach(btn => btn.classList.remove('active'));
        const mediumSizeBtn = document.querySelector('[data-size="medium"]');
        const normalContrastBtn = document.querySelector('[data-contrast="normal"]');

        if (mediumSizeBtn) mediumSizeBtn.classList.add('active');
        if (normalContrastBtn) normalContrastBtn.classList.add('active');

        // Clear localStorage
        ['textSize', 'contrast', 'reduceMotion', 'highlightFocus', 'simplifyLayout'].forEach(key => {
            localStorage.removeItem(key);
        });

        // Reset animations and transitions
        document.body.style.animation = '';
        document.body.style.transition = '';
        document.body.classList.remove('no-focus-outline', 'simplified-layout');

        this.announceToScreenReader('All accessibility settings reset');
    }

    loadSavedSettings() {
        const savedTextSize = localStorage.getItem('textSize');
        const savedContrast = localStorage.getItem('contrast');

        if (savedTextSize) {
            const button = document.querySelector(`[data-size="${savedTextSize}"]`);
            if (button) {
                document.querySelectorAll('[data-size]').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                this.applyTextSize(savedTextSize);
            }
        }

        if (savedContrast) {
            const button = document.querySelector(`[data-contrast="${savedContrast}"]`);
            if (button) {
                document.querySelectorAll('[data-contrast]').forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                this.applyContrast(savedContrast);
            }
        }

        this.loadAdditionalSettings();
    }

    loadAdditionalSettings() {
        const reduceMotion = localStorage.getItem('reduceMotion') === 'true';
        const highlightFocus = localStorage.getItem('highlightFocus') !== 'false';
        const simplifyLayout = localStorage.getItem('simplifyLayout') === 'true';

        const reduceMotionEl = document.getElementById('reduceMotion');
        const highlightFocusEl = document.getElementById('highlightFocus');
        const simplifyLayoutEl = document.getElementById('simplifyLayout');

        if (reduceMotionEl) reduceMotionEl.checked = reduceMotion;
        if (highlightFocusEl) highlightFocusEl.checked = highlightFocus;
        if (simplifyLayoutEl) simplifyLayoutEl.checked = simplifyLayout;

        // Apply settings
        if (reduceMotion) {
            document.body.style.animation = 'none';
            document.body.style.transition = 'none';
        }
        if (!highlightFocus) {
            document.body.classList.add('no-focus-outline');
        }
        if (simplifyLayout) {
            document.body.classList.add('simplified-layout');
            document.querySelectorAll('.card, .navbar, .btn').forEach(el => {
                el.style.boxShadow = 'none';
                el.style.borderRadius = '0';
            });
        }
    }

    announceToScreenReader(message) {
        const announcer = document.getElementById('sr-announcer');
        if (announcer) {
            announcer.textContent = message;
            setTimeout(() => {
                announcer.textContent = '';
            }, 1000);
        }
    }
}

// Utility functions
function speakPageTitle() {
    if ('speechSynthesis' in window) {
        const pageTitle = document.title;
        const utterance = new SpeechSynthesisUtterance(pageTitle);
        window.speechSynthesis.speak(utterance);
        announceToScreenReader('Reading page title aloud');
    } else {
        announceToScreenReader('Text-to-speech is not supported in your browser');
    }
}

function printPage() {
    window.print();
}

function announceToScreenReader(message) {
    const announcer = document.getElementById('sr-announcer');
    if (announcer) {
        announcer.textContent = message;
        setTimeout(() => {
            announcer.textContent = '';
        }, 1000);
    }
}

// Sidebar functionality
function toggleSidebar() {
    const sidebar = document.querySelector('.pwd-sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    const body = document.body;

    sidebar.classList.toggle('mobile-open');
    if (overlay) {
        overlay.classList.toggle('mobile-open');
    }
    body.classList.toggle('sidebar-open');
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Accessibility Manager
    new AccessibilityManager();

    // Existing sidebar functionality
    const navLinks = document.querySelectorAll('.pwd-sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768) {
                toggleSidebar();
            }
        });
    });

    // Close sidebar with escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const sidebar = document.querySelector('.pwd-sidebar');
            if (sidebar && sidebar.classList.contains('mobile-open')) {
                toggleSidebar();
            }
        }
    });

    // Close sidebar when clicking overlay
    const overlay = document.querySelector('.sidebar-overlay');
    if (overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }

    // Mobile menu button event
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', toggleSidebar);
    }

    // Skip link functionality
    const skipLink = document.querySelector('.skip-link');
    if (skipLink) {
        skipLink.addEventListener('focus', function() {
            this.style.left = '6px';
            this.style.top = '6px';
        });
        skipLink.addEventListener('blur', function() {
            this.style.left = '-9999px';
        });
    }

    // Auto-dismiss success alerts after 8 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert-success').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 8000);

    // Screen reader announcement for new opportunities
    const jobPostingsCount = document.querySelector('[aria-labelledby="jobs-section-heading"]') ?
        document.querySelector('[aria-labelledby="jobs-section-heading"]').querySelectorAll('.list-group-item').length : 0;
    const trainingsCount = document.querySelector('[aria-labelledby="trainings-section-heading"]') ?
        document.querySelector('[aria-labelledby="trainings-section-heading"]').querySelectorAll('.list-group-item').length : 0;

    if (jobPostingsCount > 0 || trainingsCount > 0) {
        setTimeout(() => {
            const message = "New job and training opportunities are available. Check the Available Opportunities section.";
            const liveRegion = document.createElement('div');
            liveRegion.setAttribute('aria-live', 'polite');
            liveRegion.setAttribute('aria-atomic', 'true');
            liveRegion.className = 'sr-only';
            liveRegion.textContent = message;
            document.body.appendChild(liveRegion);

            setTimeout(() => {
                document.body.removeChild(liveRegion);
            }, 3000);
        }, 1000);
    }
});

// Make functions globally available for onclick attributes
window.speakPageTitle = speakPageTitle;
window.printPage = printPage;
window.resetAccessibility = function() {
    const manager = new AccessibilityManager();
    manager.resetAccessibility();
};
window.toggleSidebar = toggleSidebar;

// Google Translate
function googleTranslateElementInit() {
    if (typeof google !== 'undefined' && google.translate) {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,tl',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'google_translate_element');
    }
}

// Keyboard shortcuts (complementary to AccessibilityManager)
document.addEventListener('keydown', function(e) {
    if (e.altKey) {
        const accessibilityToggle = document.getElementById('accessibilityToggle');
        const pwdSidebar = document.querySelector('.pwd-sidebar');

        switch(e.key) {
            case 'a':
                e.preventDefault();
                if (accessibilityToggle) {
                    accessibilityToggle.click();
                }
                break;
            case 'r':
                e.preventDefault();
                if (window.resetAccessibility) {
                    window.resetAccessibility();
                }
                break;
            case 's':
                e.preventDefault();
                document.querySelector('.skip-link')?.focus();
                break;
            case 'p':
                e.preventDefault();
                if (pwdSidebar) {
                    toggleSidebar();
                }
                break;
        }
    }
});
