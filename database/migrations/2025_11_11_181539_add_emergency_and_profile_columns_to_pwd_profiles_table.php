<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            // Emergency Contact Information
            if (!Schema::hasColumn('pwd_profiles', 'emergency_contact_name')) {
                $table->string('emergency_contact_name')->nullable()->after('special_needs');
            }
            if (!Schema::hasColumn('pwd_profiles', 'emergency_contact_phone')) {
                $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
            }
            if (!Schema::hasColumn('pwd_profiles', 'emergency_contact_relationship')) {
                $table->string('emergency_contact_relationship')->nullable()->after('emergency_contact_phone');
            }

            // Profile Photo
            if (!Schema::hasColumn('pwd_profiles', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('emergency_contact_relationship');
            }

            // PWD ID Information
            if (!Schema::hasColumn('pwd_profiles', 'pwd_id_number')) {
                $table->string('pwd_id_number', 100)->nullable()->after('profile_photo');
            }
            if (!Schema::hasColumn('pwd_profiles', 'pwd_id_photo')) {
                $table->string('pwd_id_photo')->nullable()->after('pwd_id_number');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
                'profile_photo',
                'pwd_id_number',
                'pwd_id_photo',
            ]);
        });
    }
};
