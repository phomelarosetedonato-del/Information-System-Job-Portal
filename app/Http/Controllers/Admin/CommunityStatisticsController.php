<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityPwdStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommunityStatisticsController extends Controller
{
    /**
     * Show dashboard with year selector
     */
    public function index()
    {
        try {
            $years = CommunityPwdStat::distinct()
                ->pluck('year')
                ->sort()
                ->reverse()
                ->toArray();

            $currentYear = $years[0] ?? 2025;
            $stats = $this->getYearStats($currentYear);

            return view('admin.community-statistics.index', compact('years', 'currentYear', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading community statistics: ' . $e->getMessage());
            return back()->with('error', 'Unable to load community statistics');
        }
    }

    /**
     * Show form to create new year
     */
    public function create()
    {
        try {
            $disabilityTypes = [
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

            $existingYears = CommunityPwdStat::distinct()
                ->pluck('year')
                ->sort()
                ->reverse()
                ->toArray();

            return view('admin.community-statistics.create', compact('disabilityTypes', 'existingYears'));
        } catch (\Exception $e) {
            Log::error('Error loading create form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load create form');
        }
    }

    /**
     * Store new year data
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'year' => 'required|integer|min:1900|max:2100|unique:community_pwd_stats,year',
                'stats' => 'required|array|size:10',
                'stats.*.disability_type' => 'required|string',
                'stats.*.unemployed_count' => 'required|integer|min:0',
                'stats.*.employed_count' => 'required|integer|min:0',
            ], [
                'year.unique' => 'Data for this year already exists. Use Edit to modify it.',
            ]);

            DB::beginTransaction();

            foreach ($validated['stats'] as $stat) {
                CommunityPwdStat::create([
                    'year' => $validated['year'],
                    'disability_type' => $stat['disability_type'],
                    'unemployed_count' => $stat['unemployed_count'],
                    'employed_count' => $stat['employed_count'],
                ]);
            }

            DB::commit();

            Log::info("New year {$validated['year']} created with " . count($validated['stats']) . " records");

            return redirect()->route('admin.community-statistics.index')
                ->with('success', 'Community statistics for ' . $validated['year'] . ' created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating community statistics: ' . $e->getMessage());
            return back()->with('error', 'Unable to create statistics: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show edit form with existing data
     */
    public function edit($year)
    {
        try {
            $stats = CommunityPwdStat::where('year', $year)
                ->orderBy('disability_type')
                ->get();

            if ($stats->isEmpty()) {
                return redirect()->route('admin.community-statistics.index')
                    ->with('error', 'No data found for year ' . $year);
            }

            $totals = $this->calculateTotals($stats);
            $years = CommunityPwdStat::distinct()
                ->pluck('year')
                ->sort()
                ->reverse()
                ->toArray();

            return view('admin.community-statistics.edit', compact('year', 'stats', 'totals', 'years'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return back()->with('error', 'Unable to load edit form');
        }
    }

    /**
     * Save all records for a year
     */
    public function update(Request $request, $year)
    {
        try {
            $validated = $request->validate([
                'stats' => 'required|array',
                'stats.*.disability_type' => 'required|string',
                'stats.*.unemployed_count' => 'required|integer|min:0',
                'stats.*.employed_count' => 'required|integer|min:0',
            ]);

            DB::beginTransaction();

            foreach ($validated['stats'] as $stat) {
                CommunityPwdStat::updateOrCreate(
                    [
                        'year' => $year,
                        'disability_type' => $stat['disability_type'],
                    ],
                    [
                        'unemployed_count' => $stat['unemployed_count'],
                        'employed_count' => $stat['employed_count'],
                    ]
                );
            }

            DB::commit();

            Log::info("Community PWD stats updated for year {$year}");

            return back()->with('success', 'Community statistics updated successfully for ' . $year);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating community statistics: ' . $e->getMessage());
            return back()->with('error', 'Unable to update statistics: ' . $e->getMessage());
        }
    }

    /**
     * Calculate totals for a year
     */
    public function calculateTotals($stats = null)
    {
        if (is_numeric($stats)) {
            // If called via AJAX with year parameter
            $year = $stats;
            $stats = CommunityPwdStat::where('year', $year)->get();
        }

        $totalUnemployed = $stats->sum('unemployed_count');
        $totalEmployed = $stats->sum('employed_count');
        $totalPwd = $totalUnemployed + $totalEmployed;
        $employmentRate = $totalPwd > 0 ? round(($totalEmployed / $totalPwd) * 100, 2) : 0;

        return [
            'total_unemployed' => $totalUnemployed,
            'total_employed' => $totalEmployed,
            'total_pwd' => $totalPwd,
            'employment_rate' => $employmentRate,
        ];
    }

    /**
     * Get list of years with data
     */
    public function getYearList()
    {
        return CommunityPwdStat::distinct()
            ->pluck('year')
            ->sort()
            ->reverse()
            ->toArray();
    }

    /**
     * Get all stats for a specific year
     */
    private function getYearStats($year)
    {
        return CommunityPwdStat::where('year', $year)
            ->orderBy('disability_type')
            ->get();
    }

    /**
     * Export to CSV
     */
    public function exportCSV($year)
    {
        try {
            $stats = CommunityPwdStat::where('year', $year)
                ->orderBy('disability_type')
                ->get();

            if ($stats->isEmpty()) {
                return back()->with('error', 'No data found for year ' . $year);
            }

            $csv = "Disability Type,Unemployed,Employed\n";
            foreach ($stats as $stat) {
                $csv .= "\"{$stat->disability_type}\",{$stat->unemployed_count},{$stat->employed_count}\n";
            }

            $fileName = "community-pwd-stats-{$year}.csv";

            return response($csv, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
            ]);
        } catch (\Exception $e) {
            Log::error('Error exporting CSV: ' . $e->getMessage());
            return back()->with('error', 'Unable to export data');
        }
    }

    /**
     * Import from CSV
     */
    public function importCSV(Request $request, $year)
    {
        try {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:1024',
            ]);

            $file = $request->file('csv_file');
            $contents = file_get_contents($file->getRealPath());
            $lines = array_filter(explode("\n", $contents));

            DB::beginTransaction();

            $recordCount = 0;
            foreach ($lines as $index => $line) {
                if ($index === 0) continue; // Skip header

                $parts = str_getcsv($line);
                if (count($parts) < 3) continue;

                $disabilityType = trim($parts[0]);
                $unemployedCount = (int)$parts[1];
                $employedCount = (int)$parts[2];

                CommunityPwdStat::updateOrCreate(
                    [
                        'year' => $year,
                        'disability_type' => $disabilityType,
                    ],
                    [
                        'unemployed_count' => $unemployedCount,
                        'employed_count' => $employedCount,
                    ]
                );

                $recordCount++;
            }

            DB::commit();

            Log::info("Imported {$recordCount} community PWD stats records for year {$year}");

            return back()->with('success', "Successfully imported {$recordCount} records for {$year}");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error importing CSV: ' . $e->getMessage());
            return back()->with('error', 'Unable to import data: ' . $e->getMessage());
        }
    }

    /**
     * Delete entire year
     */
    public function destroy($year)
    {
        try {
            $count = CommunityPwdStat::where('year', $year)->count();

            if ($count === 0) {
                return back()->with('error', 'No data found for year ' . $year);
            }

            DB::beginTransaction();

            CommunityPwdStat::where('year', $year)->delete();

            DB::commit();

            Log::info("Community PWD statistics for year {$year} deleted ({$count} records)");

            return redirect()->route('admin.community-statistics.index')
                ->with('success', "Successfully deleted all statistics for {$year} ({$count} records)");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting community statistics: ' . $e->getMessage());
            return back()->with('error', 'Unable to delete statistics: ' . $e->getMessage());
        }
    }
}
