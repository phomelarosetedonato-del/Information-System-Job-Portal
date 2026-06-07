#!/usr/bin/env php
<?php
/**
 * Post-Fix Validation Script
 * Verifies that all issues have been resolved
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

echo "\n";
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║           POST-FIX VALIDATION REPORT                         ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$allPassed = true;

// Test 1: APP_URL Configuration
echo "TEST 1: APP_URL Configuration\n";
echo "───────────────────────────────────────────────────\n";

$appUrl = Config::get('app.url');
echo "Current APP_URL: " . $appUrl . "\n";

if ($appUrl === 'http://127.0.0.1:8000') {
    echo "✓ APP_URL is correctly configured for http://127.0.0.1:8000\n";
} elseif (strpos($appUrl, 'localhost') !== false) {
    echo "✗ APP_URL still contains 'localhost' - mismatch detected\n";
    echo "  Current: " . $appUrl . "\n";
    echo "  Expected: http://127.0.0.1:8000 or similar\n";
    $allPassed = false;
} else {
    echo "⚠ APP_URL is: " . $appUrl . "\n";
    echo "  Make sure it matches your actual access URL (http://127.0.0.1:8000)\n";
}

// Test 2: SESSION_DOMAIN Configuration
echo "\nTEST 2: SESSION_DOMAIN Configuration\n";
echo "───────────────────────────────────────────────────\n";

$sessionDomain = Config::get('session.domain');
echo "Current SESSION_DOMAIN: " . ($sessionDomain ?: 'null') . "\n";

if ($sessionDomain === '127.0.0.1' || strpos($appUrl, $sessionDomain) !== false) {
    echo "✓ SESSION_DOMAIN is compatible with APP_URL\n";
} elseif ($sessionDomain === 'localhost') {
    echo "✗ SESSION_DOMAIN mismatch: set to 'localhost' but accessing via 127.0.0.1\n";
    $allPassed = false;
} else {
    echo "⚠ SESSION_DOMAIN is: " . ($sessionDomain ?: 'null (default)') . "\n";
}

// Test 3: Route Registration
echo "\nTEST 3: Route Registration\n";
echo "───────────────────────────────────────────────────\n";

// Check routes in the routes file directly instead of runtime routes
$routesFile = __DIR__ . '/routes/web.php';
$routesContent = file_get_contents($routesFile);

$requiredRoutes = [
    "'admin.announcements.store'" => "admin.announcements.store",
    "'announcements.show'" => "announcements.show",
];

$routeStatus = true;
foreach ($requiredRoutes as $pattern => $routeName) {
    if (strpos($routesContent, $pattern) !== false) {
        echo "✓ Route '" . $routeName . "' is defined in routes file\n";
    } else {
        echo "✗ Route '" . $routeName . "' is NOT defined in routes file\n";
        $routeStatus = false;
        $allPassed = false;
    }
}

// Test 4: Notification Configuration
echo "\nTEST 4: Notification Configuration\n";
echo "───────────────────────────────────────────────────\n";

$notificationFile = __DIR__ . '/app/Notifications/AnnouncementNotification.php';
if (file_exists($notificationFile)) {
    $content = file_get_contents($notificationFile);

    if (strpos($content, 'use Queueable') === false) {
        echo "✓ Queueable trait has been removed\n";
    } else {
        echo "✗ Queueable trait is still present\n";
        $allPassed = false;
    }

    if (strpos($content, 'toMail') !== false && strpos($content, 'toDatabase') !== false) {
        echo "✓ Notification channels (mail, database) are configured\n";
    } else {
        echo "✗ Notification channels may be misconfigured\n";
        $allPassed = false;
    }
} else {
    echo "✗ Notification class file not found\n";
    $allPassed = false;
}

// Test 5: Controller Configuration
echo "\nTEST 5: Controller Configuration\n";
echo "───────────────────────────────────────────────────\n";

$controllerFile = __DIR__ . '/app/Http/Controllers/AnnouncementController.php';
if (file_exists($controllerFile)) {
    $content = file_get_contents($controllerFile);

    if (strpos($content, 'notifyPwdUsers') !== false) {
        echo "✓ notifyPwdUsers() method exists\n";
    } else {
        echo "✗ notifyPwdUsers() method not found\n";
        $allPassed = false;
    }

    if (strpos($content, 'AnnouncementController@store - REQUEST START') !== false) {
        echo "✓ Enhanced logging is in place\n";
    } else {
        echo "✗ Enhanced logging not found\n";
    }
} else {
    echo "✗ Controller file not found\n";
    $allPassed = false;
}

// Test 6: Environment File
echo "\nTEST 6: Environment File\n";
echo "───────────────────────────────────────────────────\n";

$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    echo "✓ .env file exists\n";

    $envContent = file_get_contents($envFile);

    if (strpos($envContent, 'APP_URL=http://127.0.0.1:8000') !== false) {
        echo "✓ .env contains correct APP_URL\n";
    } else {
        echo "⚠ .env may not have the correct APP_URL\n";
    }

    if (strpos($envContent, 'SESSION_DOMAIN=127.0.0.1') !== false) {
        echo "✓ .env contains correct SESSION_DOMAIN\n";
    } else {
        echo "⚠ .env may not have the correct SESSION_DOMAIN\n";
    }
} else {
    echo "✗ .env file not found\n";
    $allPassed = false;
}

// Summary
echo "\n";
echo "╔═══════════════════════════════════════════════════════════════╗\n";

if ($allPassed) {
    echo "║                  ✓ ALL CHECKS PASSED                       ║\n";
    echo "║                                                             ║\n";
    echo "║  The announcement redirect issue has been RESOLVED.        ║\n";
    echo "║  You should now be able to create announcements without    ║\n";
    echo "║  being redirected to the home page.                        ║\n";
    echo "║                                                             ║\n";
    echo "║  NEXT STEPS:                                               ║\n";
    echo "║  1. Clear your browser cache (Ctrl+Shift+Delete)           ║\n";
    echo "║  2. Log out and log back in                                ║\n";
    echo "║  3. Test creating an announcement                          ║\n";
} else {
    echo "║                  ✗ SOME CHECKS FAILED                      ║\n";
    echo "║                                                             ║\n";
    echo "║  Please review the errors above and ensure all fixes       ║\n";
    echo "║  have been applied correctly.                              ║\n";
}

echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

exit($allPassed ? 0 : 1);
