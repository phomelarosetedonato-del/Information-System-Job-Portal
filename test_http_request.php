<?php
/**
 * HTTP Request Simulation Test
 * Simulates form POST to /admin/announcements
 */

echo "╔════════════════════════════════════════════════════════╗\n";
echo "║     TESTING ANNOUNCEMENT FORM SUBMISSION              ║\n";
echo "╚════════════════════════════════════════════════════════╝\n\n";

// Get base URL
$baseUrl = 'http://127.0.0.1:8000';
$csrfTokenUrl = $baseUrl . '/admin/announcements/create';
$storeUrl = $baseUrl . '/admin/announcements';

echo "Base URL: $baseUrl\n";
echo "Form URL: $csrfTokenUrl\n";
echo "Store URL: $storeUrl\n\n";

// Step 1: Get CSRF token
echo "Step 1: Fetching CSRF token...\n";
$createPageContent = @file_get_contents($csrfTokenUrl);

if ($createPageContent === false) {
    echo "❌ ERROR: Could not fetch create page\n";
    echo "   Make sure your Laravel app is running on $baseUrl\n";
    exit(1);
}

// Extract CSRF token
preg_match('/<input[^>]*name="_token"[^>]*value="([^"]+)"/', $createPageContent, $matches);
$csrfToken = $matches[1] ?? null;

if (!$csrfToken) {
    echo "❌ ERROR: Could not find CSRF token\n";
    exit(1);
}

echo "✓ CSRF Token found: " . substr($csrfToken, 0, 10) . "...\n\n";

// Step 2: Simulate form submission
echo "Step 2: Submitting announcement form...\n";

$postData = [
    '_token' => $csrfToken,
    'title' => 'Test Announcement - ' . date('Y-m-d H:i:s'),
    'content' => 'This is a test announcement created at ' . date('Y-m-d H:i:s'),
    'is_active' => '1',
];

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => http_build_query($postData),
        'follow_location' => false, // Don't follow redirects, we want to see them
    ]
]);

$response = @file_get_contents($storeUrl, false, $context);
$headers = $http_response_header ?? [];

echo "Response headers:\n";
foreach ($headers as $header) {
    if (stripos($header, 'HTTP') === 0 || stripos($header, 'Location') === 0) {
        echo "  $header\n";
    }
}

// Check for redirect
$redirectUrl = null;
foreach ($headers as $header) {
    if (stripos($header, 'Location:') === 0) {
        $redirectUrl = trim(substr($header, 9));
        break;
    }
}

if ($redirectUrl) {
    echo "\n✓ Redirect detected: $redirectUrl\n";

    if (stripos($redirectUrl, '/login') !== false) {
        echo "⚠ PROBLEM: Redirected to login page!\n";
        echo "  This means admin session was lost!\n";
    } elseif (stripos($redirectUrl, '/admin/announcements') !== false) {
        echo "✓ Correct: Redirected to announcements list\n";
    }
} else {
    echo "❌ No redirect header found\n";
}

// Step 3: Check if announcement was created
echo "\nStep 3: Checking if announcement was created...\n";

$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query('SELECT COUNT(*) as count FROM announcements');
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalAnnouncements = $row['count'];

echo "Total announcements in database: $totalAnnouncements\n";

if ($totalAnnouncements > 0) {
    $stmt = $db->query('SELECT id, title, created_at FROM announcements ORDER BY id DESC LIMIT 1');
    $latest = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "\nLatest announcement:\n";
    echo "  ID: {$latest['id']}\n";
    echo "  Title: {$latest['title']}\n";
    echo "  Created: {$latest['created_at']}\n";
}

echo "\n═══════════════════════════════════════════════════════\n";

?>
