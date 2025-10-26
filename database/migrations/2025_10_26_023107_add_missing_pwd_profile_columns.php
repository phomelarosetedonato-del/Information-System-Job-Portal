<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            // Only add columns that don't exist
            if (!Schema::hasColumn('pwd_profiles', 'assistive_devices')) {
                $table->text('assistive_devices')->nullable();
            }
            if (!Schema::hasColumn('pwd_profiles', 'accessibility_needs')) {
                $table->text('accessibility_needs')->nullable();
            }
            if (!Schema::hasColumn('pwd_profiles', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable();
            }
            if (!Schema::hasColumn('pwd_profiles', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone')->nullable();
            }
            if (!Schema::hasColumn('pwd_profiles', 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship')->nullable();
            }
            if (!Schema::hasColumn('pwd_profiles', 'profile_photo')) {
                $table->string('profile_photo')->nullable();
            }
            if (!Schema::hasColumn('pwd_profiles', 'pwd_id_number')) {
                $table->string('pwd_id_number')->nullable();
            }
            if (!Schema::hasColumn('pwd_profiles', 'pwd_id_photo')) {
                $table->string('pwd_id_photo')->nullable();
            }
            if (!Schema::hasColumn('pwd_profiles', 'profile_completed')) {
                $table->boolean('profile_completed')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'assistive_devices',
                'accessibility_needs',
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'profile_photo',
                'pwd_id_number',
                'pwd_id_photo',
                'profile_completed'
            ]);
        });
    }
};
