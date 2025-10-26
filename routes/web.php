<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Accessibility\AccessibilityController;
use App\Http\Controllers\JobPostingController;
use App\Http\Controllers\SkillTrainingController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\TrainingEnrollmentController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

// =========================================================================
// PUBLIC ROUTES (No authentication required)
// =========================================================================

// Home and static pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/read-first', [HomeController::class, 'readFirst'])->name('read-first');

// Terms and Privacy (required for registration)
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

// Authentication Routes - FIXED: Use correct LoginController namespace
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

// Password Reset Routes
Route::prefix('password')->group(function () {
    Route::get('/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

// Public Accessibility Routes
Route::prefix('accessibility')->group(function () {
    Route::post('/quick-tool', [AccessibilityController::class, 'quickTool'])->name('accessibility.quick-tool');
    Route::get('/features', [AccessibilityController::class, 'features'])->name('accessibility.features');
});

// Public Job Postings (for all users)
Route::get('/jobs', [JobPostingController::class, 'publicIndex'])->name('job-postings.public');
Route::get('/jobs/{job_posting}', [JobPostingController::class, 'publicShow'])->name('job-postings.public.show');

// Public Skill Trainings (for all users)
Route::get('/trainings', [SkillTrainingController::class, 'publicIndex'])->name('skill-trainings.public');
Route::get('/trainings/{skill_training}', [SkillTrainingController::class, 'publicShow'])->name('skill-trainings.public-show');

// Redirect /home to /dashboard for authenticated users, otherwise to home
Route::get('/home', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/');
});

// =========================================================================
// PROTECTED ROUTES (Authentication required)
// =========================================================================

Route::middleware(['auth'])->group(function () {
    // Logout - FIXED: Use correct LoginController namespace
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    // Email Verification Routes
    Route::prefix('email')->group(function () {
        Route::get('/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
        Route::get('/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
        Route::post('/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Protected Accessibility Routes
    Route::prefix('accessibility')->group(function () {
        Route::get('/settings', [AccessibilityController::class, 'settings'])->name('accessibility.settings');
        Route::post('/preferences', [AccessibilityController::class, 'updatePreferences'])->name('accessibility.update');
        Route::post('/reset', [AccessibilityController::class, 'resetPreferences'])->name('accessibility.reset');
        Route::post('/quick-tool-protected', [AccessibilityController::class, 'quickTool'])->name('accessibility.quick-tool-protected');
    });

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/photo', [ProfileController::class, 'deletePhoto'])->name('profile.deletePhoto');

        // PWD Profile Completion Routes
        Route::get('/complete', [ProfileController::class, 'showPwdCompleteForm'])->name('profile.pwd-complete-form');
        Route::post('/complete', [ProfileController::class, 'completePwdProfile'])->name('profile.pwd-complete');
    });

    // Notification Routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/', [NotificationController::class, 'clearAll'])->name('notifications.clear');
    });

    // =========================================================================
    // JOB APPLICATION ROUTES
    // =========================================================================
    Route::prefix('applications')->group(function () {
        // User's applications
        Route::get('/', [JobApplicationController::class, 'index'])->name('applications.index');
        Route::get('/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
        Route::post('/{application}/withdraw', [JobApplicationController::class, 'withdraw'])->name('applications.withdraw');

        // Job application submission
        Route::post('/job/{job}/apply', [JobApplicationController::class, 'apply'])->name('job.apply');
    });

    // =========================================================================
    // JOB POSTING MANAGEMENT ROUTES (Admin & Authorized Users)
    // =========================================================================
    Route::prefix('job-postings')->group(function () {
        // Main CRUD routes
        Route::get('/', [JobPostingController::class, 'index'])->name('job-postings.index');
        Route::get('/create', [JobPostingController::class, 'create'])->name('job-postings.create');
        Route::post('/', [JobPostingController::class, 'store'])->name('job-postings.store');
        Route::get('/{job_posting}', [JobPostingController::class, 'show'])->name('job-postings.show');
        Route::get('/{job_posting}/edit', [JobPostingController::class, 'edit'])->name('job-postings.edit');
        Route::put('/{job_posting}', [JobPostingController::class, 'update'])->name('job-postings.update');
        Route::delete('/{job_posting}', [JobPostingController::class, 'destroy'])->name('job-postings.destroy');

        // Enhanced Job Posting Features
        Route::post('/{job_posting}/toggle-status', [JobPostingController::class, 'toggleStatus'])
            ->name('job-postings.toggle-status');
        Route::post('/{job_posting}/duplicate', [JobPostingController::class, 'duplicate'])
            ->name('job-postings.duplicate');
        Route::post('/{job_posting}/extend-deadline', [JobPostingController::class, 'extendDeadline'])
            ->name('job-postings.extend-deadline');

        // Statistics and Analytics
        Route::get('/statistics/overview', [JobPostingController::class, 'statistics'])
            ->name('job-postings.statistics');
        Route::get('/export/csv', [JobPostingController::class, 'export'])
            ->name('job-postings.export');

        // Bulk Actions
        Route::post('/bulk/actions', [JobPostingController::class, 'bulkAction'])
            ->name('job-postings.bulk-action');
    });

    // =========================================================================
    // SKILL TRAININGS ROUTES
    // =========================================================================
    Route::prefix('skill-trainings')->group(function () {
        Route::get('/', [SkillTrainingController::class, 'index'])->name('skill-trainings.index');
        Route::get('/create', [SkillTrainingController::class, 'create'])->name('skill-trainings.create');
        Route::post('/', [SkillTrainingController::class, 'store'])->name('skill-trainings.store');
        Route::get('/{skill_training}', [SkillTrainingController::class, 'show'])->name('skill-trainings.show');
        Route::get('/{skill_training}/edit', [SkillTrainingController::class, 'edit'])->name('skill-trainings.edit');
        Route::put('/{skill_training}', [SkillTrainingController::class, 'update'])->name('skill-trainings.update');
        Route::delete('/{skill_training}', [SkillTrainingController::class, 'destroy'])->name('skill-trainings.destroy');

        // Toggle status route
        Route::post('/{skill_training}/toggle-status', [SkillTrainingController::class, 'toggleStatus'])
            ->name('skill-trainings.toggle-status');
    });

    // =========================================================================
    // TRAINING ENROLLMENT ROUTES
    // =========================================================================
    Route::prefix('enrollments')->group(function () {
        Route::get('/', [TrainingEnrollmentController::class, 'index'])->name('enrollments.index');
        Route::post('/', [TrainingEnrollmentController::class, 'store'])->name('enrollments.store');
        Route::delete('/{enrollment}', [TrainingEnrollmentController::class, 'destroy'])->name('enrollments.destroy');
    });

    // =========================================================================
    // ANNOUNCEMENTS ROUTES
    // =========================================================================
    Route::prefix('announcements')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('announcements.index');
        Route::get('/create', [AnnouncementController::class, 'create'])->name('announcements.create');
        Route::post('/', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::get('/{announcement}', [AnnouncementController::class, 'show'])->name('announcements.show');
        Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('announcements.edit');
        Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');
    });

    // =========================================================================
    // DOCUMENTS ROUTES
    // =========================================================================
    Route::prefix('documents')->group(function () {
        Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
        Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
        Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
    });

    // =========================================================================
    // ADMIN ROUTES
    // =========================================================================
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Redirect /admin to /admin/dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // =========================================================================
        // USER MANAGEMENT ROUTES
        // =========================================================================
        Route::prefix('users')->name('users.')->group(function () {
            // User listing and management
            Route::get('/', [AdminController::class, 'users'])->name('index');
            Route::get('/create', [AdminController::class, 'createUser'])->name('create');
            Route::post('/', [AdminController::class, 'storeUser'])->name('store');
            Route::get('/{user}', [AdminController::class, 'userShow'])->name('show');

            // User status management
            Route::post('/{user}/activate', [AdminController::class, 'activateUser'])->name('activate');
            Route::post('/{user}/deactivate', [AdminController::class, 'deactivateUser'])->name('deactivate');
            Route::post('/{user}/unlock', [AdminController::class, 'unlockUser'])->name('unlock');
            Route::post('/{user}/role', [AdminController::class, 'updateUserRole'])->name('update-role');
        });

        // Security Reports
        Route::get('/security-report', [AdminController::class, 'userSecurityReport'])->name('security.report');

        // System Statistics
        Route::get('/statistics', [AdminController::class, 'systemStatistics'])->name('statistics');

        // =========================================================================
        // APPLICATION MANAGEMENT ROUTES
        // =========================================================================
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [JobApplicationController::class, 'adminIndex'])->name('index');
            Route::post('/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('status');
            Route::post('/bulk-update', [JobApplicationController::class, 'bulkUpdate'])->name('bulk-update');
            Route::get('/statistics', [JobApplicationController::class, 'statistics'])->name('statistics');
        });

        // =========================================================================
        // TRAINING MANAGEMENT ROUTES
        // =========================================================================
        Route::prefix('enrollments')->name('enrollments.')->group(function () {
            Route::get('/', [TrainingEnrollmentController::class, 'adminIndex'])->name('index');
            Route::post('/{enrollment}/status', [TrainingEnrollmentController::class, 'updateStatus'])->name('updateStatus');
        });

        // =========================================================================
        // JOB POSTING MANAGEMENT ROUTES (Admin-specific)
        // =========================================================================
        Route::prefix('job-postings')->name('job-postings.')->group(function () {
            Route::get('/analytics', [JobPostingController::class, 'statistics'])->name('statistics');
            Route::get('/export', [JobPostingController::class, 'export'])->name('export');
            Route::post('/bulk-action', [JobPostingController::class, 'bulkAction'])->name('bulk-action');
        });

        // =========================================================================
        // RESOURCE MANAGEMENT ROUTES (Backward compatibility)
        // =========================================================================
        Route::resource('job-postings', JobPostingController::class)->except(['index', 'show', 'create', 'edit']);
        Route::resource('skill-trainings', SkillTrainingController::class)->except(['index', 'show', 'create', 'edit']);
        Route::resource('announcements', AnnouncementController::class)->except(['index', 'show', 'create', 'edit']);
    });
});

// =========================================================================
// API ROUTES (For AJAX calls)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/job-postings/filter/counts', [JobPostingController::class, 'getFilterCounts'])->name('job-postings.filter-counts');

    // Additional API routes for job postings
    Route::prefix('api')->group(function () {
        Route::get('/job-postings/stats', [JobPostingController::class, 'getFilterCounts'])->name('api.job-postings.stats');
        Route::get('/job-postings/{id}/quick-stats', [JobPostingController::class, 'getJobStats'])->name('api.job-postings.quick-stats');
    });
});

// =========================================================================
// DEBUG ROUTES (Conditional - only in development)
// =========================================================================

if (env('APP_DEBUG', false)) {
    Route::get('/debug-user', function () {
        $user = auth()->user();

        if (!$user) {
            return "No user logged in";
        }

        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'isAdmin()' => $user->isAdmin() ? 'true' : 'false',
            'isPwd()' => $user->isPwd() ? 'true' : 'false',
            'hasPwdProfile()' => $user->hasPwdProfile() ? 'true' : 'false'
        ];
    })->middleware('auth');

    // Test basic form submission
    Route::get('/debug-registration-form', function() {
        return '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Debug Registration</title>
            <style>
                body { font-family: Arial; padding: 20px; }
                .form-group { margin: 10px 0; }
                label { display: block; margin-bottom: 5px; }
                input, textarea { width: 300px; padding: 5px; }
                .error { color: red; margin: 5px 0; }
                .success { color: green; margin: 5px 0; }
            </style>
        </head>
        <body>
            <h1>Debug Registration Form</h1>
            <form method="POST" action="' . route('register') . '">
                ' . csrf_field() . '

                <div class="form-group">
                    <label>User Type *</label>
                    <div>
                        <input type="radio" name="user_type" value="pwd" checked> PWD
                        <input type="radio" name="user_type" value="employer"> Employer
                        <input type="radio" name="user_type" value="admin"> Admin
                    </div>
                </div>

                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="name" value="Test User ' . rand(1000,9999) . '" required>
                </div>

                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" name="email" value="test' . rand(1000,9999) . '@test.com" required>
                </div>

                <div class="form-group">
                    <label>Phone *</label>
                    <input type="text" name="phone" value="1234567890" required>
                </div>

                <div class="form-group">
                    <label>Address *</label>
                    <textarea name="address" required>Test Address</textarea>
                </div>

                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" value="Test1234" required>
                </div>

                <div class="form-group">
                    <label>Confirm Password *</label>
                    <input type="password" name="password_confirmation" value="Test1234" required>
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="terms" checked required>
                        I agree to terms *
                    </label>
                </div>

                <button type="submit" style="padding: 10px 20px; background: blue; color: white; border: none;">Test Register</button>
            </form>

            <div style="margin-top: 20px;">
                <a href="/debug-create-user">Test Direct User Creation</a> |
                <a href="/debug-session">Check Session</a> |
                <a href="/register">Back to Real Form</a>
            </div>
        </body>
        </html>
        ';
    });

    // Test direct user creation
    Route::get('/debug-create-user', function() {
        try {
            $user = \App\Models\User::create([
                'name' => 'Debug User ' . rand(1000, 9999),
                'email' => 'debug' . rand(1000, 9999) . '@test.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'pwd',
                'phone' => '1234567890',
                'address' => 'Debug Address',
                'registration_ip' => request()->ip(),
            ]);

            return response()->json([
                'success' => true,
                'user' => $user->toArray(),
                'message' => 'User created directly'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    });

    // Check what's in the session/validation
    Route::get('/debug-session', function() {
        return [
            'session_data' => session()->all(),
            'old_input' => old(),
            'errors' => session('errors') ? session('errors')->all() : 'No errors',
            'csrf_token' => csrf_token()
        ];
    });
}

// Laravel Auth Routes (keep for compatibility)
Auth::routes(['register' => false]); // We've already defined custom registration routes

// Keep the home route for Laravel compatibility
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
