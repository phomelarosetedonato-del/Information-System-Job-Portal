<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('disability_types', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('disability_types', 'name')) {
                $table->string('name')->after('id');
            }

            if (!Schema::hasColumn('disability_types', 'description')) {
                $table->text('description')->nullable()->after('name');
            }

            if (!Schema::hasColumn('disability_types', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('description');
            }
        });
    }

    public function down()
    {
        Schema::table('disability_types', function (Blueprint $table) {
            // We don't drop columns in down() to be safe
            // You can manually drop them if needed
        });
    }
};
