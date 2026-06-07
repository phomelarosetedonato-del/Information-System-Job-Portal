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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_qualified')->default(false)->after('role')->comment('Whether applicant is qualified');
            $table->decimal('qualification_score', 5, 2)->nullable()->after('is_qualified')->comment('Qualification score out of 100');
            $table->timestamp('qualified_at')->nullable()->after('qualification_score')->comment('When applicant was marked as qualified');
            $table->boolean('available_for_jobs')->default(false)->after('qualified_at')->comment('Whether applicant is available for jobs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_qualified', 'qualification_score', 'qualified_at', 'available_for_jobs']);
        });
    }
};
