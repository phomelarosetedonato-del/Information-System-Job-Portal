<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            $table->string('disability_type')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            $table->string('disability_type')->nullable(false)->change();
        });
    }
};
