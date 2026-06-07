#!/usr/bin/env bash
# Accessibility Widget - Quick Verification Script
# Use this to verify the accessibility toggle is working correctly

echo "================================================"
echo "ACCESSIBILITY WIDGET VERIFICATION"
echo "================================================"
echo ""

# Test 1: Check if files exist
echo "✓ Step 1: Checking files exist..."
if [ -f "resources/views/partials/accessibility-widget.blade.php" ]; then
    echo "  ✓ Partial widget found"
else
    echo "  ✗ Partial widget NOT found"
fi

if [ -f "resources/views/layouts/admin.blade.php" ]; then
    echo "  ✓ Layout file found"
else
    echo "  ✗ Layout file NOT found"
fi

echo ""
echo "✓ Step 2: Quick code checks..."

# Check for duplicate code in layout
if grep -q "const accessibilityToggle = document.getElementById('accessibilityToggle')" resources/views/layouts/admin.blade.php; then
    echo "  ⚠ WARNING: Still found duplicate initialization code in layout"
else
    echo "  ✓ Duplicate code removed from layout (GOOD)"
fi

# Check for widget inclusion
if grep -q "@include('partials.accessibility-widget')" resources/views/layouts/admin.blade.php; then
    echo "  ✓ Widget partial is included in layout"
else
    echo "  ✗ Widget partial NOT included"
fi

# Check for widget HTML
if grep -q 'id="accessibilityToggle"' resources/views/partials/accessibility-widget.blade.php; then
    echo "  ✓ Toggle button HTML found"
else
    echo "  ✗ Toggle button HTML NOT found"
fi

# Check for widget CSS
if grep -q ".accessibility-toggle" resources/views/partials/accessibility-widget.blade.php; then
    echo "  ✓ CSS styles found"
else
    echo "  ✗ CSS styles NOT found"
fi

# Check for widget JavaScript
if grep -q "document.addEventListener('DOMContentLoaded'" resources/views/partials/accessibility-widget.blade.php; then
    echo "  ✓ JavaScript initialization found"
else
    echo "  ✗ JavaScript initialization NOT found"
fi

echo ""
echo "✓ Step 3: Browser Testing Instructions"
echo "  1. Open: http://127.0.0.1:8000/admin/dashboard"
echo "  2. Look for GREEN toggle button (bottom-right corner)"
echo "  3. Click it - panel should SLIDE UP"
echo "  4. Click \"Large\" button - text should get BIGGER"
echo "  5. Click \"Very High\" contrast - colors should change to YELLOW/BLACK"
echo "  6. Press ESC - panel should CLOSE"
echo "  7. Press Alt+A - panel should TOGGLE OPEN"
echo ""

echo "✓ Step 4: Browser Console Check (F12)"
echo "  Look for these SUCCESS messages:"
echo "    ✅ Accessibility widget elements found and ready"
echo "    ✓ Admin layout initialized successfully"
echo ""
echo "  Should NOT see any ERROR messages related to accessibility"
echo ""

echo "================================================"
echo "If all steps above pass, the accessibility"
echo "toggle is working correctly!"
echo "================================================"
