<?php
try {
    $db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');

    echo "=== Announcement Verification ===\n\n";

    // Check announcements
    $stmt = $db->query('SELECT COUNT(*) as count FROM announcements');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Total announcements: " . $row['count'] . "\n";

    // Check recent announcements
    $stmt = $db->query('SELECT * FROM announcements ORDER BY created_at DESC LIMIT 3');
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($announcements)) {
        echo "\nRecent announcements:\n";
        foreach ($announcements as $ann) {
            echo "  - Title: {$ann['title']}\n";
            echo "    Active: {$ann['is_active']}\n";
            echo "    Created: {$ann['created_at']}\n\n";
        }
    }

    // Check notifications
    $stmt = $db->query('SELECT COUNT(*) as count FROM notifications');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nTotal notifications: " . $row['count'] . "\n";

    // Check recent notifications by type
    $stmt = $db->query('SELECT type, COUNT(*) as count FROM notifications GROUP BY type ORDER BY count DESC');
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($notifications)) {
        echo "\nNotifications by type:\n";
        foreach ($notifications as $notif) {
            echo "  - {$notif['type']}: {$notif['count']}\n";
        }
    }

    // Check PWD users
    $stmt = $db->query('SELECT COUNT(*) as count FROM users WHERE role = "pwd" AND is_active = 1');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\n\nActive PWD users: " . $row['count'] . "\n";

    // Check failed jobs
    $stmt = $db->query('SELECT COUNT(*) as count FROM failed_jobs');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Failed jobs: " . $row['count'] . "\n";

    if ($row['count'] > 0) {
        $stmt = $db->query('SELECT id, exception FROM failed_jobs ORDER BY failed_at DESC LIMIT 1');
        $failed = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "\nLatest failed job:\n";
        echo substr($failed['exception'], 0, 500) . "\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
?>
