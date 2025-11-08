<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->text('rejection_reason')->nullable()->after('admin_notes');
            $table->foreignId('reviewed_by')->nullable()->after('rejection_reason')->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->timestamp('status_updated_at')->nullable()->after('reviewed_at');
        });
    }

    public function down()
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn(['rejection_reason', 'reviewed_by', 'reviewed_at', 'status_updated_at']);
        });
    }
};
