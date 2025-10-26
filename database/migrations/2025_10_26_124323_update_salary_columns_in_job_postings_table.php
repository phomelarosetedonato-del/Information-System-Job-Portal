<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            // Remove the old salary column
            $table->dropColumn('salary');

            // Add new salary columns
            $table->decimal('salary_min', 10, 2)->nullable()->after('is_active');
            $table->decimal('salary_max', 10, 2)->nullable()->after('salary_min');
        });
    }

    public function down()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            $table->string('salary')->nullable();
            $table->dropColumn(['salary_min', 'salary_max']);
        });
    }
};
