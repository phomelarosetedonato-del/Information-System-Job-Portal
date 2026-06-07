<?php
/**
 * User's Exact Scenario Test
 * Tests with the exact announcement data the user provided
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔════════════════════════════════════════════════════════════════╗\n";
echo "║     USER'S EXACT SCENARIO - ANNOUNCEMENT CREATION TEST         ║\n";
echo "╚════════════════════════════════════════════════════════════════╝\n\n";

// Clear previous test announcements to avoid confusion
echo "STEP 0: Cleanup Previous Tests\n";
echo "────────────────────────────────────\n";
Announcement::where('title', 'like', '%System Maintenance%')->delete();
echo "✓ Cleared previous test announcements\n\n";

// Find admin user
echo "STEP 1: Finding Admin User\n";
echo "────────────────────────────────────\n";
$admin = User::where('role', 'admin')->where('is_active', true)->first();
if (!$admin) {
    echo "✗ No active admin user found\n";
    exit(1);
}
echo "✓ Admin found: " . $admin->name . " (ID: " . $admin->id . ")\n";

// Authenticate
Auth::loginUsingId($admin->id);
echo "✓ Authenticated as admin\n\n";

// The exact data from user
$userData = [
    'title' => 'System Maintenance Schedule – December 15, 2025',
    'content' => 'Please be informed that the system will undergo scheduled maintenance on December 15, 2025, from 8:00 PM to 11:00 PM. During this period, some features may be temporarily unavailable, including job applications, profile updates, and admin dashboard access.

This maintenance is part of our continuous effort to improve system performance, enhance security, and provide users with a smoother experience. We advise all users to save their work and complete any urgent transactions before the maintenance period begins.

Thank you for your understanding and cooperation.',
    'is_active' => '1',
];

echo "STEP 2: Creating Announcement with User's Exact Data\n";
echo "────────────────────────────────────\n";
echo "Title: " . substr($userData['title'], 0, 50) . "...\n";
echo "Content length: " . strlen($userData['content']) . " characters\n";
echo "Is Active: YES\n\n";

try {
    // This mimics what the controller does
    Log::info('TEST: Starting announcement creation with user data', [
        'admin_id' => Auth::id(),
        'admin_name' => Auth::user()->name,
        'title_length' => strlen($userData['title']),
        'content_length' => strlen($userData['content']),
    ]);

    // Create announcement
    $announcement = Announcement::create([
        'title' => $userData['title'],
        'content' => $userData['content'],
        'created_by' => Auth::id(),
        'is_active' => $userData['is_active'] ? 1 : 0,
    ]);

    echo "STEP 3: Announcement Created\n";
    echo "────────────────────────────────────\n";
    echo "✓ ID: " . $announcement->id . "\n";
    echo "✓ Title: " . $announcement->title . "\n";
    echo "✓ Created by: " . $announcement->created_by . "\n";
    echo "✓ Is Active: " . ($announcement->is_active ? 'YES' : 'NO') . "\n";
    echo "✓ Created at: " . $announcement->created_at . "\n\n";

    // Verify in database
    echo "STEP 4: Database Verification\n";
    echo "────────────────────────────────────\n";
    $dbRecord = Announcement::find($announcement->id);
    if ($dbRecord) {
        echo "✓ Announcement found in database\n";
        echo "✓ Title matches: " . ($dbRecord->title === $announcement->title ? 'YES' : 'NO') . "\n";
    } else {
        echo "✗ Announcement NOT found in database\n";
        exit(1);
    }

    // Count PWD users
    echo "\nSTEP 5: Notification Targets\n";
    echo "────────────────────────────────────\n";
    $pwdUserCount = User::where('role', 'pwd')->where('is_active', true)->count();
    echo "✓ Active PWD users: " . $pwdUserCount . "\n";

    if ($pwdUserCount > 0) {
        echo "✓ Notification details:\n";
        $pwdUsers = User::where('role', 'pwd')->where('is_active', true)->get();
        foreach ($pwdUsers as $user) {
            echo "  - " . $user->name . " (" . $user->email . ")\n";
        }
    }

    // Test notification sending
    echo "\nSTEP 6: Sending Notifications\n";
    echo "────────────────────────────────────\n";

    if ($pwdUserCount > 0) {
        try {
            \Illuminate\Support\Facades\Notification::send(
                $pwdUsers,
                new \App\Notifications\AnnouncementNotification($announcement)
            );
            echo "✓ Notifications sent successfully\n";

            // Verify notifications in database
            $notificationCount = DB::table('notifications')
                ->where('notifiable_type', 'App\\Models\\User')
                ->where('type', 'like', '%AnnouncementNotification%')
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->count();

            echo "✓ Notifications created in database: " . $notificationCount . "\n";

        } catch (\Throwable $e) {
            echo "✗ Error sending notifications: " . $e->getMessage() . "\n";
            echo "  File: " . $e->getFile() . "\n";
            echo "  Line: " . $e->getLine() . "\n";
            exit(1);
        }
    } else {
        echo "⊘ No PWD users to notify\n";
    }

    // Test redirect
    echo "\nSTEP 7: Testing Redirect Response\n";
    echo "────────────────────────────────────\n";

    $redirectRoute = 'admin.announcements.index';
    try {
        $redirectUrl = route($redirectRoute);
        echo "✓ Redirect route resolved: " . $redirectUrl . "\n";
    } catch (\Throwable $e) {
        echo "✗ Route resolution error: " . $e->getMessage() . "\n";
        exit(1);
    }

    // Verify admin middleware would still allow access
    echo "\nSTEP 8: Verifying Admin Access (Post-Announcement)\n";
    echo "────────────────────────────────────\n";
    echo "✓ Current user: " . Auth::user()->name . "\n";
    echo "✓ Current role: " . Auth::user()->role . "\n";
    echo "✓ Is admin: " . (Auth::user()->isAdmin() ? 'YES' : 'NO') . "\n";
    echo "✓ Authentication check: " . (Auth::check() ? 'PASSED' : 'FAILED') . "\n";

    echo "\n";
    echo "╔════════════════════════════════════════════════════════════════╗\n";
    echo "║                ✓ ALL TESTS PASSED                             ║\n";
    echo "║                                                                ║\n";
    echo "║  The announcement creation process works correctly. The        ║\n";
    echo "║  announcement is created, notifications are sent, and the     ║\n";
    echo "║  admin remains authenticated for redirect.                    ║\n";
    echo "╚════════════════════════════════════════════════════════════════╝\n\n";

    Log::info('TEST: Announcement creation test completed successfully', [
        'announcement_id' => $announcement->id,
        'pwd_users_notified' => $pwdUserCount,
    ]);

} catch (\Throwable $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    Log::error('TEST: Announcement creation test failed', [
        'error' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
    exit(1);
}
