<?php
/**
 * Simulated HTTP Test for Announcement Creation
 * This mimics what happens when a user submits the form
 */

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "🧪 HTTP REQUEST SIMULATION TEST\n";
echo "═══════════════════════════════════════════════════════════════\n\n";

// Step 1: Find admin user
$admin = User::where('role', 'admin')->where('is_active', true)->first();
if (!$admin) {
    echo "✗ No active admin user found\n";
    exit(1);
}

echo "STEP 1: Admin Found\n";
echo "─────────────────────────────────\n";
echo "✓ ID: " . $admin->id . "\n";
echo "✓ Name: " . $admin->name . "\n";
echo "✓ Email: " . $admin->email . "\n";

// Step 2: Authenticate
Auth::loginUsingId($admin->id);

echo "\nSTEP 2: Authentication\n";
echo "─────────────────────────────────\n";
echo "✓ Authenticated: " . (Auth::check() ? 'YES' : 'NO') . "\n";
echo "✓ User ID: " . Auth::id() . "\n";
echo "✓ User Role: " . Auth::user()->role . "\n";
echo "✓ Is Admin: " . (Auth::user()->isAdmin() ? 'YES' : 'NO') . "\n";

// Step 3: Create a simulated request
echo "\nSTEP 3: Simulating Form Submission\n";
echo "─────────────────────────────────\n";

$data = [
    'title' => 'Test Announcement ' . date('Y-m-d H:i:s'),
    'content' => 'This is a test announcement created at ' . date('Y-m-d H:i:s'),
    'is_active' => '1',
];

echo "Form Data:\n";
echo "  - Title: " . $data['title'] . "\n";
echo "  - Content: " . substr($data['content'], 0, 50) . "...\n";
echo "  - Is Active: " . ($data['is_active'] ? 'YES' : 'NO') . "\n";

// Step 4: Simulate the request
echo "\nSTEP 4: Testing Route Handler\n";
echo "─────────────────────────────────\n";

try {
    $request = new Request();
    $request->setMethod('POST');
    $request->setRequestUri('/admin/announcements');
    $request->initialize(
        [], // $_GET
        $data, // $_POST
        [], // $_COOKIE
        [], // $_FILES
        [] // $_SERVER
    );

    // Set headers
    $controller = new \App\Http\Controllers\AnnouncementController();

    Log::info('=== SIMULATING ANNOUNCEMENT CREATION REQUEST ===', [
        'admin_id' => Auth::id(),
        'admin_name' => Auth::user()->name,
        'data' => $data,
    ]);

    echo "Calling AnnouncementController@store...\n";
    $response = $controller->store($request);

    echo "✓ Response received\n";
    echo "  - Status Code: " . $response->status() . "\n";
    echo "  - Response Type: " . get_class($response) . "\n";

    if (method_exists($response, 'getTargetUrl')) {
        echo "  - Redirect URL: " . $response->getTargetUrl() . "\n";
    }

    // Check session data
    if ($response instanceof \Illuminate\Http\RedirectResponse) {
        $session = $response->getSession();
        if ($session && $session->has('success')) {
            echo "  - Success Message: " . $session->pull('success') . "\n";
        }
    }

    echo "\n✓ Announcement creation completed successfully!\n";

} catch (\Illuminate\Validation\ValidationException $e) {
    echo "✗ Validation Error\n";
    echo "  Errors: " . json_encode($e->errors()) . "\n";
    exit(1);
} catch (\Throwable $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . "\n";
    echo "  Line: " . $e->getLine() . "\n";
    exit(1);
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "✓ TEST COMPLETE\n";
echo "═══════════════════════════════════════════════════════════════\n\n";
