<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            // Add disability_severity first (this is the most important one)
            if (!Schema::hasColumn('pwd_profiles', 'disability_severity')) {
                $table->enum('disability_severity', ['mild', 'moderate', 'severe'])
                      ->default('moderate')
                      ->after('disability_type');
            }
        });
    }

    public function down()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            $table->dropColumn(['disability_severity']);
        });
    }
};
