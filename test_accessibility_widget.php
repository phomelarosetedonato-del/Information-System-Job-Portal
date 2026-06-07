<?php
/**
 * Accessibility Widget Functionality Test
 * This script tests if the accessibility widget is working correctly in the admin dashboard
 */

// Start session to track accessibility settings
session_start();

// Get Laravel app instance
require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Simple HTML test to verify widget loading
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accessibility Widget Test</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            background: #f5f5f5;
        }
        .test-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 20px auto;
        }
        .test-section {
            margin: 20px 0;
            padding: 15px;
            border-left: 4px solid #2E8B57;
            background: #f9f9f9;
        }
        .test-result {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .pass {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .fail {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .icon {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="test-container">
        <h1><i class="fas fa-universal-access"></i> Accessibility Widget Test Report</h1>

        <div class="test-section">
            <h3>Widget File Verification</h3>
            <?php
            $widgetFile = __DIR__ . '/resources/views/partials/accessibility-widget.blade.php';
            if (file_exists($widgetFile)) {
                $fileSize = filesize($widgetFile);
                $lines = count(file($widgetFile));
                echo "<div class='test-result pass'>";
                echo "<span class='icon'>✓</span> Widget file exists at: " . $widgetFile;
                echo "<br>File size: " . number_format($fileSize) . " bytes | Lines: " . $lines;
                echo "</div>";
            } else {
                echo "<div class='test-result fail'>";
                echo "<span class='icon'>✗</span> Widget file NOT found at: " . $widgetFile;
                echo "</div>";
            }
            ?>
        </div>

        <div class="test-section">
            <h3>Required Elements Check</h3>
            <?php
            $widgetContent = file_get_contents($widgetFile);

            $checks = [
                'accessibilityToggle button' => 'id="accessibilityToggle"',
                'accessibilityPanel div' => 'id="accessibilityPanel"',
                'closeAccessibilityPanel button' => 'id="closeAccessibilityPanel"',
                'Font size controls' => 'data-size',
                'Contrast controls' => 'data-contrast',
                'Preset cards' => 'data-preset',
                'JavaScript toggle handler' => 'toggleBtn.addEventListener',
                'CSS .show rule' => '.accessibility-panel.show',
                'CSS toggle button' => '.accessibility-toggle',
                'localStorage integration' => 'localStorage.setItem',
            ];

            foreach ($checks as $label => $pattern) {
                if (strpos($widgetContent, $pattern) !== false) {
                    echo "<div class='test-result pass'>";
                    echo "<span class='icon'>✓</span> " . $label . " found";
                    echo "</div>";
                } else {
                    echo "<div class='test-result fail'>";
                    echo "<span class='icon'>✗</span> " . $label . " NOT found";
                    echo "</div>";
                }
            }
            ?>
        </div>

        <div class="test-section">
            <h3>Layout Integration Check</h3>
            <?php
            $layoutFile = __DIR__ . '/resources/views/layouts/admin.blade.php';
            if (file_exists($layoutFile)) {
                $layoutContent = file_get_contents($layoutFile);
                $checks = [
                    'Widget include' => "@include('partials.accessibility-widget')",
                    'Accessibility toggle JavaScript' => 'accessibilityToggle',
                    'Font size handler' => 'data-size',
                ];

                foreach ($checks as $label => $pattern) {
                    if (strpos($layoutContent, $pattern) !== false) {
                        echo "<div class='test-result pass'>";
                        echo "<span class='icon'>✓</span> " . $label . " found in layout";
                        echo "</div>";
                    } else {
                        echo "<div class='test-result fail'>";
                        echo "<span class='icon'>✗</span> " . $label . " NOT found in layout";
                        echo "</div>";
                    }
                }
            } else {
                echo "<div class='test-result fail'>";
                echo "<span class='icon'>✗</span> Layout file NOT found";
                echo "</div>";
            }
            ?>
        </div>

        <div class="test-section">
            <h3>Instructions for Browser Testing</h3>
            <ol>
                <li>Navigate to: <strong>http://127.0.0.1:8000/admin/dashboard</strong></li>
                <li>Look for the green circular <strong>Universal Access</strong> icon in the bottom-right corner</li>
                <li>Click the icon - it should open an accessibility panel with settings</li>
                <li>Test the following:
                    <ul>
                        <li><strong>Text Size:</strong> Click different size buttons and verify text changes</li>
                        <li><strong>Contrast Mode:</strong> Click contrast buttons and verify colors change</li>
                        <li><strong>Keyboard:</strong> Press <strong>Alt+A</strong> to toggle the panel</li>
                        <li><strong>Press ESC:</strong> Panel should close</li>
                    </ul>
                </li>
            </ol>
        </div>

        <div class="test-section" style="background: #fff3cd;">
            <h3>Quick Accessibility Test</h3>
            <p>If all tests above pass, the widget should work. Open the browser console (F12) and you should see debug logs like:</p>
            <code style="background: #f5f5f5; padding: 10px; display: block;">✅ Accessibility widget elements found and ready</code>
            <p>If you see errors like "element not found", there's a timing or structural issue.</p>
        </div>
    </div>

    <script>
        console.log('=== ACCESSIBILITY WIDGET TEST PAGE ===');
        console.log('Check the browser console for any JavaScript errors');
    </script>
</body>
</html>
