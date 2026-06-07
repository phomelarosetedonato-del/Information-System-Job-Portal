<?php
/**
 * Complete Announcement Test Script
 * Tests the full announcement workflow including creation and notifications
 */

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║       ANNOUNCEMENT SYSTEM VERIFICATION TEST              ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

try {
    $db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Database connection successful\n\n";

    // ===== CHECK DATABASE STRUCTURE =====
    echo "【 DATABASE STRUCTURE 】\n";
    echo "─────────────────────────────────────\n";

    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='announcements'");
    $announcementsTableExists = $stmt->fetch(PDO::FETCH_ASSOC);
    echo ($announcementsTableExists ? "✓" : "✗") . " Announcements table: " . ($announcementsTableExists ? "EXISTS" : "MISSING") . "\n";

    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='notifications'");
    $notificationsTableExists = $stmt->fetch(PDO::FETCH_ASSOC);
    echo ($notificationsTableExists ? "✓" : "✗") . " Notifications table: " . ($notificationsTableExists ? "EXISTS" : "MISSING") . "\n";

    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
    $usersTableExists = $stmt->fetch(PDO::FETCH_ASSOC);
    echo ($usersTableExists ? "✓" : "✗") . " Users table: " . ($usersTableExists ? "EXISTS" : "MISSING") . "\n\n";

    // ===== CHECK DATA =====
    echo "【 DATA STATUS 】\n";
    echo "─────────────────────────────────────\n";

    $stmt = $db->query('SELECT COUNT(*) as count FROM announcements');
    $announcementCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "📢 Total announcements: $announcementCount\n";

    if ($announcementCount > 0) {
        echo "\n   Recent announcements:\n";
        $stmt = $db->query('SELECT id, title, is_active, created_at FROM announcements ORDER BY id DESC LIMIT 5');
        $i = 1;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $status = $row['is_active'] ? '🟢 Active' : '⚪ Inactive';
            echo "   $i. [{$row['id']}] {$row['title']}\n      Status: $status | Created: {$row['created_at']}\n";
            $i++;
        }
    }

    $stmt = $db->query('SELECT COUNT(*) as count FROM notifications');
    $notificationCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "\n🔔 Total notifications: $notificationCount\n";

    if ($notificationCount > 0) {
        echo "\n   Notifications by type:\n";
        $stmt = $db->query('SELECT type, COUNT(*) as count FROM notifications GROUP BY type ORDER BY count DESC');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "   • {$row['type']}: {$row['count']}\n";
        }
    }

    // ===== CHECK USERS =====
    echo "\n👥 Users status:\n";
    $stmt = $db->query("SELECT role, COUNT(*) as count, COUNT(CASE WHEN is_active=1 THEN 1 END) as active FROM users GROUP BY role ORDER BY role");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $roleLabel = match($row['role']) {
            'admin' => '👨‍💼 Admin',
            'pwd' => '♿ PWD',
            'employer' => '🏢 Employer',
            default => '👤 ' . ucfirst($row['role'])
        };
        echo "   $roleLabel: {$row['active']}/{$row['count']} active\n";
    }

    // ===== CHECK QUEUE STATUS =====
    echo "\n【 QUEUE STATUS 】\n";
    echo "─────────────────────────────────────\n";

    $stmt = $db->query('SELECT COUNT(*) as count FROM jobs');
    $jobCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "⏳ Pending jobs: $jobCount\n";

    $stmt = $db->query('SELECT COUNT(*) as count FROM failed_jobs');
    $failedJobCount = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    echo "❌ Failed jobs: $failedJobCount\n";

    if ($failedJobCount > 0) {
        echo "\n   Recent failed jobs:\n";
        $stmt = $db->query('SELECT id, queue, payload FROM failed_jobs ORDER BY failed_at DESC LIMIT 3');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $payload = substr($row['payload'], 0, 100);
            echo "   • Queue: {$row['queue']}\n     Payload: {$payload}...\n";
        }
    }

    // ===== RECOMMENDATIONS =====
    echo "\n【 SUMMARY & NEXT STEPS 】\n";
    echo "─────────────────────────────────────\n";

    if ($announcementCount === 0) {
        echo "⚠ No announcements have been created yet.\n\n";
        echo "ACTION: Try creating an announcement through the admin panel:\n";
        echo "1. Go to http://127.0.0.1:8000/admin/announcements/create\n";
        echo "2. Fill in Title and Content\n";
        echo "3. Check 'Make this announcement active'\n";
        echo "4. Click 'Create Announcement'\n";
        echo "5. Run this script again to verify\n";
    } else {
        echo "✓ Announcements are being created successfully!\n\n";

        if ($notificationCount > 0) {
            echo "✓ Notifications are being stored!\n";
            echo "  - Check PWD user dashboard for notification bell icon\n";
            echo "  - Notifications should appear when user logs in\n";
        } else {
            echo "⚠ No notifications recorded yet.\n";
            echo "  - Verify active PWD users exist\n";
            echo "  - Check logs for notification sending errors\n";
        }
    }

    echo "\n【 LOG FILE LOCATION 】\n";
    echo "─────────────────────────────────────\n";
    echo "storage/logs/laravel.log\n";
    echo "Search for: 'AnnouncementController' for detailed request logs\n";
    echo "Search for: 'AdminMiddleware' to check authorization\n\n";

} catch (PDOException $e) {
    echo "❌ DATABASE ERROR: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

echo "═══════════════════════════════════════════════════════════\n";
echo "Test completed at " . date('Y-m-d H:i:s') . "\n";
?>
