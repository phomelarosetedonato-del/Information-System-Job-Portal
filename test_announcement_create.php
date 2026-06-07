<?php
// Simple test to create an announcement programmatically
// This mimics what the form submission does

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\Announcement;
use App\Models\User;

// Boot the application
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$request = Illuminate\Http\Request::capture();
$app->bind('request', $request);

echo "=== Announcement Creation Test ===\n\n";

// Get admin user
$admin = User::where('role', 'admin')->first();
if (!$admin) {
    echo "ERROR: No admin user found\n";
    exit(1);
}

echo "Admin user: " . $admin->name . " (ID: " . $admin->id . ")\n\n";

// Manually create an announcement (bypassing form)
echo "Creating announcement...\n";
try {
    $announcement = Announcement::create([
        'title' => 'Test Announcement - ' . date('Y-m-d H:i:s'),
        'content' => 'This is a test announcement to verify the system is working. Created on ' . date('Y-m-d H:i:s'),
        'created_by' => $admin->id,
        'is_active' => 1,
    ]);

    echo "✓ Announcement created successfully!\n";
    echo "  - ID: " . $announcement->id . "\n";
    echo "  - Title: " . $announcement->title . "\n";
    echo "  - Active: " . ($announcement->is_active ? 'Yes' : 'No') . "\n";
    echo "  - Created at: " . $announcement->created_at . "\n\n";

    // Check database
    $count = Announcement::count();
    echo "Total announcements in database: $count\n\n";

    // Check notifications
    $notifCount = \Illuminate\Notifications\DatabaseNotification::count();
    echo "Total notifications in database: $notifCount\n\n";

    // Try to send notifications
    echo "Attempting to send notifications to PWD users...\n";
    $pwdUsers = User::where('role', 'pwd')->where('is_active', 1)->get();
    echo "Active PWD users found: " . $pwdUsers->count() . "\n";

    if ($pwdUsers->isNotEmpty()) {
        try {
            \Illuminate\Support\Facades\Notification::send($pwdUsers, new \App\Notifications\AnnouncementNotification($announcement));
            echo "✓ Notifications sent successfully!\n";

            // Check notifications again
            $notifCount = \Illuminate\Notifications\DatabaseNotification::count();
            echo "Total notifications in database after sending: $notifCount\n";
        } catch (\Exception $e) {
            echo "✗ Error sending notifications: " . $e->getMessage() . "\n";
            echo "Trace: " . $e->getTraceAsString() . "\n";
        }
    } else {
        echo "⚠ No active PWD users to notify\n";
    }

} catch (\Exception $e) {
    echo "✗ Error creating announcement: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n✓ Test completed successfully!\n";
?>
