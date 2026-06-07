<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class QualificationController extends Controller
{
    /**
     * Show qualified applicants report
     */
    public function index()
    {
        try {
            $qualifiedApplicants = User::where('role', 'pwd')
                ->where('is_qualified', true)
                ->with('pwdProfile')
                ->paginate(15);

            // Statistics
            $stats = [
                'total_qualified' => User::where('role', 'pwd')->where('is_qualified', true)->count(),
                'available_for_jobs' => User::where('role', 'pwd')->where('is_qualified', true)->where('available_for_jobs', true)->count(),
                'average_score' => User::where('role', 'pwd')->where('is_qualified', true)->avg('qualification_score'),
                'not_available' => User::where('role', 'pwd')->where('is_qualified', true)->where('available_for_jobs', false)->count(),
            ];

            return view('admin.qualifications.index', compact('qualifiedApplicants', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error loading qualified applicants: ' . $e->getMessage());
            return back()->with('error', 'Unable to load qualified applicants');
        }
    }

    /**
     * Mark applicant as qualified
     */
    public function markQualified(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'qualification_score' => 'required|numeric|min:0|max:100',
                'available_for_jobs' => 'required|boolean',
            ]);

            $user->update([
                'is_qualified' => true,
                'qualification_score' => $validated['qualification_score'],
                'qualified_at' => now(),
                'available_for_jobs' => $validated['available_for_jobs'],
            ]);

            return back()->with('success', "{$user->name} has been marked as qualified with a score of {$validated['qualification_score']}/100");
        } catch (\Exception $e) {
            Log::error('Error marking applicant as qualified: ' . $e->getMessage());
            return back()->with('error', 'Unable to mark applicant as qualified');
        }
    }

    /**
     * Update qualification status
     */
    public function updateQualification(Request $request, User $user)
    {
        try {
            $validated = $request->validate([
                'qualification_score' => 'required|numeric|min:0|max:100',
                'available_for_jobs' => 'required|boolean',
            ]);

            $user->update([
                'qualification_score' => $validated['qualification_score'],
                'available_for_jobs' => $validated['available_for_jobs'],
            ]);

            return back()->with('success', "{$user->name}'s qualification has been updated");
        } catch (\Exception $e) {
            Log::error('Error updating qualification: ' . $e->getMessage());
            return back()->with('error', 'Unable to update qualification');
        }
    }

    /**
     * Remove qualification
     */
    public function removeQualification(User $user)
    {
        try {
            $user->update([
                'is_qualified' => false,
                'qualification_score' => null,
                'qualified_at' => null,
                'available_for_jobs' => false,
            ]);

            return back()->with('success', "{$user->name} has been removed from qualified applicants");
        } catch (\Exception $e) {
            Log::error('Error removing qualification: ' . $e->getMessage());
            return back()->with('error', 'Unable to remove qualification');
        }
    }

    /**
     * Filter by availability
     */
    public function filterByAvailability($availability = 'all')
    {
        try {
            $query = User::where('role', 'pwd')->where('is_qualified', true);

            if ($availability === 'available') {
                $query->where('available_for_jobs', true);
            } elseif ($availability === 'unavailable') {
                $query->where('available_for_jobs', false);
            }

            $qualifiedApplicants = $query->with('pwdProfile')->paginate(15);

            // Statistics
            $stats = [
                'total_qualified' => User::where('role', 'pwd')->where('is_qualified', true)->count(),
                'available_for_jobs' => User::where('role', 'pwd')->where('is_qualified', true)->where('available_for_jobs', true)->count(),
                'average_score' => User::where('role', 'pwd')->where('is_qualified', true)->avg('qualification_score'),
                'not_available' => User::where('role', 'pwd')->where('is_qualified', true)->where('available_for_jobs', false)->count(),
            ];

            return view('admin.qualifications.index', compact('qualifiedApplicants', 'stats', 'availability'));
        } catch (\Exception $e) {
            Log::error('Error filtering qualified applicants: ' . $e->getMessage());
            return back()->with('error', 'Unable to filter applicants');
        }
    }

    /**
     * Export to PDF
     */
    public function exportPDF()
    {
        try {
            $qualifiedApplicants = User::where('role', 'pwd')
                ->where('is_qualified', true)
                ->with('pwdProfile')
                ->get();

            // Generate HTML content
            $html = $this->generatePDFContent($qualifiedApplicants);

            // Create PDF using DOMPDF
            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4')
                ->setOption('isHtml5ParserEnabled', true)
                ->setOption('isPhpEnabled', false);

            // Generate filename
            $fileName = 'qualified-applicants-' . date('Y-m-d-His') . '.pdf';

            Log::info('PDF export generated: ' . $fileName);

            // Return PDF download
            return $pdf->download($fileName);
        } catch (\Exception $e) {
            Log::error('Error exporting PDF: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Unable to export PDF: ' . $e->getMessage());
        }
    }

    /**
     * Export to Excel
     */
    public function exportExcel()
    {
        try {
            $qualifiedApplicants = User::where('role', 'pwd')
                ->where('is_qualified', true)
                ->with('pwdProfile')
                ->get();

            // Generate CSV content
            $csv = $this->generateCSVContent($qualifiedApplicants);

            // Create temp directory if it doesn't exist
            $tempDir = storage_path('app' . DIRECTORY_SEPARATOR . 'temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Create file with proper path handling
            $fileName = 'qualified-applicants-' . date('Y-m-d-His') . '.csv';
            $filePath = $tempDir . DIRECTORY_SEPARATOR . $fileName;

            // Write CSV to file
            $bytesWritten = file_put_contents($filePath, $csv);

            if ($bytesWritten === false) {
                throw new \Exception('Failed to write CSV file to disk');
            }

            // Verify file exists before download
            if (!file_exists($filePath)) {
                throw new \Exception('CSV file was not created successfully');
            }

            Log::info('Excel export file created: ' . $filePath);

            // Return download with proper headers
            return response()->download($filePath, $fileName, [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                'Pragma' => 'public',
                'Cache-Control' => 'public, must-revalidate'
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Error exporting Excel: ' . $e->getMessage() . ' | Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Unable to export Excel: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF content as HTML
     */
    private function generatePDFContent($qualifiedApplicants)
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Qualified Applicants Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #2c3e50; border-bottom: 3px solid #27ae60; padding-bottom: 10px; }
        .report-meta { background: #ecf0f1; padding: 10px; margin: 20px 0; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background: #34495e; color: white; padding: 12px; text-align: left; }
        td { padding: 10px; border-bottom: 1px solid #bdc3c7; }
        tr:nth-child(even) { background: #f8f9fa; }
        .badge { display: inline-block; padding: 4px 8px; border-radius: 3px; font-size: 0.85em; }
        .badge-success { background: #27ae60; color: white; }
        .badge-warning { background: #f39c12; color: white; }
        .score { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Qualified Applicants Report</h1>
    <div class="report-meta">
        <p><strong>Report Generated:</strong> ' . now()->format('F d, Y H:i:s') . '</p>
        <p><strong>Total Qualified Applicants:</strong> ' . $qualifiedApplicants->count() . '</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Disability Type</th>
                <th>Score</th>
                <th>Qualified Date</th>
                <th>Available</th>
            </tr>
        </thead>
        <tbody>';

        foreach ($qualifiedApplicants as $applicant) {
            $availability = $applicant->available_for_jobs ? '<span class="badge badge-success">Yes</span>' : '<span class="badge badge-warning">No</span>';
            $disabilityType = $applicant->pwdProfile ? ($applicant->pwdProfile->disability_type ?? 'N/A') : 'N/A';

            $html .= '<tr>
                <td>' . htmlspecialchars($applicant->name) . '</td>
                <td>' . htmlspecialchars($applicant->email) . '</td>
                <td>' . htmlspecialchars($disabilityType) . '</td>
                <td class="score">' . number_format($applicant->qualification_score, 2) . '/100</td>
                <td>' . ($applicant->qualified_at ? $applicant->qualified_at->format('M d, Y') : 'N/A') . '</td>
                <td>' . $availability . '</td>
            </tr>';
        }

        $html .= '</tbody>
    </table>
</body>
</html>';

        return $html;
    }

    /**
     * Generate CSV content for Excel export
     */
    private function generateCSVContent($qualifiedApplicants)
    {
        $csv = "Name,Email,Disability Type,Qualification Score,Qualified Date,Available for Jobs\n";

        foreach ($qualifiedApplicants as $applicant) {
            $disabilityType = $applicant->pwdProfile ? ($applicant->pwdProfile->disability_type ?? 'N/A') : 'N/A';
            $available = $applicant->available_for_jobs ? 'Yes' : 'No';
            $qualifiedDate = $applicant->qualified_at ? $applicant->qualified_at->format('M d, Y') : 'N/A';

            $csv .= '"' . str_replace('"', '""', $applicant->name) . '",'
                  . '"' . str_replace('"', '""', $applicant->email) . '",'
                  . '"' . str_replace('"', '""', $disabilityType) . '",'
                  . number_format($applicant->qualification_score, 2) . ','
                  . $qualifiedDate . ','
                  . $available . "\n";
        }

        return $csv;
    }
}

