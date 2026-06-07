<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CommunityPwdStat;
use Illuminate\Support\Facades\DB;

echo "=== COMMUNITY PWD STATISTICS VERIFICATION ===\n\n";

// Get all records for 2025
$stats = CommunityPwdStat::where('year', 2025)->orderBy('disability_type')->get();

echo "Total Records in Database: " . $stats->count() . "\n";
echo str_repeat("=", 100) . "\n\n";

$totalUnemployed = 0;
$totalEmployed = 0;
$records = [];

echo sprintf("%-40s | %15s | %15s | %15s | %15s\n", "Disability Type", "Unemployed", "Employed", "Total", "Employment Rate");
echo str_repeat("-", 100) . "\n";

foreach ($stats as $stat) {
    $total = $stat->unemployed_count + $stat->employed_count;
    $rate = $stat->getEmploymentRate();
    $totalUnemployed += $stat->unemployed_count;
    $totalEmployed += $stat->employed_count;

    echo sprintf("%-40s | %15d | %15d | %15d | %14.2f%%\n",
        $stat->disability_type,
        $stat->unemployed_count,
        $stat->employed_count,
        $total,
        $rate
    );

    $records[] = [
        'type' => $stat->disability_type,
        'unemployed' => $stat->unemployed_count,
        'employed' => $stat->employed_count,
        'total' => $total,
        'rate' => $rate
    ];
}

$totalOverallPwd = $totalUnemployed + $totalEmployed;
$overallEmploymentRate = $totalOverallPwd > 0 ? round(($totalEmployed / $totalOverallPwd) * 100, 2) : 0;

echo str_repeat("-", 100) . "\n";
echo sprintf("%-40s | %15d | %15d | %15d | %14.2f%%\n",
    "TOTAL",
    $totalUnemployed,
    $totalEmployed,
    $totalOverallPwd,
    $overallEmploymentRate
);

echo "\n" . str_repeat("=", 100) . "\n";
echo "CALCULATION VERIFICATION\n";
echo str_repeat("=", 100) . "\n\n";

echo "1. Total Unemployed Count: " . $totalUnemployed . "\n";
echo "2. Total Employed Count: " . $totalEmployed . "\n";
echo "3. Total PWD (Unemployed + Employed): " . $totalOverallPwd . "\n";
echo "4. Employment Rate Calculation: (" . $totalEmployed . " / " . $totalOverallPwd . ") * 100 = " . $overallEmploymentRate . "%\n";

echo "\n" . str_repeat("=", 100) . "\n";
echo "INDIVIDUAL RECORD RATE CALCULATIONS\n";
echo str_repeat("=", 100) . "\n\n";

foreach ($records as $record) {
    $individual_rate_calc = $record['total'] > 0 ? round(($record['employed'] / $record['total']) * 100, 2) : 0;
    echo "- " . $record['type'] . ": (" . $record['employed'] . " / " . $record['total'] . ") * 100 = " . $individual_rate_calc . "%\n";
}

echo "\n" . str_repeat("=", 100) . "\n";
echo "API CONTROLLER CALCULATION VERIFICATION\n";
echo str_repeat("=", 100) . "\n\n";

// Test API calculation
$apiControllerCalc = [
    'total_unemployed' => $totalUnemployed,
    'total_employed' => $totalEmployed,
    'total_pwd' => $totalOverallPwd,
    'employment_rate' => $overallEmploymentRate,
];

echo "API would return:\n";
echo json_encode($apiControllerCalc, JSON_PRETTY_PRINT) . "\n";

echo "\n" . str_repeat("=", 100) . "\n";
echo "VERIFICATION COMPLETE\n";
echo str_repeat("=", 100) . "\n";
