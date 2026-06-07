<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            if (!Schema::hasColumn('job_postings', 'contact_email')) {
                $table->string('contact_email')->nullable()->after('application_deadline');
            }
            if (!Schema::hasColumn('job_postings', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('job_postings', function (Blueprint $table) {
            if (Schema::hasColumn('job_postings', 'contact_email')) {
                $table->dropColumn('contact_email');
            }
            if (Schema::hasColumn('job_postings', 'contact_phone')) {
                $table->dropColumn('contact_phone');
            }
        });
    }
};
