<?php
/**
 * Update Test User Email Address
 */

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "\n";
echo "═══════════════════════════════════════════════════════\n";
echo "Updating Test User Email Address\n";
echo "═══════════════════════════════════════════════════════\n\n";

// Find the test user
$testUser = User::where('name', 'Test User')->where('email', 'test@example.com')->first();

if (!$testUser) {
    echo "✗ Test user not found\n";
    exit(1);
}

echo "Found Test User:\n";
echo "  ID: " . $testUser->id . "\n";
echo "  Name: " . $testUser->name . "\n";
echo "  Current Email: " . $testUser->email . "\n\n";

// Use the email configured in MAIL_FROM_ADDRESS
// Since that email is already used, we'll create a variation
$newEmail = 'testuser@gmail.com';

echo "Updating to: " . $newEmail . "\n";

try {
    $testUser->update(['email' => $newEmail]);
    echo "✓ Email updated successfully\n\n";

    echo "Updated User:\n";
    echo "  ID: " . $testUser->id . "\n";
    echo "  Name: " . $testUser->name . "\n";
    echo "  New Email: " . $testUser->email . "\n\n";

    // Verify in database
    $verified = User::find($testUser->id);
    echo "Verification from database:\n";
    echo "  Email: " . $verified->email . "\n";

} catch (\Throwable $e) {
    echo "✗ Error updating email: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n";
echo "═══════════════════════════════════════════════════════\n";
echo "✓ Test user email has been updated\n";
echo "═══════════════════════════════════════════════════════\n\n";
