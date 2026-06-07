<?php
/**
 * Direct Database Test - Create Announcement Directly
 * Bypasses all middleware and controllers
 */

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Get the service provider kernel
$app->make(Illuminate\Contracts\Console\Kernel::class);

try {
    echo "╔════════════════════════════════════════════════════════╗\n";
    echo "║     DIRECT ANNOUNCEMENT CREATION TEST                  ║\n";
    echo "╚════════════════════════════════════════════════════════╝\n\n";

    // Find an admin user
    $admin = User::where('role', 'admin')->first();

    if (!$admin) {
        echo "❌ ERROR: No admin user found in database\n";
        echo "   Users in database:\n";
        $users = User::select('id', 'name', 'role')->get();
        foreach ($users as $user) {
            echo "   - ID: {$user->id}, Name: {$user->name}, Role: {$user->role}\n";
        }
        exit(1);
    }

    echo "✓ Found admin user: {$admin->name} (ID: {$admin->id})\n\n";

    // Try to create an announcement
    echo "Attempting to create announcement directly in database...\n";

    $announcement = Announcement::create([
        'title' => 'Direct Test Announcement - ' . date('Y-m-d H:i:s'),
        'content' => 'This announcement was created directly via code at ' . date('Y-m-d H:i:s'),
        'created_by' => $admin->id,
        'is_active' => 1,
    ]);

    echo "✅ SUCCESS! Announcement created!\n";
    echo "   ID: {$announcement->id}\n";
    echo "   Title: {$announcement->title}\n";
    echo "   Active: " . ($announcement->is_active ? 'Yes' : 'No') . "\n";
    echo "   Created By: {$announcement->created_by}\n";
    echo "   Created At: {$announcement->created_at}\n\n";

    // Verify it's in database
    $count = Announcement::count();
    echo "✓ Total announcements in database: $count\n\n";

    // Check if foreign key constraint works
    echo "Testing foreign key constraint...\n";
    try {
        $badAnnouncement = Announcement::create([
            'title' => 'Bad Announcement',
            'content' => 'This should fail',
            'created_by' => 99999, // Non-existent user
            'is_active' => 1,
        ]);
        echo "❌ PROBLEM: Foreign key constraint NOT enforced!\n";
        $badAnnouncement->delete(); // Clean up
    } catch (\Exception $e) {
        echo "✓ Foreign key constraint works correctly\n";
        echo "   Error: " . $e->getMessage() . "\n";
    }

    echo "\n═══════════════════════════════════════════════════════\n";
    echo "✅ TEST PASSED: Announcements CAN be created in database\n";
    echo "═══════════════════════════════════════════════════════\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
    exit(1);
}
?>
