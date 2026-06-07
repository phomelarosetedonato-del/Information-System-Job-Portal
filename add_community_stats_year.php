<?php
/**
 * COMMUNITY PWD STATISTICS - INTERACTIVE YEAR ADDITION TOOL
 *
 * This script helps you add a new year to the Community PWD Statistics system
 * Run: php add_community_stats_year.php
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CommunityPwdStat;

class CommunityStatsYearAdder {

    public function run() {
        echo "\n";
        echo str_repeat("=", 100) . "\n";
        echo "COMMUNITY PWD STATISTICS - YEAR ADDITION TOOL\n";
        echo str_repeat("=", 100) . "\n\n";

        $this->showMenu();
    }

    private function showMenu() {
        echo "Choose an option:\n";
        echo "1. Clone data from existing year (EASIEST)\n";
        echo "2. Add new year with custom data\n";
        echo "3. View existing years and their record counts\n";
        echo "4. Exit\n\n";

        echo "Enter your choice (1-4): ";
        $choice = trim(fgets(STDIN));

        switch ($choice) {
            case '1':
                $this->cloneYear();
                break;
            case '2':
                $this->addCustomYear();
                break;
            case '3':
                $this->viewYears();
                break;
            case '4':
                echo "Goodbye!\n\n";
                exit;
            default:
                echo "Invalid choice. Please try again.\n\n";
                $this->showMenu();
        }
    }

    private function cloneYear() {
        echo "\n" . str_repeat("-", 100) . "\n";
        echo "CLONE EXISTING YEAR DATA\n";
        echo str_repeat("-", 100) . "\n\n";

        // Show available years
        $years = CommunityPwdStat::distinct()
            ->pluck('year')
            ->sort()
            ->reverse()
            ->toArray();

        if (empty($years)) {
            echo "❌ No data found in database\n";
            return;
        }

        echo "Available years: " . implode(", ", $years) . "\n\n";

        echo "Enter source year to clone from: ";
        $sourceYear = (int) trim(fgets(STDIN));

        echo "Enter destination year: ";
        $destYear = (int) trim(fgets(STDIN));

        if ($sourceYear == $destYear) {
            echo "❌ Source and destination years must be different\n";
            return;
        }

        // Check if source year exists
        $sourceRecords = CommunityPwdStat::where('year', $sourceYear)->get();
        if ($sourceRecords->isEmpty()) {
            echo "❌ No records found for year $sourceYear\n";
            return;
        }

        // Check if destination already exists
        $existingCount = CommunityPwdStat::where('year', $destYear)->count();
        if ($existingCount > 0) {
            echo "\n⚠️  Year $destYear already has $existingCount records\n";
            echo "Overwrite? (y/n): ";
            $overwrite = strtolower(trim(fgets(STDIN)));
            if ($overwrite !== 'y') {
                echo "Cancelled.\n";
                return;
            }
        }

        // Clone data
        $cloned = 0;
        foreach ($sourceRecords as $record) {
            CommunityPwdStat::updateOrCreate(
                [
                    'year' => $destYear,
                    'disability_type' => $record->disability_type,
                ],
                [
                    'unemployed_count' => $record->unemployed_count,
                    'employed_count' => $record->employed_count,
                ]
            );
            $cloned++;
        }

        echo "\n✅ Successfully cloned $cloned records from $sourceYear to $destYear\n";

        // Verify
        $verification = CommunityPwdStat::where('year', $destYear)->count();
        echo "✅ Verification: $verification records found for $destYear\n";

        // Show totals
        $destData = CommunityPwdStat::where('year', $destYear)->get();
        $totalU = $destData->sum('unemployed_count');
        $totalE = $destData->sum('employed_count');
        $totalP = $totalU + $totalE;
        $rate = $totalP > 0 ? round(($totalE / $totalP) * 100, 2) : 0;

        echo "\n📊 $destYear Statistics:\n";
        echo "   - Total Unemployed: $totalU\n";
        echo "   - Total Employed: $totalE\n";
        echo "   - Total PWD: $totalP\n";
        echo "   - Employment Rate: $rate%\n";

        echo "\n" . str_repeat("-", 100) . "\n";
        $this->returnToMenu();
    }

    private function addCustomYear() {
        echo "\n" . str_repeat("-", 100) . "\n";
        echo "ADD NEW YEAR WITH CUSTOM DATA\n";
        echo str_repeat("-", 100) . "\n\n";

        echo "Enter year: ";
        $year = (int) trim(fgets(STDIN));

        // Verify year format
        if ($year < 1900 || $year > 2100) {
            echo "❌ Invalid year\n";
            return;
        }

        // Check if year exists
        $existingCount = CommunityPwdStat::where('year', $year)->count();
        if ($existingCount > 0) {
            echo "\n⚠️  Year $year already has $existingCount records\n";
            echo "Overwrite? (y/n): ";
            $overwrite = strtolower(trim(fgets(STDIN)));
            if ($overwrite !== 'y') {
                echo "Cancelled.\n";
                return;
            }
        }

        $disabilities = [
            'Deaf or Hard of Hearing',
            'Intellectual Disability',
            'Learning Disability',
            'Mental Disability',
            'Physical Disability (Orthopedic)',
            'Psychosocial Disability',
            'Speech and Language Impairment',
            'Visual Disability',
            'Cancer (RA11215)',
            'Rare Disease (RA 11215)',
        ];

        echo "\nEnter data for $year (or press Enter to skip a disability type):\n\n";

        $addedCount = 0;
        $totalU = 0;
        $totalE = 0;

        foreach ($disabilities as $index => $disability) {
            echo "[$index] $disability\n";

            echo "  Unemployed count (0-1000): ";
            $unemployed = (int) trim(fgets(STDIN));

            echo "  Employed count (0-1000): ";
            $employed = (int) trim(fgets(STDIN));

            if ($unemployed >= 0 && $employed >= 0) {
                CommunityPwdStat::updateOrCreate(
                    ['year' => $year, 'disability_type' => $disability],
                    ['unemployed_count' => $unemployed, 'employed_count' => $employed]
                );
                $addedCount++;
                $totalU += $unemployed;
                $totalE += $employed;
                echo "  ✅ Added\n\n";
            } else {
                echo "  ⚠️  Skipped (invalid input)\n\n";
            }
        }

        if ($addedCount == 0) {
            echo "❌ No records added\n";
            return;
        }

        $totalP = $totalU + $totalE;
        $rate = $totalP > 0 ? round(($totalE / $totalP) * 100, 2) : 0;

        echo str_repeat("-", 100) . "\n";
        echo "✅ Successfully added $addedCount records for $year\n\n";
        echo "📊 $year Statistics:\n";
        echo "   - Total Unemployed: $totalU\n";
        echo "   - Total Employed: $totalE\n";
        echo "   - Total PWD: $totalP\n";
        echo "   - Employment Rate: $rate%\n";

        echo "\n" . str_repeat("-", 100) . "\n";
        $this->returnToMenu();
    }

    private function viewYears() {
        echo "\n" . str_repeat("-", 100) . "\n";
        echo "EXISTING YEARS IN DATABASE\n";
        echo str_repeat("-", 100) . "\n\n";

        $years = CommunityPwdStat::distinct()
            ->pluck('year')
            ->sort()
            ->reverse();

        if ($years->isEmpty()) {
            echo "❌ No years found\n";
        } else {
            foreach ($years as $year) {
                $count = CommunityPwdStat::where('year', $year)->count();
                $stats = CommunityPwdStat::where('year', $year)->get();
                $totalU = $stats->sum('unemployed_count');
                $totalE = $stats->sum('employed_count');
                $totalP = $totalU + $totalE;
                $rate = $totalP > 0 ? round(($totalE / $totalP) * 100, 2) : 0;

                echo "📊 Year $year:\n";
                echo "   Records: $count\n";
                echo "   Unemployed: $totalU\n";
                echo "   Employed: $totalE\n";
                echo "   Total PWD: $totalP\n";
                echo "   Employment Rate: $rate%\n";
                echo "   URL: /admin/community-statistics/$year/edit\n\n";
            }
        }

        echo str_repeat("-", 100) . "\n";
        $this->returnToMenu();
    }

    private function returnToMenu() {
        echo "\nPress Enter to return to menu...";
        fgets(STDIN);
        echo "\n";
        $this->showMenu();
    }
}

// Run the tool
$tool = new CommunityStatsYearAdder();
$tool->run();
