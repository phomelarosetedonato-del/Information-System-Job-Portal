<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\JobPosting;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        //
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // ==================== EXISTING GATES (Keep these) ====================
        Gate::define('is-admin', function ($user) {
            return $user->role === 'admin';
        });

        Gate::define('is-pwd', function ($user) {
            return $user->role === 'pwd';
        });

        // ==================== NEW JOB POSTING GATES (Add these) ====================

        // Admin and employers can manage job postings
        Gate::define('manage-job-postings', function (User $user) {
            return $user->isAdmin() || $user->role === 'employer';
        });

        // Create job postings
        Gate::define('create-job-postings', function (User $user) {
            return $user->isAdmin() || $user->role === 'employer';
        });

        // View job posting (admin view)
        Gate::define('view-job-posting', function (User $user, JobPosting $jobPosting) {
            return $user->isAdmin() || $user->role === 'employer';
        });

        // Update job posting - admin or the creator
        Gate::define('update-job-posting', function (User $user, JobPosting $jobPosting) {
            return $user->isAdmin() ||
                   ($user->role === 'employer' && $jobPosting->created_by == $user->id);
        });

        // Delete job posting - admin or the creator
        Gate::define('delete-job-posting', function (User $user, JobPosting $jobPosting) {
            return $user->isAdmin() ||
                   ($user->role === 'employer' && $jobPosting->created_by == $user->id);
        });

        // ==================== NEW ADMIN-ONLY GATES ====================
        Gate::define('view-admin-panel', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('access-statistics', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('perform-bulk-actions', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('export-data', function (User $user) {
            return $user->isAdmin();
        });

        Gate::define('view-analytics', function (User $user) {
            return $user->isAdmin();
        });

        // ==================== NEW PWD USER GATES ====================
        Gate::define('apply-for-jobs', function (User $user) {
            return $user->isPwd() && $user->hasPwdProfile();
        });

        Gate::define('enroll-in-trainings', function (User $user) {
            return $user->isPwd() && $user->hasPwdProfile();
        });

        // ==================== NEW PUBLIC ACCESS GATES ====================
        Gate::define('view-public-jobs', function (?User $user) {
            return true; // Everyone can view public jobs
        });

        Gate::define('view-public-job-details', function (?User $user, JobPosting $jobPosting) {
            // Check if job is active and not expired
            if (!$jobPosting->is_active) return false;
            if ($jobPosting->application_deadline && $jobPosting->application_deadline->isPast()) return false;
            return true;
        });

        // ==================== NEW EMPLOYER GATE ====================
        Gate::define('is-employer', function (User $user) {
            return $user->role === 'employer';
        });
    }
}
