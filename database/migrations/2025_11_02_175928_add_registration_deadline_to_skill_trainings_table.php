<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('skill_trainings', function (Blueprint $table) {
            $table->timestamp('registration_deadline')->nullable()->after('end_date');
        });
    }

    public function down()
    {
        Schema::table('skill_trainings', function (Blueprint $table) {
            $table->dropColumn('registration_deadline');
        });
    }
};
