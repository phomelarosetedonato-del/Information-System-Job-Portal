<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('success_stories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('job_title');
            $table->string('company');
            $table->text('story');
            $table->string('disability_type')->nullable();
            $table->integer('salary_increase')->nullable();
            $table->text('previous_situation')->nullable();
            $table->text('achievement')->nullable();
            $table->text('key_takeaways')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_published')->default(false);
            $table->integer('views')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('success_stories');
    }
};
