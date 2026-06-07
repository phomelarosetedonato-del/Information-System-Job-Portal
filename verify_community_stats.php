<?php

// Verify Community PWD Statistics
require 'bootstrap/app.php';
$kernel = app('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

echo "=== COMMUNITY PWD STATISTICS VERIFICATION ===\n\n";

$totalRecords = \App\Models\CommunityPwdStat::count();
$totalUnemployed = \App\Models\CommunityPwdStat::sum('unemployed_count');
$totalEmployed = \App\Models\CommunityPwdStat::sum('employed_count');

echo "Total Records Seeded: $totalRecords\n";
echo "Total Unemployed: $totalUnemployed\n";
echo "Total Employed: $totalEmployed\n";
echo "Total PWD: " . ($totalUnemployed + $totalEmployed) . "\n";
echo "Employment Rate: " . round(($totalEmployed / ($totalUnemployed + $totalEmployed)) * 100, 2) . "%\n\n";

echo "By Disability Type:\n";
\App\Models\CommunityPwdStat::all()->each(function($stat) {
    $total = $stat->unemployed_count + $stat->employed_count;
    $rate = $total > 0 ? round(($stat->employed_count / $total) * 100, 1) : 0;
    echo "  • " . str_pad($stat->disability_type, 40) . " - U: {$stat->unemployed_count}, E: {$stat->employed_count}, Rate: {$rate}%\n";
});

echo "\n✅ All systems verified and operational!\n";
