<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Admin\CommunityStatisticsController;
use App\Models\CommunityPwdStat;

echo "=== EDIT PAGE CALCULATIONS TEST ===\n\n";

$controller = new CommunityStatisticsController();

echo "Testing: Edit page for year 2025\n\n";

// Get stats for 2025
$stats = CommunityPwdStat::where('year', 2025)->orderBy('disability_type')->get();

// Calculate totals using controller method
$totals = $controller->calculateTotals($stats);

echo "Data loaded from database:\n";
echo "  Total records: " . $stats->count() . "\n\n";

echo str_repeat("=", 100) . "\n";
echo "CONTROLLER calculateTotals() OUTPUT\n";
echo str_repeat("=", 100) . "\n\n";

echo json_encode($totals, JSON_PRETTY_PRINT) . "\n\n";

echo str_repeat("=", 100) . "\n";
echo "INDIVIDUAL RECORD CALCULATIONS\n";
echo str_repeat("=", 100) . "\n\n";

echo sprintf("%-40s | %15s | %15s | %15s | %15s\n", "Disability Type", "Unemployed", "Employed", "Total", "Employment Rate");
echo str_repeat("-", 100) . "\n";

$runningTotalUnemployed = 0;
$runningTotalEmployed = 0;

foreach ($stats as $stat) {
    $total = $stat->unemployed_count + $stat->employed_count;
    $rate = $stat->getEmploymentRate();

    $runningTotalUnemployed += $stat->unemployed_count;
    $runningTotalEmployed += $stat->employed_count;

    echo sprintf("%-40s | %15d | %15d | %15d | %14.2f%%\n",
        $stat->disability_type,
        $stat->unemployed_count,
        $stat->employed_count,
        $total,
        $rate
    );
}

$runningTotalPwd = $runningTotalUnemployed + $runningTotalEmployed;
$runningEmploymentRate = $runningTotalPwd > 0 ? round(($runningTotalEmployed / $runningTotalPwd) * 100, 2) : 0;

echo str_repeat("-", 100) . "\n";
echo sprintf("%-40s | %15d | %15d | %15d | %14.2f%%\n",
    "TOTAL (Manual Calculation)",
    $runningTotalUnemployed,
    $runningTotalEmployed,
    $runningTotalPwd,
    $runningEmploymentRate
);

echo "\n" . str_repeat("=", 100) . "\n";
echo "VERIFICATION: Controller vs Manual Calculation\n";
echo str_repeat("=", 100) . "\n\n";

$match_unemployed = $totals['total_unemployed'] == $runningTotalUnemployed ? "✓ MATCH" : "✗ MISMATCH";
$match_employed = $totals['total_employed'] == $runningTotalEmployed ? "✓ MATCH" : "✗ MISMATCH";
$match_pwd = $totals['total_pwd'] == $runningTotalPwd ? "✓ MATCH" : "✗ MISMATCH";
$match_rate = $totals['employment_rate'] == $runningEmploymentRate ? "✓ MATCH" : "✗ MISMATCH";

echo "Total Unemployed: " . $totals['total_unemployed'] . " vs " . $runningTotalUnemployed . " - " . $match_unemployed . "\n";
echo "Total Employed: " . $totals['total_employed'] . " vs " . $runningTotalEmployed . " - " . $match_employed . "\n";
echo "Total PWD: " . $totals['total_pwd'] . " vs " . $runningTotalPwd . " - " . $match_pwd . "\n";
echo "Employment Rate: " . $totals['employment_rate'] . "% vs " . $runningEmploymentRate . "% - " . $match_rate . "\n";

echo "\n" . str_repeat("=", 100) . "\n";
echo "EDIT PAGE TEST COMPLETE\n";
echo str_repeat("=", 100) . "\n";
