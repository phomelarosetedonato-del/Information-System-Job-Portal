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
            $table->string('nationality')->nullable();
            $table->string('education_level')->nullable();
            $table->string('school_name')->nullable();
            $table->text('limitations')->nullable();
            $table->string('desired_position')->nullable();
            $table->string('employment_type')->nullable();
            $table->string('preferred_work_conditions')->nullable();
            $table->text('accessibility_accommodations')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            $table->dropColumn('nationality');
            $table->dropColumn('education_level');
            $table->dropColumn('school_name');
            $table->dropColumn('limitations');
            $table->dropColumn('desired_position');
            $table->dropColumn('employment_type');
            $table->dropColumn('preferred_work_conditions');
            $table->dropColumn('accessibility_accommodations');
        });
    }
};
