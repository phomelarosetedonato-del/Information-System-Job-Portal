<?php
/**
 * Test Email Sending with Updated User Addresses
 */

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Announcement;
use App\Notifications\AnnouncementNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║      EMAIL DELIVERY TEST WITH REAL ADDRESSES              ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Get all PWD users
$pwdUsers = User::where('role', 'pwd')->where('is_active', true)->get();

echo "PWD Users to Notify:\n";
echo "───────────────────────────────────\n";
foreach ($pwdUsers as $user) {
    echo "  - " . $user->name . " (" . $user->email . ")\n";
}
echo "\n";

// Get the latest announcement (or create one)
$announcement = Announcement::latest()->first();

if (!$announcement) {
    echo "No announcements found. Creating test announcement...\n\n";

    $admin = User::where('role', 'admin')->first();
    if (!$admin) {
        echo "✗ No admin user found\n";
        exit(1);
    }

    $announcement = Announcement::create([
        'title' => 'Test Announcement - Email Delivery Test',
        'content' => 'This is a test announcement to verify email delivery is working correctly.',
        'created_by' => $admin->id,
        'is_active' => true,
    ]);

    echo "✓ Test announcement created (ID: " . $announcement->id . ")\n\n";
}

echo "Announcement Details:\n";
echo "───────────────────────────────────\n";
echo "  ID: " . $announcement->id . "\n";
echo "  Title: " . $announcement->title . "\n";
echo "  Active: " . ($announcement->is_active ? 'YES' : 'NO') . "\n\n";

// Test sending email
echo "Sending Notifications:\n";
echo "───────────────────────────────────\n";

try {
    foreach ($pwdUsers as $user) {
        echo "  → Sending to: " . $user->email . " (" . $user->name . ")\n";
    }

    echo "\n  Processing...\n";

    Notification::send($pwdUsers, new AnnouncementNotification($announcement));

    echo "\n✓ Notifications sent successfully!\n\n";

    echo "Email Details:\n";
    echo "───────────────────────────────────\n";
    echo "  From: PWD System <phomelarosetedonato@gmail.com>\n";
    echo "  Recipients:\n";
    foreach ($pwdUsers as $user) {
        echo "    - " . $user->email . "\n";
    }
    echo "  Subject: 📢 New Announcement: " . substr($announcement->title, 0, 40) . "...\n";
    echo "  Content: First 100 chars of announcement...\n\n";

    echo "✓ Email sending is working correctly!\n\n";

} catch (\Throwable $e) {
    echo "✗ Error sending notifications: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . "\n";
    echo "  Line: " . $e->getLine() . "\n\n";

    echo "Troubleshooting:\n";
    echo "  1. Check that MAIL credentials are correct in .env\n";
    echo "  2. Verify Gmail app password is configured\n";
    echo "  3. Check that 'Less secure app access' is enabled (if needed)\n";
    echo "  4. Verify internet connection\n\n";

    exit(1);
}

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                    ✓ TEST COMPLETE                        ║\n";
echo "║                                                            ║\n";
echo "║  Emails should be delivered to:                           ║\n";
foreach ($pwdUsers as $user) {
    echo "║    - " . str_pad($user->email, 52) . "║\n";
}
echo "╚════════════════════════════════════════════════════════════╝\n\n";

Log::info('Email delivery test completed successfully', [
    'announcement_id' => $announcement->id,
    'users_notified' => $pwdUsers->count(),
    'emails' => $pwdUsers->pluck('email')->toArray(),
]);
