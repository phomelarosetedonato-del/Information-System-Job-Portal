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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Personal Information
            $table->string('surname');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->date('date_of_birth');
            $table->enum('sex', ['male', 'female', 'prefer_not_to_say']);
            $table->string('mobile_number');
            $table->string('email_address');
            $table->string('province');
            $table->text('complete_address')->nullable();

            // Professional Summary
            $table->text('professional_summary')->nullable();
            $table->text('career_objective')->nullable();

            // Education
            $table->string('educational_attainment'); // e.g., High School, Bachelor's, Master's
            $table->string('course')->nullable(); // Degree/Course
            $table->string('school_name')->nullable();
            $table->string('school_address')->nullable();
            $table->year('year_graduated')->nullable();
            $table->json('additional_education')->nullable(); // Array of additional education entries

            // Eligibility/Certifications
            $table->json('eligibility')->nullable(); // Array of licenses/certifications

            // Work Experience
            $table->json('work_experience')->nullable(); // Array of work experiences

            // Training/Seminars
            $table->json('trainings')->nullable(); // Array of trainings attended

            // Skills
            $table->json('skills')->nullable(); // Array of skills
            $table->json('languages')->nullable(); // Array of languages

            // Documents
            $table->string('profile_photo')->nullable(); // Path to profile photo
            $table->json('personal_documents')->nullable(); // Array of document paths (PDFs)
            $table->json('supporting_documents')->nullable(); // Array of supporting document paths

            // Application Letter
            $table->text('application_letter')->nullable();

            // Publishing Settings
            $table->boolean('is_published')->default(false); // Public as job seeker
            $table->boolean('is_searchable')->default(true); // Allow employers to search
            $table->string('visibility')->default('private'); // private, employers_only, public

            // Resume Template & Styling
            $table->string('template')->default('professional'); // Template style
            $table->json('customization')->nullable(); // Font, colors, layout preferences

            // Metadata
            $table->integer('views_count')->default(0);
            $table->timestamp('last_updated_at')->nullable();
            $table->boolean('is_complete')->default(false);
            $table->integer('completion_percentage')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('user_id');
            $table->index('is_published');
            $table->index('visibility');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
