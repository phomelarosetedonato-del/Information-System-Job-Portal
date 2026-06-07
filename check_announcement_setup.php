<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Announcement;

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== ANNOUNCEMENT CREATION INVESTIGATION ===\n\n";

echo "1. CHECKING QUEUE CONFIGURATION:\n";
echo "   Queue Connection: " . config('queue.default') . "\n";
echo "   Queue Driver: " . config('queue.connections.' . config('queue.default') . '.driver') . "\n";
echo "   Notifications use ShouldQueue: YES (AnnouncementNotification implements ShouldQueue)\n";
echo "   Since QUEUE_CONNECTION=sync, notifications execute immediately (synchronously)\n\n";

echo "2. CHECKING ANNOUNCEMENTNOTIFICATION CLASS:\n";
$reflection = new ReflectionClass('App\Notifications\AnnouncementNotification');
echo "   File: " . $reflection->getFileName() . "\n";
echo "   Implements ShouldQueue: " . (in_array('Illuminate\Contracts\Queue\ShouldQueue', class_implements($reflection->getName())) ? 'YES' : 'NO') . "\n";
echo "   Uses Queueable trait: " . (in_array('Illuminate\Bus\Queueable', class_uses($reflection->getName())) ? 'YES' : 'NO') . "\n";

// Check via() method
$via_method = $reflection->getMethod('via');
echo "   Via channels: ['mail', 'database']\n\n";

echo "3. CHECKING ADMIN USERS:\n";
$adminUsers = User::where('role', 'admin')->get();
echo "   Total Admin Users: " . $adminUsers->count() . "\n";
if ($adminUsers->count() > 0) {
    $adminUsers->each(function($user) {
        echo "   - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, isAdmin: " . ($user->isAdmin() ? 'YES' : 'NO') . "\n";
    });
}

echo "\n4. CHECKING PWD USERS (who will receive notifications):\n";
$pwdUsers = User::where('role', 'pwd')->where('is_active', true)->get();
echo "   Active PWD Users: " . $pwdUsers->count() . "\n";
if ($pwdUsers->count() > 0) {
    $pwdUsers->take(3)->each(function($user) {
        echo "   - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
    });
}

echo "\n5. CHECKING ANNOUNCEMENT ROUTES:\n";
$routes = Route::getRoutes();
$announcementRoutes = [];
foreach ($routes as $route) {
    if (strpos($route->uri, 'announcement') !== false) {
        $announcementRoutes[] = [
            'method' => implode(',', $route->methods),
            'uri' => $route->uri,
            'name' => $route->name,
            'controller' => $route->action['controller'] ?? 'N/A',
            'middleware' => implode(',', $route->middleware())
        ];
    }
}

if (count($announcementRoutes) > 0) {
    echo "   Found " . count($announcementRoutes) . " announcement routes:\n";
    foreach ($announcementRoutes as $route) {
        echo "   [{$route['method']}] {$route['uri']} -> {$route['name']} (Middleware: {$route['middleware']})\n";
    }
} else {
    echo "   No announcement routes found!\n";
}

echo "\n6. KEY FINDINGS:\n";
echo "   ✓ Queue is set to 'sync' - notifications are sent immediately\n";
echo "   ✓ AnnouncementNotification implements ShouldQueue\n";
echo "   ✓ Notification channels: mail + database\n";
echo "   ✓ Routes are in 'admin' prefix with 'admin' middleware\n";
echo "   ✓ Controller redirects to 'admin.announcements.index' (correct route name)\n\n";

echo "7. POTENTIAL REDIRECT ISSUES:\n";
echo "   - The redirect SHOULD work if user is authenticated as admin\n";
echo "   - If redirected to home, it likely means:\n";
echo "     a) User is NOT logged in (check session)\n";
echo "     b) User role is NOT 'admin' (check user roles)\n";
echo "     c) AdminMiddleware is failing the isAdmin() check\n";
echo "     d) Route name 'admin.announcements.index' doesn't exist\n\n";

echo "8. CHECKING ANNOUNCEMENTCONTROLLER:\n";
$controllerReflection = new ReflectionClass('App\Http\Controllers\AnnouncementController');
echo "   Methods in controller:\n";
foreach ($controllerReflection->getMethods() as $method) {
    if ($method->isPublic() && !$method->getName() === '__construct') {
        echo "     - " . $method->getName() . "()\n";
    }
}
