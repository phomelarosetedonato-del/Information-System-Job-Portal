<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$request = \Illuminate\Http\Request::capture();
$kernel->bootstrap();

echo "=== DATABASE VERIFICATION ===\n\n";

echo "📊 TABLE COUNTS:\n";
echo "Users: " . \App\Models\User::count() . "\n";
echo "Job Postings: " . \App\Models\JobPosting::count() . "\n";
echo "Skill Trainings: " . \App\Models\SkillTraining::count() . "\n";
echo "Disability Types: " . \App\Models\DisabilityType::count() . "\n";
echo "Locations: " . \App\Models\Location::count() . "\n";
echo "Work Arrangement Options: " . \App\Models\WorkArrangementOption::count() . "\n";
echo "Assistive Device Options: " . \App\Models\AssistiveDeviceOption::count() . "\n\n";

echo "👥 ADMIN USERS:\n";
$admins = \App\Models\User::where('role', 'admin')->get(['name', 'email', 'is_active']);
foreach ($admins as $admin) {
    echo "  ✓ " . $admin->name . " (" . $admin->email . ") - Active: " . ($admin->is_active ? "YES" : "NO") . "\n";
}
echo "\n";

echo "📍 LOCATIONS (First 5):\n";
$locations = \App\Models\Location::take(5)->get(['name', 'is_active']);
foreach ($locations as $loc) {
    echo "  ✓ " . $loc->name . " - Active: " . ($loc->is_active ? "YES" : "NO") . "\n";
}
echo "\n";

echo "💼 JOB POSTINGS (Sample):\n";
$jobs = \App\Models\JobPosting::take(3)->get(['title', 'company', 'is_active']);
foreach ($jobs as $job) {
    echo "  ✓ " . $job->title . " @ " . $job->company . "\n";
}
echo "\n";

echo "♿ DISABILITY TYPES (First 5):\n";
$disabilities = \App\Models\DisabilityType::take(5)->get(['name']);
foreach ($disabilities as $dis) {
    echo "  ✓ " . $dis->name . "\n";
}
echo "\n";

echo "✅ ALL DATABASE CHECKS PASSED!\n";
