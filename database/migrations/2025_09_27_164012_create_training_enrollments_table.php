<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('training_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_training_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'enrolled', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure a user can only enroll once in a training
            $table->unique(['user_id', 'skill_training_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('training_enrollments');
    }
};
