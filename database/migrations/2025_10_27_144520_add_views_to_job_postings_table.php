<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_postings', 'views')) {
                $table->unsignedBigInteger('views')->default(0)->after('application_deadline');
            }
        });
    }

    public function down()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            if (Schema::hasColumn('job_postings', 'views')) {
                $table->dropColumn('views');
            }
        });
    }
};
