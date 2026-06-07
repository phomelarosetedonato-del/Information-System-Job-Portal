<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;
use App\Models\Announcement;

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ANNOUNCEMENTS ===\n";
$count = Announcement::count();
echo "Total Announcements: $count\n\n";

if ($count > 0) {
    echo "Recent Announcements:\n";
    Announcement::latest()->limit(5)->get()->each(function($a) {
        echo "ID: {$a->id} | Title: {$a->title} | Active: {$a->is_active} | Created: {$a->created_at}\n";
    });
}

echo "\n=== NOTIFICATIONS ===\n";
$notifications = DB::table('notifications')->count();
echo "Total Notifications: $notifications\n\n";

$types = DB::table('notifications')->select('type')->distinct()->pluck('type')->toArray();
if (!empty($types)) {
    echo "Notification Types:\n";
    foreach ($types as $type) {
        $count = DB::table('notifications')->where('type', $type)->count();
        echo "Type: $type | Count: $count\n";
    }
}

echo "\n=== QUEUE/JOBS ===\n";
$pendingJobs = DB::table('jobs')->count();
$failedJobs = DB::table('failed_jobs')->count();
echo "Pending Jobs: $pendingJobs\n";
echo "Failed Jobs: $failedJobs\n";

echo "\n=== CONFIGURATION ===\n";
echo "Queue Connection: " . config('queue.default') . "\n";
echo "Queue Driver: " . config('queue.connections.' . config('queue.default') . '.driver') . "\n";
