<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Password security (guarded)
            if (!Schema::hasColumn('users', 'password_changed_at')) {
                $table->timestamp('password_changed_at')->nullable();
            }
            if (!Schema::hasColumn('users', 'password_meets_current_standards')) {
                $table->boolean('password_meets_current_standards')->default(false);
            }
            if (!Schema::hasColumn('users', 'last_password_changed_at')) {
                $table->timestamp('last_password_changed_at')->nullable();
            }

            // Two-factor authentication
            if (!Schema::hasColumn('users', 'two_factor_secret')) {
                $table->text('two_factor_secret')->nullable();
            }
            if (!Schema::hasColumn('users', 'two_factor_recovery_codes')) {
                $table->text('two_factor_recovery_codes')->nullable();
            }
            if (!Schema::hasColumn('users', 'two_factor_confirmed_at')) {
                $table->timestamp('two_factor_confirmed_at')->nullable();
            }

            // Account security
            if (!Schema::hasColumn('users', 'account_locked_until')) {
                $table->timestamp('account_locked_until')->nullable();
            }
            if (!Schema::hasColumn('users', 'failed_login_attempts')) {
                $table->integer('failed_login_attempts')->default(0);
            }

            // Security tracking
            if (!Schema::hasColumn('users', 'security_questions_set')) {
                $table->boolean('security_questions_set')->default(false);
            }
            if (!Schema::hasColumn('users', 'last_security_activity')) {
                $table->timestamp('last_security_activity')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $drop = [];
            foreach (['password_changed_at','password_meets_current_standards','last_password_changed_at','two_factor_secret','two_factor_recovery_codes','two_factor_confirmed_at','account_locked_until','failed_login_attempts','security_questions_set','last_security_activity'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $drop[] = $col;
                }
            }
            if (!empty($drop)) {
                $table->dropColumn($drop);
            }
        });
    }
};
