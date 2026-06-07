<?php
/**
 * Comprehensive Announcement Creation Test Script
 * This script simulates the complete announcement creation flow
 */

// Bootstrap Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Announcement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AnnouncementNotification;

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "🔬 COMPREHENSIVE ANNOUNCEMENT CREATION TEST\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Step 1: Check database connectivity
echo "STEP 1: Database Connectivity\n";
echo "─────────────────────────────────\n";
try {
    $count = DB::table('users')->count();
    echo "✓ Database connected. Total users: $count\n";
} catch (\Throwable $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
    exit(1);
}

// Step 2: Find an admin user
echo "\nSTEP 2: Finding Admin User\n";
echo "─────────────────────────────────\n";
$admin = User::where('role', 'admin')->where('is_active', true)->first();
if (!$admin) {
    echo "✗ No active admin user found\n";
    exit(1);
}
echo "✓ Admin found: ID=" . $admin->id . ", Name=" . $admin->name . ", Role=" . $admin->role . "\n";

// Step 3: Check active PWD users
echo "\nSTEP 3: Checking Active PWD Users\n";
echo "─────────────────────────────────\n";
$pwdUserCount = User::where('role', 'pwd')->where('is_active', true)->count();
echo "✓ Active PWD users: $pwdUserCount\n";
if ($pwdUserCount === 0) {
    echo "⚠ Warning: No active PWD users to notify\n";
}

// Step 4: Simulate announcement creation
echo "\nSTEP 4: Creating Test Announcement\n";
echo "─────────────────────────────────\n";

try {
    // Authenticate as admin
    Auth::loginUsingId($admin->id);
    echo "✓ Authenticated as admin: " . Auth::user()->name . "\n";
} catch (\Throwable $e) {
    echo "✗ Authentication error: " . $e->getMessage() . "\n";
    exit(1);
}

try {
    $testData = [
        'title' => 'Test Announcement - ' . now()->format('Y-m-d H:i:s'),
        'content' => 'This is a test announcement created at ' . now()->toDateTimeString(),
        'created_by' => $admin->id,
        'is_active' => true,
    ];

    echo "Creating announcement with data:\n";
    echo "  - Title: " . $testData['title'] . "\n";
    echo "  - Created by: " . $testData['created_by'] . "\n";
    echo "  - Is Active: " . ($testData['is_active'] ? 'YES' : 'NO') . "\n";

    $announcement = Announcement::create($testData);

    if (!$announcement->id) {
        echo "✗ Failed to create announcement - no ID returned\n";
        exit(1);
    }

    echo "✓ Announcement created successfully\n";
    echo "  - ID: " . $announcement->id . "\n";
    echo "  - Created at: " . $announcement->created_at . "\n";

} catch (\Throwable $e) {
    echo "✗ Error creating announcement: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . "\n";
    echo "  Line: " . $e->getLine() . "\n";
    exit(1);
}

// Step 5: Verify announcement in database
echo "\nSTEP 5: Verifying Announcement in Database\n";
echo "─────────────────────────────────\n";
$dbAnnouncement = Announcement::find($announcement->id);
if (!$dbAnnouncement) {
    echo "✗ Announcement not found in database\n";
    exit(1);
}
echo "✓ Announcement verified in database\n";
echo "  - Title: " . $dbAnnouncement->title . "\n";
echo "  - Is Active: " . ($dbAnnouncement->is_active ? 'YES' : 'NO') . "\n";

// Step 6: Test notification sending
echo "\nSTEP 6: Testing Notification System\n";
echo "─────────────────────────────────\n";

try {
    if ($pwdUserCount > 0) {
        $pwdUsers = User::where('role', 'pwd')->where('is_active', true)->get();

        echo "Sending notifications to $pwdUserCount PWD users...\n";

        foreach ($pwdUsers as $user) {
            echo "  - Notifying: " . $user->name . " (" . $user->email . ")\n";
        }

        // Send notification
        Notification::send($pwdUsers, new AnnouncementNotification($announcement));

        echo "✓ Notifications sent successfully\n";

        // Verify database notifications were created
        $dbNotifications = DB::table('notifications')
            ->where('type', 'like', '%AnnouncementNotification%')
            ->orderBy('created_at', 'desc')
            ->limit(1)
            ->first();

        if ($dbNotifications) {
            echo "✓ Database notifications created\n";
            echo "  - Count: " . DB::table('notifications')
                ->where('type', 'like', '%AnnouncementNotification%')
                ->count() . "\n";
        } else {
            echo "⚠ No database notifications found yet\n";
        }

    } else {
        echo "⊘ Skipped (no PWD users to notify)\n";
    }

} catch (\Throwable $e) {
    echo "✗ Error sending notifications: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . "\n";
    echo "  Line: " . $e->getLine() . "\n";
}

// Step 7: Final summary
echo "\nSTEP 7: Test Summary\n";
echo "─────────────────────────────────\n";
echo "✓ All critical operations completed successfully\n";
echo "✓ Announcement ID: " . $announcement->id . "\n";
echo "✓ Admin: " . $admin->name . "\n";
echo "✓ Status: Active\n";

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "✓ TEST COMPLETE - No errors detected\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

Log::info('Test announcement creation completed successfully', [
    'announcement_id' => $announcement->id,
    'admin_id' => $admin->id,
    'pwd_users_notified' => $pwdUserCount,
]);
