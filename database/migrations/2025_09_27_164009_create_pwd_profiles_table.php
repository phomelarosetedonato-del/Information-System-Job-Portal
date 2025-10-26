<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pwd_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('disability_type');
            $table->text('skills')->nullable();
            $table->text('qualifications')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->text('special_needs')->nullable();
            $table->boolean('is_employed')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pwd_profiles');
    }
};
