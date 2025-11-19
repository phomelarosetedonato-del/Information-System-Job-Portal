<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('skill_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial skill options
        DB::table('skill_options')->insert([
            ['name' => 'Computer Skills (MS Office, Email)', 'description' => 'Proficiency in computer applications', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Communication Skills', 'description' => 'Verbal and written communication abilities', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Customer Service', 'description' => 'Customer interaction and support skills', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Data Entry', 'description' => 'Fast and accurate data input skills', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bookkeeping & Accounting', 'description' => 'Financial record keeping skills', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Graphic Design', 'description' => 'Visual design and creative skills', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Web Development', 'description' => 'Website creation and programming', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Content Writing', 'description' => 'Writing and content creation skills', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Social Media Management', 'description' => 'Managing social media platforms', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Handicraft & Arts', 'description' => 'Artistic and craft skills', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sewing & Tailoring', 'description' => 'Garment making and alteration skills', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cooking & Baking', 'description' => 'Food preparation skills', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Teaching & Training', 'description' => 'Educational and instructional skills', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Language Skills', 'description' => 'Multilingual communication abilities', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Technical Support', 'description' => 'IT troubleshooting and support', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skill_options');
    }
};
