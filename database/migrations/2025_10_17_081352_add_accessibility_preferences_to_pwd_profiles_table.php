<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            // Add assistive_devices
            if (!Schema::hasColumn('pwd_profiles', 'assistive_devices')) {
                $table->json('assistive_devices')->nullable()->after('disability_severity');
            }

            // Add accessibility_needs
            if (!Schema::hasColumn('pwd_profiles', 'accessibility_needs')) {
                $table->json('accessibility_needs')->nullable()->after('assistive_devices');
            }

            // Add profile_completed
            if (!Schema::hasColumn('pwd_profiles', 'profile_completed')) {
                $table->boolean('profile_completed')->default(false)->after('special_needs');
            }
        });
    }

    public function down()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'assistive_devices',
                'accessibility_needs',
                'profile_completed'
            ]);
        });
    }
};
