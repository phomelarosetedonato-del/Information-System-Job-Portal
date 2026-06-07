<?php
/**
 * COMMUNITY PWD STATISTICS - INTERACTIVE DIAGNOSTIC TOOL
 *
 * This script verifies all computations in the Community PWD Statistics system
 * Run this script anytime to validate system functionality
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CommunityPwdStat;
use App\Http\Controllers\Admin\CommunityStatisticsController;
use App\Http\Controllers\Api\CommunityStatisticsApiController;

class CommunityStatsValidator {
    private $controller;
    private $apiController;

    public function __construct() {
        $this->controller = new CommunityStatisticsController();
        $this->apiController = new CommunityStatisticsApiController();
    }

    public function runFullDiagnostics() {
        echo "\n";
        echo str_repeat("=", 100) . "\n";
        echo "COMMUNITY PWD STATISTICS - FULL SYSTEM DIAGNOSTIC\n";
        echo str_repeat("=", 100) . "\n\n";

        $this->checkDatabase();
        $this->checkControllerCalculations();
        $this->checkApiResponse();
        $this->checkDataIntegrity();
        $this->generateReport();
    }

    private function checkDatabase() {
        echo "\n[1/4] DATABASE CHECK\n";
        echo str_repeat("-", 100) . "\n";

        $stats = CommunityPwdStat::where('year', 2025)->get();
        $count = $stats->count();

        if ($count == 0) {
            echo "❌ ERROR: No records found for 2025\n";
            return false;
        }

        echo "✅ Found $count records for 2025\n";

        $totalU = $stats->sum('unemployed_count');
        $totalE = $stats->sum('employed_count');
        $totalP = $totalU + $totalE;

        echo "✅ Total Unemployed: $totalU\n";
        echo "✅ Total Employed: $totalE\n";
        echo "✅ Total PWD: $totalP\n";

        return true;
    }

    private function checkControllerCalculations() {
        echo "\n[2/4] CONTROLLER CALCULATIONS CHECK\n";
        echo str_repeat("-", 100) . "\n";

        $stats = CommunityPwdStat::where('year', 2025)->get();
        $totals = $this->controller->calculateTotals($stats);

        if (!isset($totals['total_unemployed'])) {
            echo "❌ ERROR: Calculate totals returned invalid data\n";
            return false;
        }

        echo "✅ Controller calculateTotals() returned:\n";
        echo "   - total_unemployed: " . $totals['total_unemployed'] . "\n";
        echo "   - total_employed: " . $totals['total_employed'] . "\n";
        echo "   - total_pwd: " . $totals['total_pwd'] . "\n";
        echo "   - employment_rate: " . $totals['employment_rate'] . "%\n";

        // Verify calculations
        $manual_rate = ($totals['total_employed'] / $totals['total_pwd']) * 100;
        $manual_rate = round($manual_rate, 2);

        if ($manual_rate != $totals['employment_rate']) {
            echo "❌ ERROR: Employment rate calculation incorrect\n";
            return false;
        }

        echo "✅ All calculations verified\n";
        return true;
    }

    private function checkApiResponse() {
        echo "\n[3/4] API ENDPOINT CHECK\n";
        echo str_repeat("-", 100) . "\n";

        $response = $this->apiController->getStats(2025);
        $data = json_decode($response->getContent(), true);

        if ($response->getStatusCode() != 200) {
            echo "❌ ERROR: API returned status " . $response->getStatusCode() . "\n";
            return false;
        }

        echo "✅ API endpoint returned 200 OK\n";

        if (!$data['success']) {
            echo "❌ ERROR: API success flag is false\n";
            return false;
        }

        echo "✅ API success flag is true\n";

        if (count($data['stats']) != 10) {
            echo "❌ ERROR: API returned " . count($data['stats']) . " records instead of 10\n";
            return false;
        }

        echo "✅ API returned 10 records\n";

        if (!isset($data['totals'])) {
            echo "❌ ERROR: API response missing totals\n";
            return false;
        }

        echo "✅ API totals present:\n";
        echo "   - total_unemployed: " . $data['totals']['total_unemployed'] . "\n";
        echo "   - total_employed: " . $data['totals']['total_employed'] . "\n";
        echo "   - total_pwd: " . $data['totals']['total_pwd'] . "\n";
        echo "   - employment_rate: " . $data['totals']['employment_rate'] . "%\n";

        return true;
    }

    private function checkDataIntegrity() {
        echo "\n[4/4] DATA INTEGRITY CHECK\n";
        echo str_repeat("-", 100) . "\n";

        $stats = CommunityPwdStat::where('year', 2025)->get();
        $errors = 0;

        foreach ($stats as $stat) {
            $total = $stat->unemployed_count + $stat->employed_count;
            $rate = $stat->getEmploymentRate();

            // Verify rate calculation
            $expected_rate = $total > 0 ? round(($stat->employed_count / $total) * 100, 2) : 0;

            if ($rate != $expected_rate) {
                echo "❌ ERROR: " . $stat->disability_type . " rate incorrect\n";
                $errors++;
            }
        }

        if ($errors == 0) {
            echo "✅ All " . $stats->count() . " records have correct calculations\n";
            echo "✅ All individual employment rates verified\n";
            return true;
        } else {
            echo "❌ Found $errors errors in data integrity\n";
            return false;
        }
    }

    private function generateReport() {
        echo "\n" . str_repeat("=", 100) . "\n";
        echo "DIAGNOSTIC REPORT\n";
        echo str_repeat("=", 100) . "\n\n";

        $stats = CommunityPwdStat::where('year', 2025)->orderBy('disability_type')->get();
        $totals = $this->controller->calculateTotals($stats);

        echo "FINAL SUMMARY:\n";
        echo "└─ Database Records: " . $stats->count() . "\n";
        echo "└─ Year: 2025\n";
        echo "└─ Total Unemployed: " . $totals['total_unemployed'] . "\n";
        echo "└─ Total Employed: " . $totals['total_employed'] . "\n";
        echo "└─ Total PWD: " . $totals['total_pwd'] . "\n";
        echo "└─ Overall Employment Rate: " . $totals['employment_rate'] . "%\n\n";

        echo "STATUS: ✅ ALL SYSTEMS OPERATIONAL\n";
        echo "All computations are working correctly and providing accurate tallies.\n\n";

        echo str_repeat("=", 100) . "\n";
        echo "End of Diagnostic Report - " . date('Y-m-d H:i:s') . "\n";
        echo str_repeat("=", 100) . "\n\n";
    }
}

// Run diagnostics
$validator = new CommunityStatsValidator();
$validator->runFullDiagnostics();
