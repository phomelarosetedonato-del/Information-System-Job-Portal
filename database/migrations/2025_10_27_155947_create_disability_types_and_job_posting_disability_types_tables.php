<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Only create the pivot table if it doesn't exist
        if (!Schema::hasTable('job_posting_disability_types')) {
            Schema::create('job_posting_disability_types', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_posting_id')->constrained()->onDelete('cascade');
                $table->foreignId('disability_type_id')->constrained()->onDelete('cascade');
                $table->timestamps();

                // Add unique constraint with shorter name
                $table->unique(['job_posting_id', 'disability_type_id'], 'job_post_disability_unique');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('job_posting_disability_types');
    }
};
