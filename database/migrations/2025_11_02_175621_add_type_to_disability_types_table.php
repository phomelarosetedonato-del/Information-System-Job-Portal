<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('disability_types', function (Blueprint $table) {
            $table->string('type')->nullable()->after('id'); // or wherever it should be
        });
    }

    public function down()
    {
        Schema::table('disability_types', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
