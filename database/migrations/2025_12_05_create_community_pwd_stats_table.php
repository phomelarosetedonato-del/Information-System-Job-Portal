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
        Schema::create('community_pwd_stats', function (Blueprint $table) {
            $table->id();
            $table->year('year')->comment('Year of the statistics');
            $table->string('disability_type')->comment('Type of disability');
            $table->integer('unemployed_count')->default(0)->comment('Number of unemployed PWD');
            $table->integer('employed_count')->default(0)->comment('Number of employed PWD');
            $table->timestamps();

            // Unique constraint on year + disability_type
            $table->unique(['year', 'disability_type']);

            // Index on year for faster queries
            $table->index('year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_pwd_stats');
    }
};
