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
        Schema::create('accommodation_options', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed initial accommodation options
        DB::table('accommodation_options')->insert([
            ['name' => 'Wheelchair Accessible Workplace', 'description' => 'Physical accessibility for wheelchair users', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Flexible Work Schedule', 'description' => 'Adjustable working hours', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Work from Home Option', 'description' => 'Remote work arrangement', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Assistive Technology/Software', 'description' => 'Screen readers, voice recognition, etc.', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sign Language Interpreter', 'description' => 'Communication support for deaf employees', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Accessible Restroom Facilities', 'description' => 'Modified bathroom facilities', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Reserved Parking Space', 'description' => 'Designated parking near entrance', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ergonomic Workstation', 'description' => 'Adjusted desk, chair, and equipment', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Modified Job Tasks', 'description' => 'Adjusted work responsibilities', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Additional Break Time', 'description' => 'Extra rest periods as needed', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Visual Aids & Large Print Materials', 'description' => 'Enhanced visual materials', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Quiet Work Environment', 'description' => 'Reduced noise workspace', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Personal Care Assistant Support', 'description' => 'Assistance with personal needs', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Job Coach/Mentor Support', 'description' => 'On-site training and guidance', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transportation Assistance', 'description' => 'Support with commuting', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_options');
    }
};
