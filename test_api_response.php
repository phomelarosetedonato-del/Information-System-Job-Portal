<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Api\CommunityStatisticsApiController;
use App\Models\CommunityPwdStat;

echo "=== API ENDPOINT TEST ===\n\n";

// Create a mock request
$controller = new CommunityStatisticsApiController();

echo "Testing: GET /api/community-stats/2025\n\n";

$response = $controller->getStats(2025);
$content = $response->getContent();
$data = json_decode($content, true);

echo "Response Status: " . $response->getStatusCode() . "\n";
echo "Response Content:\n\n";
echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n\n";

echo str_repeat("=", 100) . "\n";
echo "API RESPONSE VALIDATION\n";
echo str_repeat("=", 100) . "\n\n";

if ($data['success']) {
    echo "✓ Success flag is TRUE\n";
} else {
    echo "✗ Success flag is FALSE\n";
}

if (isset($data['stats']) && is_array($data['stats'])) {
    echo "✓ Stats array present with " . count($data['stats']) . " records\n";
} else {
    echo "✗ Stats array missing or invalid\n";
}

if (isset($data['totals'])) {
    echo "✓ Totals object present\n";
    echo "  - total_unemployed: " . $data['totals']['total_unemployed'] . "\n";
    echo "  - total_employed: " . $data['totals']['total_employed'] . "\n";
    echo "  - total_pwd: " . $data['totals']['total_pwd'] . "\n";
    echo "  - employment_rate: " . $data['totals']['employment_rate'] . "%\n";
} else {
    echo "✗ Totals object missing\n";
}

echo "\n" . str_repeat("=", 100) . "\n";
echo "INDIVIDUAL STATS VALIDATION\n";
echo str_repeat("=", 100) . "\n\n";

foreach ($data['stats'] as $stat) {
    echo "- " . $stat['disability_type'] . ":\n";
    echo "  Unemployed: " . $stat['unemployed_count'] . "\n";
    echo "  Employed: " . $stat['employed_count'] . "\n";
}

echo "\nAPI TEST COMPLETE\n";
