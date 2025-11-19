<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\PwdDashboardController;
use App\Http\Controllers\EmployerDashboardController;
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
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\EmployerVerificationController;

// =========================================================================
// PUBLIC ROUTES (No authentication required)
// =========================================================================

// Home and static pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('home.search');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/events', [HomeController::class, 'events'])->name('events');
Route::get('/read-first', [HomeController::class, 'readFirst'])->name('read-first');
Route::get('/success-stories', [HomeController::class, 'successStories'])->name('success-stories');
Route::get('/success-stories/{id}', [HomeController::class, 'showStory'])->name('story.show');

// Terms and Privacy
Route::get('/terms', function () {
    return view('terms');
})->name('terms');

Route::get('/privacy', function () {
    return view('privacy');
})->name('privacy');

// Test routes for public translation
Route::get('/test-translation-public', function () {
    return view('test-translation-public');
})->name('test-translation-public');

Route::get('/test-translation-fix', function () {
    return view('test-translation-fix');
})->name('test-translation-fix');

// Authentication Routes
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

// =========================================================================
// PUBLIC JOB POSTINGS ROUTES (for viewing only - no applications)
// =========================================================================
Route::prefix('jobs')->group(function () {
    Route::get('/', [JobPostingController::class, 'publicIndex'])->name('job-postings.public');
    Route::get('/{job_posting}', [JobPostingController::class, 'publicShow'])->name('job-postings.public.show');
});

// =========================================================================
// PUBLIC SKILL TRAININGS ROUTES (for viewing only - no enrollments)
// =========================================================================
Route::prefix('skill-trainings')->group(function () {
    Route::get('/', [SkillTrainingController::class, 'publicIndex'])->name('skill-trainings.public.index');
    Route::get('/{skill_training}', [SkillTrainingController::class, 'publicShow'])->name('skill-trainings.public.show');
});

// Redirect /home to /dashboard for authenticated users, otherwise to home
Route::get('/home', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect('/');
});

// =========================================================================
// PUBLIC ACCESSIBILITY ROUTES (No authentication required)
// =========================================================================
Route::prefix('accessibility')->group(function () {
    Route::post('/translate', [AccessibilityController::class, 'translateText'])->name('accessibility.translate.public');
    Route::post('/translate-batch', [AccessibilityController::class, 'translateBatch'])->name('accessibility.translate-batch.public');
    Route::post('/quick-tool', [AccessibilityController::class, 'quickTool'])->name('accessibility.quick-tool.public');
});

// =========================================================================
// PROTECTED ROUTES (Authentication required)
// =========================================================================

Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

    // Email Verification Routes
    Route::prefix('email')->group(function () {
        Route::get('/verify', [App\Http\Controllers\Auth\VerificationController::class, 'show'])->name('verification.notice');
        Route::get('/verify/{id}/{hash}', [App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
        Route::post('/resend', [App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');
    });

    // =========================================================================
    // DASHBOARD ROUTES - Updated with separate controllers
    // =========================================================================

    // Main dashboard route that redirects based on role
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // PWD-specific dashboard (protected by PwdMiddleware)
    Route::middleware(['pwd'])->group(function () {
        Route::get('/pwd/dashboard', [PwdDashboardController::class, 'index'])->name('pwd.dashboard');
    });

    // =========================================================================
    // EMPLOYER-SPECIFIC ROUTES (Protected by EmployerMiddleware)
    // =========================================================================
    Route::middleware(['employer', 'pending.employer.verification'])->prefix('employer')->name('employer.')->group(function () {
        // Redirect /employer to /employer/dashboard
        Route::get('/', function () {
            return redirect()->route('employer.dashboard');
        });

        // Employer dashboard - accessible to all employers
        Route::get('/dashboard', [EmployerDashboardController::class, 'index'])->name('dashboard');

        // Settings route
        Route::get('/settings', [EmployerController::class, 'settings'])->name('settings');

        // Employer verification routes
        Route::prefix('verification')->name('verification.')->group(function () {
            Route::get('/apply', [EmployerVerificationController::class, 'showApplicationForm'])->name('apply');
            Route::post('/apply', [EmployerVerificationController::class, 'submitApplication'])->name('submit');
            Route::get('/status', [EmployerVerificationController::class, 'status'])->name('status');
            Route::get('/requirements', [EmployerVerificationController::class, 'requirements'])->name('requirements');
            Route::get('/renew', [EmployerVerificationController::class, 'showRenewalForm'])->name('renew');
            Route::post('/renew', [EmployerVerificationController::class, 'submitRenewal'])->name('renew.submit');
        });

        // Employer Profile Management (always accessible)
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [EmployerController::class, 'profile'])->name('show');
            Route::get('/edit', [EmployerController::class, 'editProfile'])->name('edit');
            Route::put('/update', [EmployerController::class, 'updateProfile'])->name('update');
            Route::get('/stats', [EmployerController::class, 'getStats'])->name('stats');
            Route::post('/resume/upload', [EmployerController::class, 'uploadResume'])->name('upload-resume');
            Route::delete('/resume/delete', [EmployerController::class, 'deleteResume'])->name('delete-resume');
        });

        // Job posting preview/draft routes (accessible without verification)
        Route::prefix('job-drafts')->name('job-drafts.')->group(function () {
            Route::get('/', [JobPostingController::class, 'draftIndex'])->name('index');
            Route::get('/create', [JobPostingController::class, 'createDraft'])->name('create');
            Route::post('/', [JobPostingController::class, 'storeDraft'])->name('store');
            Route::get('/{job_posting}/preview', [JobPostingController::class, 'previewDraft'])->name('preview');
            Route::post('/{job_posting}/submit-verification', [JobPostingController::class, 'submitForVerification'])->name('submit');
            Route::get('/{job_posting}/edit', [JobPostingController::class, 'editDraft'])->name('edit');
            Route::put('/{job_posting}', [JobPostingController::class, 'updateDraft'])->name('update');
            Route::delete('/{job_posting}', [JobPostingController::class, 'destroyDraft'])->name('destroy');
        });
    });

    // =========================================================================
    // VERIFIED EMPLOYER ROUTES (Protected by VerifiedEmployer middleware)
    // =========================================================================
    Route::middleware(['employer', 'verified.employer'])->prefix('employer')->name('employer.')->group(function () {
        // Job Posting Management (Protected by verified employer middleware)
        Route::prefix('job-postings')->name('job-postings.')->group(function () {
            Route::get('/', [JobPostingController::class, 'employerIndex'])->name('index');
            Route::get('/create', [JobPostingController::class, 'create'])->name('create');
            Route::post('/', [JobPostingController::class, 'store'])->name('store');
            Route::get('/{job_posting}', [JobPostingController::class, 'show'])->name('show');
            Route::get('/{job_posting}/edit', [JobPostingController::class, 'edit'])->name('edit');
            Route::put('/{job_posting}', [JobPostingController::class, 'update'])->name('update');
            Route::delete('/{job_posting}', [JobPostingController::class, 'destroy'])->name('destroy');
            Route::post('/{job_posting}/toggle-status', [JobPostingController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{job_posting}/duplicate', [JobPostingController::class, 'duplicate'])->name('duplicate');
            Route::post('/{job_posting}/extend-deadline', [JobPostingController::class, 'extendDeadline'])->name('extend-deadline');
            Route::get('/analytics', [JobPostingController::class, 'employerStatistics'])->name('statistics');
            Route::get('/export', [JobPostingController::class, 'exportEmployerJobs'])->name('export');
        });

        // Application Management for employer's job postings
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [JobApplicationController::class, 'employerIndex'])->name('index');
            Route::get('/{application}', [JobApplicationController::class, 'show'])->name('show');

            // 🔔 UPDATED: Application status routes with new notification system
            Route::post('/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('update-status');
            Route::post('/{application}/shortlist', [JobApplicationController::class, 'shortlist'])->name('shortlist');
            Route::post('/{application}/reject', [JobApplicationController::class, 'reject'])->name('reject');
            Route::post('/{application}/schedule-interview', [JobApplicationController::class, 'scheduleInterview'])->name('schedule-interview');
            Route::post('/bulk-update', [JobApplicationController::class, 'bulkUpdate'])->name('bulk-update');
            Route::get('/statistics', [JobApplicationController::class, 'employerStatistics'])->name('statistics');
            Route::get('/export', [JobApplicationController::class, 'exportEmployerApplications'])->name('export');
        });

        // Employer Analytics and Reports
        Route::prefix('analytics')->name('analytics.')->group(function () {
            Route::get('/overview', [EmployerController::class, 'analyticsOverview'])->name('overview');
            Route::get('/performance', [EmployerController::class, 'performanceMetrics'])->name('performance');
            Route::get('/application-trends', [EmployerController::class, 'applicationTrends'])->name('application-trends');
            Route::get('/jobs-performance', [EmployerController::class, 'jobsPerformance'])->name('jobs-performance');
        });
    });

    // =========================================================================
    // ACCESSIBILITY ROUTES (Authenticated users only - settings and preferences)
    // =========================================================================
    Route::prefix('accessibility')->group(function () {
        Route::get('/settings', [AccessibilityController::class, 'settings'])->name('accessibility.settings');
        Route::post('/preferences', [AccessibilityController::class, 'updatePreferences'])->name('accessibility.update-preferences');
        Route::get('/reset', [AccessibilityController::class, 'resetPreferences'])->name('accessibility.reset-preferences');
        Route::post('/toggle-language', [AccessibilityController::class, 'toggleLanguage'])->name('accessibility.toggle-language');
        // Note: translate, translate-batch, and quick-tool routes are now public (see above)
    });

    // =========================================================================
    // PROFILE ROUTES (All authenticated users)
    // =========================================================================
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/update', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/photo', [ProfileController::class, 'deletePhoto'])->name('profile.deletePhoto');

        // Resume Routes
        Route::post('/resume/upload', [ProfileController::class, 'uploadResume'])->name('profile.uploadResume');
        Route::get('/resume/download', [ProfileController::class, 'downloadResume'])->name('profile.downloadResume');
        Route::delete('/resume/delete', [ProfileController::class, 'deleteResume'])->name('profile.deleteResume');

        // PWD Profile Completion Routes
        Route::get('/complete', [ProfileController::class, 'showPwdCompleteForm'])->name('profile.pwd-complete-form');
        Route::post('/complete', [ProfileController::class, 'completePwdProfile'])->name('profile.pwd-complete');
    });

    // =========================================================================
    // NOTIFICATION ROUTES (All authenticated users)
    // =========================================================================
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
        Route::post('/{notification}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
        Route::delete('/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/', [NotificationController::class, 'clearAll'])->name('notifications.clear');
    });

    // =========================================================================
    // PWD-SPECIFIC ROUTES (Protected by PwdMiddleware with Profile Completion Check)
    // =========================================================================
    Route::middleware(['pwd', 'pwd.profile.complete'])->group(function () {
        // Job Applications (Protected - requires complete profile)
        Route::prefix('applications')->group(function () {
            Route::get('/', [JobApplicationController::class, 'index'])->name('applications.index');
            Route::get('/{application}', [JobApplicationController::class, 'show'])->name('applications.show');
            Route::post('/{application}/withdraw', [JobApplicationController::class, 'withdraw'])->name('applications.withdraw');
            Route::post('/{application}/cancel', [JobApplicationController::class, 'cancel'])->name('applications.cancel');
            Route::post('/job/{job}/apply', [JobApplicationController::class, 'apply'])->name('job.apply');
        });

        // Training Enrollments (Protected - requires complete profile)
        Route::prefix('enrollments')->group(function () {
            Route::get('/', [TrainingEnrollmentController::class, 'index'])->name('enrollments.index');
            Route::post('/', [TrainingEnrollmentController::class, 'store'])->name('enrollments.store');
            Route::get('/{enrollment}', [TrainingEnrollmentController::class, 'show'])->name('enrollments.show');
            Route::post('/{enrollment}/cancel', [TrainingEnrollmentController::class, 'cancel'])->name('enrollments.cancel');
            Route::delete('/{enrollment}', [TrainingEnrollmentController::class, 'destroy'])->name('enrollments.destroy');

            // Enrollment status update
            Route::post('/{enrollment}/status', [TrainingEnrollmentController::class, 'updateStatus'])->name('enrollments.updateStatus');
        });

        // Documents (Protected - requires complete profile)
        Route::prefix('documents')->group(function () {
            Route::get('/', [DocumentController::class, 'index'])->name('documents.index');
            Route::get('/create', [DocumentController::class, 'create'])->name('documents.create');
            Route::post('/', [DocumentController::class, 'store'])->name('documents.store');
            Route::get('/{document}', [DocumentController::class, 'show'])->name('documents.show');
            Route::get('/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
            Route::delete('/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');
        });

        // Resume Management (Protected)
        Route::resource('resumes', \App\Http\Controllers\ResumeController::class);
        Route::post('/resumes/{resume}/toggle-publish', [\App\Http\Controllers\ResumeController::class, 'togglePublish'])->name('resumes.toggle-publish');
        Route::delete('/resumes/{resume}/document', [\App\Http\Controllers\ResumeController::class, 'deleteDocument'])->name('resumes.delete-document');
        Route::get('/resumes/{resume}/download', [\App\Http\Controllers\ResumeController::class, 'download'])->name('resumes.download');

        // Skill Training Enrollment (Protected - requires complete profile)
        Route::post('/skill-trainings/{skill_training}/enroll', [SkillTrainingController::class, 'enroll'])->name('skill-trainings.enroll');
        // REMOVED DUPLICATE ROUTE: Route::get('/skill-trainings/{skill_training}', [SkillTrainingController::class, 'show'])->name('skill-trainings.show');
    });

    // =========================================================================
    // ADMIN ROUTES (Protected by AdminMiddleware)
    // =========================================================================
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        // Redirect /admin to /admin/dashboard
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });

        // Admin dashboard - using new separate controller
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Settings route
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');

        // Admin Profile Management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\AdminProfileController::class, 'show'])->name('show');
            Route::put('/', [App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('update');
            Route::put('/password', [App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('update-password');
            Route::post('/avatar', [App\Http\Controllers\Admin\AdminProfileController::class, 'updateAvatar'])->name('update-avatar');
            Route::delete('/avatar', [App\Http\Controllers\Admin\AdminProfileController::class, 'removeAvatar'])->name('remove-avatar');
        });

        // User Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [AdminController::class, 'users'])->name('index');
            Route::get('/create', [AdminController::class, 'createUser'])->name('create');
            Route::post('/', [AdminController::class, 'storeUser'])->name('store');
            Route::get('/{user}', [AdminController::class, 'userShow'])->name('show');
            Route::post('/{user}/activate', [AdminController::class, 'activateUser'])->name('activate');
            Route::post('/{user}/deactivate', [AdminController::class, 'deactivateUser'])->name('deactivate');
            Route::post('/{user}/unlock', [AdminController::class, 'unlockUser'])->name('unlock');
            Route::post('/{user}/role', [AdminController::class, 'updateUserRole'])->name('update-role');
            Route::delete('/{user}', [AdminController::class, 'deleteUser'])->name('destroy');
        });

        // Employer Verification Management
        Route::prefix('employer-verifications')->name('employer-verifications.')->group(function () {
            Route::get('/', [AdminController::class, 'employerVerifications'])->name('index');
            Route::get('/pending', [AdminController::class, 'pendingEmployerVerifications'])->name('pending');
            Route::get('/{employer}/review', [AdminController::class, 'reviewEmployerVerification'])->name('review');
            Route::post('/{employer}/approve', [AdminController::class, 'approveEmployerVerification'])->name('approve');
            Route::post('/{employer}/reject', [AdminController::class, 'rejectEmployerVerification'])->name('reject');
            Route::post('/{employer}/request-more-info', [AdminController::class, 'requestMoreInfo'])->name('request-info');
            Route::get('/{employer}/documents', [AdminController::class, 'viewEmployerDocuments'])->name('documents');
            Route::get('/expired', [AdminController::class, 'expiredEmployerVerifications'])->name('expired');
            Route::post('/{employer}/renew', [AdminController::class, 'renewEmployerVerification'])->name('renew');
        });

        // Security Reports
        Route::get('/security-report', [AdminController::class, 'userSecurityReport'])->name('security.report');

        // System Statistics
        Route::get('/statistics', [AdminController::class, 'systemStatistics'])->name('statistics');

        // Application Management
        Route::prefix('applications')->name('applications.')->group(function () {
            Route::get('/', [JobApplicationController::class, 'adminIndex'])->name('index');
            Route::get('/{application}', [JobApplicationController::class, 'show'])->name('show');

            // 🔔 UPDATED: Application status routes with new notification system
            Route::post('/{application}/status', [JobApplicationController::class, 'updateStatus'])->name('update-status');
            Route::post('/{application}/shortlist', [JobApplicationController::class, 'shortlist'])->name('shortlist');
            Route::post('/{application}/reject', [JobApplicationController::class, 'reject'])->name('reject');
            Route::post('/bulk-update', [JobApplicationController::class, 'bulkUpdate'])->name('bulk-update');
            Route::get('/statistics', [JobApplicationController::class, 'statistics'])->name('statistics');
        });

        // Training Management
        Route::prefix('enrollments')->name('enrollments.')->group(function () {
            Route::get('/', [TrainingEnrollmentController::class, 'adminIndex'])->name('index');
            Route::post('/{enrollment}/status', [TrainingEnrollmentController::class, 'updateStatus'])->name('updateStatus');
            Route::post('/bulk-update', [TrainingEnrollmentController::class, 'bulkUpdate'])->name('bulkUpdate');
        });

        // Job Posting Management (Admin-specific)
        Route::prefix('job-postings')->name('job-postings.')->group(function () {
            Route::get('/', [JobPostingController::class, 'index'])->name('index');
            Route::get('/create', [JobPostingController::class, 'create'])->name('create');
            Route::post('/', [JobPostingController::class, 'store'])->name('store');
            Route::get('/{job_posting}', [JobPostingController::class, 'show'])->name('show');
            Route::get('/{job_posting}/edit', [JobPostingController::class, 'edit'])->name('edit');
            Route::put('/{job_posting}', [JobPostingController::class, 'update'])->name('update');
            Route::delete('/{job_posting}', [JobPostingController::class, 'destroy'])->name('destroy');
            Route::post('/{job_posting}/toggle-status', [JobPostingController::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{job_posting}/duplicate', [JobPostingController::class, 'duplicate'])->name('duplicate');
            Route::post('/{job_posting}/extend-deadline', [JobPostingController::class, 'extendDeadline'])->name('extend-deadline');
            Route::get('/analytics', [JobPostingController::class, 'analytics'])->name('analytics');
            Route::get('/export', [JobPostingController::class, 'export'])->name('export');
            Route::post('/bulk-action', [JobPostingController::class, 'bulkAction'])->name('bulk-action');
        });

        // Skill Trainings Management
        Route::prefix('skill-trainings')->name('skill-trainings.')->group(function () {
            Route::get('/', [SkillTrainingController::class, 'index'])->name('index');
            Route::get('/create', [SkillTrainingController::class, 'create'])->name('create');
            Route::post('/', [SkillTrainingController::class, 'store'])->name('store');
            Route::get('/{skill_training}', [SkillTrainingController::class, 'show'])->name('show');
            Route::get('/{skill_training}/edit', [SkillTrainingController::class, 'edit'])->name('edit');
            Route::put('/{skill_training}', [SkillTrainingController::class, 'update'])->name('update');
            Route::delete('/{skill_training}', [SkillTrainingController::class, 'destroy'])->name('destroy');
            Route::post('/{skill_training}/toggle-status', [SkillTrainingController::class, 'toggleStatus'])->name('toggle-status');
            Route::get('/{skill_training}/enrollments', [SkillTrainingController::class, 'enrollments'])->name('enrollments');
        });

        // Announcements Management
        Route::prefix('announcements')->name('announcements.')->group(function () {
            Route::get('/', [AnnouncementController::class, 'index'])->name('index');
            Route::get('/create', [AnnouncementController::class, 'create'])->name('create');
            Route::post('/', [AnnouncementController::class, 'store'])->name('store');
            Route::get('/{announcement}', [AnnouncementController::class, 'show'])->name('show');
            Route::get('/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('edit');
            Route::put('/{announcement}', [AnnouncementController::class, 'update'])->name('update');
            Route::delete('/{announcement}', [AnnouncementController::class, 'destroy'])->name('destroy');
        });
    });
});

// =========================================================================
// API ROUTES (For AJAX calls)
// =========================================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/job-postings/filter/counts', [JobPostingController::class, 'getFilterCounts'])->name('job-postings.filter-counts');
    Route::prefix('api')->group(function () {
        Route::get('/job-postings/stats', [JobPostingController::class, 'getFilterCounts'])->name('api.job-postings.stats');
        Route::get('/job-postings/{id}/quick-stats', [JobPostingController::class, 'getJobStats'])->name('api.job-postings.quick-stats');

        // Employer API routes
        Route::get('/employer/stats', [EmployerDashboardController::class, 'getStats'])->name('api.employer.stats');
        Route::get('/employer/application-trends', [EmployerController::class, 'getApplicationTrends'])->name('api.employer.application-trends');
        Route::get('/employer/performance-metrics', [EmployerController::class, 'getPerformanceMetrics'])->name('api.employer.performance-metrics');

        // Application API routes
        Route::get('/applications/{application}/status', [JobApplicationController::class, 'show'])->name('api.applications.show');
    });
});

// Keep the home route for Laravel compatibility
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Laravel Auth Routes (keep for compatibility)
Auth::routes(['register' => false]);








// Add these test routes at the bottom of your web.php file
Route::get('/test-email-simple', function () {
    try {
        $yourEmail = 'phomelarosetedonato@gmail.com';

        Mail::raw('This is a test email from your PWD System. If you receive this, your email configuration is working!', function ($message) use ($yourEmail) {
            $message->to($yourEmail)
                    ->subject('✅ PWD System - Email Test Successful');
        });

        return '✅ Simple test email sent successfully to: ' . $yourEmail .
               '<br><br>Please check your email inbox and spam folder.';
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage() .
               '<br><br>Please check:
               <br>1. Your Gmail app password is correct
               <br>2. 2-factor authentication is enabled
               <br>3. You generated an app password for "Mail"';
    }
});

// Test with HTML email
Route::get('/test-email-html', function () {
    try {
        $yourEmail = 'phomelarosetedonato@gmail.com';

        Mail::send([], [], function ($message) use ($yourEmail) {
            $message->to($yourEmail)
                    ->subject('✅ PWD System - HTML Email Test')
                    ->html('
                        <h2>PWD System Email Test</h2>
                        <p>This is a <strong>HTML email test</strong> from your PWD System.</p>
                        <p>If you receive this, your email configuration is working perfectly!</p>
                        <hr>
                        <p><small>System: ' . config('app.name') . '</small></p>
                    ');
        });

        return '✅ HTML test email sent successfully to: ' . $yourEmail;
    } catch (\Exception $e) {
        return '❌ Error: ' . $e->getMessage();
    }
});


// Debug route to check notification sending
Route::get('/debug-notification', function () {
    try {
        $application = App\Models\JobApplication::first();
        if (!$application) {
            return 'No applications found. Please create an application first.';
        }

        $user = $application->user;

        Log::info("Testing notification for application: " . $application->id);
        Log::info("User email: " . $user->email);
        Log::info("Job: " . ($application->jobPosting->title ?? 'No job posting'));

        // Test the notification directly - NOW WITH ONLY 1 ARGUMENT
        $user->notify(new App\Notifications\ApplicationApproved($application));

        Log::info("Notification sent successfully");

        return 'Debug: Notification sent to ' . $user->email .
               '<br>Job: ' . ($application->jobPosting->title ?? 'Unknown') .
               '<br>Check storage/logs/laravel.log for details';

    } catch (\Exception $e) {
        Log::error('Debug notification failed: ' . $e->getMessage());
        return 'Error: ' . $e->getMessage() .
               '<br>File: ' . $e->getFile() .
               '<br>Line: ' . $e->getLine();
    }
});
// Check queue status
Route::get('/queue-status', function () {
    $pendingJobs = DB::table('jobs')->count();
    $failedJobs = DB::table('failed_jobs')->count();

    return 'Pending jobs: ' . $pendingJobs . '<br>' .
           'Failed jobs: ' . $failedJobs . '<br>' .
           'Queue connection: ' . config('queue.default') . '<br>' .
           '<a href="/process-queue">Process Queue Now</a>';
});

// Process queue manually
Route::get('/process-queue', function () {
    try {
        $exitCode = Artisan::call('queue:work', [
            '--once' => true,
            '--queue' => 'default'
        ]);

        return 'Queue processed. Exit code: ' . $exitCode;
    } catch (\Exception $e) {
        return 'Queue error: ' . $e->getMessage();
    }
});

// Enhanced debug notification
Route::get('/test-notification-enhanced', function () {
    try {
        $application = App\Models\JobApplication::first();
        if (!$application) {
            return 'No applications found.';
        }

        $user = $application->user;

        Log::info("🎯 ENHANCED NOTIFICATION TEST STARTED");
        Log::info("📧 User Email: " . $user->email);
        Log::info("👤 User Name: " . $user->name);
        Log::info("💼 Job: " . ($application->jobPosting->title ?? 'No job'));

        // Test if basic email works first
        Mail::raw('Basic email test', function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Basic Email Test');
        });
        Log::info("✅ Basic email sent");

        // Test notification
        $user->notify(new App\Notifications\ApplicationApproved($application));
        Log::info("✅ Notification queued");

        return 'Enhanced test completed for: ' . $user->email .
               '<br>Check logs for details.';

    } catch (\Exception $e) {
        Log::error('❌ Enhanced test failed: ' . $e->getMessage());
        return 'Error: ' . $e->getMessage();
    }
});
