<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Password security
            $table->timestamp('password_changed_at')->nullable()->after('password');
            $table->boolean('password_meets_current_standards')->default(false)->after('password_changed_at');
            $table->timestamp('last_password_changed_at')->nullable()->after('password_meets_current_standards');

            // Two-factor authentication
            $table->text('two_factor_secret')->nullable()->after('last_password_changed_at');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->timestamp('two_factor_confirmed_at')->nullable()->after('two_factor_recovery_codes');

            // Account security
            $table->timestamp('account_locked_until')->nullable()->after('two_factor_confirmed_at');
            $table->integer('failed_login_attempts')->default(0)->after('account_locked_until');

            // Security tracking
            $table->boolean('security_questions_set')->default(false)->after('failed_login_attempts');
            $table->timestamp('last_security_activity')->nullable()->after('security_questions_set');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'password_changed_at',
                'password_meets_current_standards',
                'last_password_changed_at',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'two_factor_confirmed_at',
                'account_locked_until',
                'failed_login_attempts',
                'security_questions_set',
                'last_security_activity',
            ]);
        });
    }
};
