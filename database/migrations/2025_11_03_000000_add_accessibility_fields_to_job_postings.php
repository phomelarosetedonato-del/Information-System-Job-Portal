<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_postings', 'is_remote')) {
                $table->boolean('is_remote')->default(false)->after('employment_type');
            }

            if (!Schema::hasColumn('job_postings', 'provides_accommodations')) {
                $table->boolean('provides_accommodations')->default(false)->after('is_remote');
            }

            if (!Schema::hasColumn('job_postings', 'accessibility_features')) {
                $table->text('accessibility_features')->nullable()->after('provides_accommodations');
            }

            if (!Schema::hasColumn('job_postings', 'assistive_technology')) {
                $table->text('assistive_technology')->nullable()->after('accessibility_features');
            }
        });
    }

    public function down()
    {
        Schema::table('job_postings', function (Blueprint $table) {
            if (Schema::hasColumn('job_postings', 'assistive_technology')) {
                $table->dropColumn('assistive_technology');
            }
            if (Schema::hasColumn('job_postings', 'accessibility_features')) {
                $table->dropColumn('accessibility_features');
            }
            if (Schema::hasColumn('job_postings', 'provides_accommodations')) {
                $table->dropColumn('provides_accommodations');
            }
            if (Schema::hasColumn('job_postings', 'is_remote')) {
                $table->dropColumn('is_remote');
            }
        });
    }
};
