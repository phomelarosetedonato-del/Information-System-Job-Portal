<?php
/**
 * Complete Email Delivery Verification
 */

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Config;

echo "\n";
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║         EMAIL DELIVERY VERIFICATION REPORT                   ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

$allPassed = true;

// Test 1: Mail Configuration
echo "TEST 1: Mail Configuration\n";
echo "───────────────────────────────────────────────────\n";

$mailDriver = Config::get('mail.driver');
$mailMailer = Config::get('mail.default');
$smtpHost = Config::get('mail.mailers.smtp.host');
$smtpPort = Config::get('mail.mailers.smtp.port');
$fromAddress = Config::get('mail.from.address');

echo "✓ Mail Driver: " . ($mailMailer ?: 'default') . "\n";
echo "✓ SMTP Host: " . $smtpHost . "\n";
echo "✓ SMTP Port: " . $smtpPort . "\n";
echo "✓ From Address: " . $fromAddress . "\n";

if (!$smtpHost || !$smtpPort) {
    echo "✗ SMTP configuration incomplete\n";
    $allPassed = false;
}

// Test 2: PWD Users Email Addresses
echo "\nTEST 2: PWD Users Email Addresses\n";
echo "───────────────────────────────────────────────────\n";

$pwdUsers = User::where('role', 'pwd')->where('is_active', true)->get();

if ($pwdUsers->isEmpty()) {
    echo "✗ No active PWD users found\n";
    $allPassed = false;
} else {
    echo "✓ Found " . $pwdUsers->count() . " active PWD users\n\n";

    foreach ($pwdUsers as $user) {
        $email = $user->email;

        // Check if it's a valid email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Check if it's not the fake domain
            if (strpos($email, 'example.com') !== false) {
                echo "✗ User: " . $user->name . " - " . $email . " (FAKE DOMAIN)\n";
                $allPassed = false;
            } else {
                echo "✓ User: " . $user->name . " - " . $email . " (VALID)\n";
            }
        } else {
            echo "✗ User: " . $user->name . " - " . $email . " (INVALID FORMAT)\n";
            $allPassed = false;
        }
    }
}

// Test 3: Notification Class
echo "\nTEST 3: Notification Class Configuration\n";
echo "───────────────────────────────────────────────────\n";

$notificationFile = __DIR__ . '/app/Notifications/AnnouncementNotification.php';
if (file_exists($notificationFile)) {
    $content = file_get_contents($notificationFile);

    if (strpos($content, 'toMail') !== false) {
        echo "✓ toMail() method exists\n";
    } else {
        echo "✗ toMail() method not found\n";
        $allPassed = false;
    }

    if (strpos($content, 'toDatabase') !== false) {
        echo "✓ toDatabase() method exists\n";
    } else {
        echo "✗ toDatabase() method not found\n";
        $allPassed = false;
    }

    if (strpos($content, 'use Queueable') === false) {
        echo "✓ Queueable trait is removed (synchronous sending)\n";
    } else {
        echo "✗ Queueable trait is still present\n";
        $allPassed = false;
    }
} else {
    echo "✗ Notification class file not found\n";
    $allPassed = false;
}

// Test 4: Route Configuration
echo "\nTEST 4: Route Configuration\n";
echo "───────────────────────────────────────────────────\n";

$routesFile = __DIR__ . '/routes/web.php';
$routesContent = file_get_contents($routesFile);

if (strpos($routesContent, "'announcements.show'") !== false) {
    echo "✓ Public announcement route is defined\n";
} else {
    echo "✗ Public announcement route not found\n";
    $allPassed = false;
}

if (strpos($routesContent, "AnnouncementController::class, 'store'") !== false) {
    echo "✓ Store announcement route is defined\n";
} else {
    echo "✗ Store announcement route not found\n";
    $allPassed = false;
}

// Test 5: Announcement Exists
echo "\nTEST 5: Sample Announcement\n";
echo "───────────────────────────────────────────────────\n";

$announcement = \App\Models\Announcement::latest()->first();

if ($announcement) {
    echo "✓ Latest announcement found\n";
    echo "  ID: " . $announcement->id . "\n";
    echo "  Title: " . substr($announcement->title, 0, 50) . "...\n";
    echo "  Active: " . ($announcement->is_active ? 'YES' : 'NO') . "\n";
} else {
    echo "⚠ No announcements in database\n";
}

// Summary
echo "\n";
echo "╔═══════════════════════════════════════════════════════════════╗\n";

if ($allPassed) {
    echo "║                  ✓ ALL CHECKS PASSED                       ║\n";
    echo "║                                                             ║\n";
    echo "║  Email delivery is properly configured. PWD users should   ║\n";
    echo "║  receive email notifications when announcements are        ║\n";
    echo "║  created.                                                   ║\n";
    echo "║                                                             ║\n";
    echo "║  Expected Recipients:                                      ║\n";
    foreach ($pwdUsers as $user) {
        echo "║    - " . str_pad($user->email, 55) . "║\n";
    }
} else {
    echo "║                  ✗ SOME CHECKS FAILED                      ║\n";
    echo "║                                                             ║\n";
    echo "║  Please review the errors above and ensure:                ║\n";
    echo "║  1. Mail configuration is correct in .env                  ║\n";
    echo "║  2. PWD users have valid email addresses                   ║\n";
    echo "║  3. Notification class is properly configured              ║\n";
}

echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

exit($allPassed ? 0 : 1);
