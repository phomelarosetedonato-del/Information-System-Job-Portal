<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\DB;

echo "=== Database Investigation ===\n\n";

// Check announcements
$announcementCount = DB::table('announcements')->count();
echo "Announcements created: $announcementCount\n";

if ($announcementCount > 0) {
    $announcements = DB::table('announcements')->latest()->limit(3)->get();
    echo "\nRecent announcements:\n";
    foreach ($announcements as $ann) {
        echo "  - ID: {$ann->id}, Title: {$ann->title}, Active: {$ann->is_active}, Created: {$ann->created_at}\n";
    }
}

// Check notifications
$notificationCount = DB::table('notifications')->count();
echo "\n\nNotifications in database: $notificationCount\n";

if ($notificationCount > 0) {
    $notifications = DB::table('notifications')->latest()->limit(5)->get();
    echo "\nRecent notifications:\n";
    foreach ($notifications as $notif) {
        echo "  - To user: {$notif->notifiable_id}, Type: {$notif->type}, Read: {$notif->read_at}\n";
    }
}

// Check PWD users
$pwdUsersCount = DB::table('users')->where('role', 'pwd')->where('is_active', true)->count();
echo "\n\nActive PWD users: $pwdUsersCount\n";

// Check jobs table
$jobsCount = DB::table('jobs')->count();
echo "\nQueued jobs: $jobsCount\n";

if ($jobsCount > 0) {
    $jobs = DB::table('jobs')->latest()->limit(3)->get();
    echo "\nRecent jobs:\n";
    foreach ($jobs as $job) {
        echo "  - Queue: {$job->queue}, Attempts: {$job->attempts}, Created: {$job->created_at}\n";
    }
}

// Check failed_jobs
$failedJobsCount = DB::table('failed_jobs')->count();
echo "\n\nFailed jobs: $failedJobsCount\n";

if ($failedJobsCount > 0) {
    $failedJobs = DB::table('failed_jobs')->latest()->limit(3)->get();
    echo "\nRecent failed jobs:\n";
    foreach ($failedJobs as $job) {
        echo "  - UUID: {$job->uuid}, Exception: " . substr($job->exception, 0, 100) . "...\n";
    }
}

echo "\n";
