#!/usr/bin/env php
<?php
/**
 * Announcement System Validation Script
 * Checks all components of the announcement creation and notification system
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

echo "\n";
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║          ANNOUNCEMENT SYSTEM VALIDATION                      ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$allPassed = true;

// Test 1: Check if routes are defined
echo "TEST 1: Verifying Routes\n";
echo "─────────────────────────────────\n";

$routes = collect(Route::getRoutes())->map(function ($route) {
    return $route->getName();
})->toArray();

$requiredRoutes = [
    'admin.announcements.index',
    'admin.announcements.create',
    'admin.announcements.store',
    'announcements.show',
];

foreach ($requiredRoutes as $routeName) {
    if (in_array($routeName, $routes)) {
        echo "✓ Route '$routeName' is defined\n";
    } else {
        echo "✗ Route '$routeName' is NOT defined\n";
        $allPassed = false;
    }
}

// Test 2: Check notification class
echo "\nTEST 2: Verifying Notification Class\n";
echo "─────────────────────────────────\n";

$notificationFile = __DIR__ . '/app/Notifications/AnnouncementNotification.php';
if (file_exists($notificationFile)) {
    echo "✓ Notification class file exists\n";

    $content = file_get_contents($notificationFile);

    if (strpos($content, 'use Queueable') === false) {
        echo "✓ Queueable trait has been removed\n";
    } else {
        echo "✗ Queueable trait is still present (should be removed)\n";
        $allPassed = false;
    }

    if (strpos($content, 'Log::info') !== false) {
        echo "✓ Logging has been added to notification\n";
    } else {
        echo "✗ Logging is missing from notification\n";
        $allPassed = false;
    }
} else {
    echo "✗ Notification class file not found\n";
    $allPassed = false;
}

// Test 3: Check controller
echo "\nTEST 3: Verifying Controller\n";
echo "─────────────────────────────────\n";

$controllerFile = __DIR__ . '/app/Http/Controllers/AnnouncementController.php';
if (file_exists($controllerFile)) {
    echo "✓ Controller file exists\n";

    $content = file_get_contents($controllerFile);

    if (strpos($content, 'public function publicShow') !== false) {
        echo "✓ publicShow() method exists\n";
    } else {
        echo "✗ publicShow() method not found\n";
        $allPassed = false;
    }

    if (strpos($content, 'notifyPwdUsers') !== false) {
        echo "✓ notifyPwdUsers() method exists\n";
    } else {
        echo "✗ notifyPwdUsers() method not found\n";
        $allPassed = false;
    }

    if (strpos($content, 'AnnouncementController@notifyPwdUsers - START') !== false) {
        echo "✓ Enhanced logging in notifyPwdUsers() exists\n";
    } else {
        echo "✗ Enhanced logging in notifyPwdUsers() not found\n";
        $allPassed = false;
    }
} else {
    echo "✗ Controller file not found\n";
    $allPassed = false;
}

// Test 4: Check Announcement model
echo "\nTEST 4: Verifying Announcement Model\n";
echo "─────────────────────────────────\n";

try {
    $model = new \App\Models\Announcement();

    $fillable = $model->getFillable();
    $expectedFillable = ['title', 'content', 'is_active', 'created_by'];

    $missingFields = array_diff($expectedFillable, $fillable);

    if (empty($missingFields)) {
        echo "✓ All required fields are fillable\n";
    } else {
        echo "✗ Missing fillable fields: " . implode(', ', $missingFields) . "\n";
        $allPassed = false;
    }
} catch (\Throwable $e) {
    echo "✗ Error checking model: " . $e->getMessage() . "\n";
    $allPassed = false;
}

// Test 5: Check database connectivity
echo "\nTEST 5: Verifying Database\n";
echo "─────────────────────────────────\n";

try {
    $announcements = \App\Models\Announcement::count();
    echo "✓ Database accessible. Current announcements: $announcements\n";
} catch (\Throwable $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
    $allPassed = false;
}

// Test 6: Check PWD users
echo "\nTEST 6: Verifying PWD Users\n";
echo "─────────────────────────────────\n";

try {
    $pwdUserCount = \App\Models\User::where('role', 'pwd')->where('is_active', true)->count();
    echo "✓ Active PWD users: $pwdUserCount\n";

    if ($pwdUserCount === 0) {
        echo "⚠ Warning: No active PWD users found. Notifications will not be sent.\n";
    }
} catch (\Throwable $e) {
    echo "✗ Error checking PWD users: " . $e->getMessage() . "\n";
    $allPassed = false;
}

// Summary
echo "\n";
echo "╔═══════════════════════════════════════════════════════════════╗\n";

if ($allPassed) {
    echo "║                    ✓ ALL TESTS PASSED                        ║\n";
    echo "║                                                             ║\n";
    echo "║  The announcement system is properly configured and ready  ║\n";
    echo "║  for use. You can now create announcements and send        ║\n";
    echo "║  notifications to PWD users.                               ║\n";
} else {
    echo "║                    ✗ SOME TESTS FAILED                       ║\n";
    echo "║                                                             ║\n";
    echo "║  Please review the errors above and ensure all required    ║\n";
    echo "║  components are properly installed and configured.         ║\n";
}

echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

exit($allPassed ? 0 : 1);
