<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add is_active column if it doesn't exist
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('address');
            }

            // Add registration_user_agent column if it doesn't exist
            if (!Schema::hasColumn('users', 'registration_user_agent')) {
                $table->text('registration_user_agent')->nullable()->after('last_security_activity');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Safe rollback - only remove if they exist
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }

            if (Schema::hasColumn('users', 'registration_user_agent')) {
                $table->dropColumn('registration_user_agent');
            }
        });
    }
};
