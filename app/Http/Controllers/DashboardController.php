<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            Log::warning('Dashboard accessed without authenticated user');
            return redirect()->route('login');
        }

        // Debugging
        Log::info('=== DASHBOARD ROLE ROUTING ===', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'isAdmin()' => $user->isAdmin() ? 'true' : 'false',
            'isPwd()' => $user->isPwd() ? 'true' : 'false',
        ]);

        // Check if user has no role
        if (!$user->role) {
            Log::error('User has no role assigned!', ['user_id' => $user->id]);
            return $this->defaultDashboard();
        }

        // Route to appropriate dashboard
        if ($user->isPwd()) {
            Log::info('ROUTE: Redirecting to PWD dashboard', ['user_id' => $user->id]);
            return redirect()->route('pwd.dashboard');
        }

        if ($user->isAdmin()) {
            Log::info('ROUTE: Redirecting to Admin dashboard', ['user_id' => $user->id]);
            return redirect()->route('admin.dashboard');
        }

        // Unrecognized role
        Log::error('User has unrecognized role', [
            'user_id' => $user->id,
            'role' => $user->role
        ]);

        return $this->defaultDashboard();
    }
    private function defaultDashboard()
    {
        /** @var \App\Models\User $user */
        /** @var \App\Models\User $user */
        $user = Auth::user();

        Log::info('Loading default dashboard', [
            'role' => $user->role
        ]);

        return view('dashboard.default', [
            'user' => $user,
            'role' => $user->role
        ]);
    }
}
