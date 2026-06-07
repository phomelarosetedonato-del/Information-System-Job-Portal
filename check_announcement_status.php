<?php
// Quick verification script
try {
    $db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check announcements
    $stmt = $db->query('SELECT COUNT(*) as count FROM announcements');
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $announcements = $result['count'];

    // Check if table exists
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='announcements'");
    $tableExists = $stmt->fetch(PDO::FETCH_ASSOC);

    echo "Announcements table exists: " . ($tableExists ? 'YES' : 'NO') . "\n";
    echo "Total announcements: $announcements\n";

    if ($announcements > 0) {
        echo "\nRecent announcements:\n";
        $stmt = $db->query('SELECT id, title, is_active, created_at FROM announcements ORDER BY id DESC LIMIT 3');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo sprintf("  [%d] %s (Active: %s, Created: %s)\n",
                $row['id'], $row['title'], $row['is_active'], $row['created_at']
            );
        }
    }

    // Check notifications
    $stmt = $db->query("SELECT COUNT(*) as count FROM notifications");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nDatabase notifications: " . $result['count'] . "\n";

    // Check users
    $stmt = $db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
    echo "\nUsers by role:\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "  " . $row['role'] . ": " . $row['count'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
