<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CommunityPwdStat;
use Illuminate\Http\Request;

class CommunityStatisticsApiController extends Controller
{
    /**
     * Get stats for a specific year
     */
    public function getStats($year)
    {
        try {
            $stats = CommunityPwdStat::where('year', $year)
                ->orderBy('disability_type')
                ->get();

            if ($stats->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No data found'], 404);
            }

            // Calculate totals
            $totalUnemployed = $stats->sum('unemployed_count');
            $totalEmployed = $stats->sum('employed_count');
            $totalPwd = $totalUnemployed + $totalEmployed;
            $employmentRate = $totalPwd > 0 ? round(($totalEmployed / $totalPwd) * 100, 2) : 0;

            return response()->json([
                'success' => true,
                'stats' => $stats,
                'totals' => [
                    'total_unemployed' => $totalUnemployed,
                    'total_employed' => $totalEmployed,
                    'total_pwd' => $totalPwd,
                    'employment_rate' => $employmentRate,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error loading stats'], 500);
        }
    }
}
