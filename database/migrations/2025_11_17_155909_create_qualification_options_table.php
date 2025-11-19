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
        Schema::create('qualification_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial qualification options
        DB::table('qualification_options')->insert([
            ['name' => 'Elementary Graduate', 'description' => 'Completed elementary education', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'High School Graduate', 'description' => 'Completed high school education', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Senior High School Graduate', 'description' => 'Completed senior high school', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vocational Course Graduate', 'description' => 'Completed vocational training', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'College Undergraduate', 'description' => 'Some college education', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'College Graduate (Bachelor\'s Degree)', 'description' => 'Completed college degree', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Post Graduate (Master\'s/Doctorate)', 'description' => 'Advanced degree holder', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'TESDA Certificate Holder', 'description' => 'Technical Education and Skills Development Authority certification', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Professional License Holder', 'description' => 'Licensed professional', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Special Education Graduate', 'description' => 'Completed special education program', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualification_options');
    }
};
