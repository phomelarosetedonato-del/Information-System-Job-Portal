<?php
/**
 * Check PWD User Email Addresses
 */

$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query('SELECT id, name, email, role FROM users WHERE role="pwd" AND is_active=1');

echo "\nPWD Users in Database:\n";
echo "═══════════════════════════════════════════════════════\n\n";

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: " . $row['id'] . "\n";
    echo "Name: " . $row['name'] . "\n";
    echo "Email: " . $row['email'] . "\n";
    echo "Role: " . $row['role'] . "\n";
    echo "───────────────────────────────────────────────────\n\n";
}

// Check email configuration
echo "\nMail Configuration:\n";
echo "═══════════════════════════════════════════════════════\n\n";

$envFile = file_get_contents('.env');
preg_match('/MAIL_DRIVER=(\S+)/i', $envFile, $driverMatch);
preg_match('/MAIL_HOST=(\S+)/i', $envFile, $hostMatch);
preg_match('/MAIL_PORT=(\S+)/i', $envFile, $portMatch);
preg_match('/MAIL_FROM_ADDRESS=(\S+)/i', $envFile, $fromMatch);

echo "MAIL_DRIVER: " . (isset($driverMatch[1]) ? $driverMatch[1] : 'NOT SET') . "\n";
echo "MAIL_HOST: " . (isset($hostMatch[1]) ? $hostMatch[1] : 'NOT SET') . "\n";
echo "MAIL_PORT: " . (isset($portMatch[1]) ? $portMatch[1] : 'NOT SET') . "\n";
echo "MAIL_FROM_ADDRESS: " . (isset($fromMatch[1]) ? $fromMatch[1] : 'NOT SET') . "\n";

echo "\n";
