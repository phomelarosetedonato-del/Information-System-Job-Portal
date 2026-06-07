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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject');
            $table->longText('message');
            $table->string('inquiry_type')->default('other'); // job_application_support, employer_partnership, etc.
            $table->boolean('is_read')->default(false);
            $table->timestamp('responded_at')->nullable();
            $table->longText('response_notes')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('is_read');
            $table->index('responded_at');
            $table->index('created_at');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
