<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'employer_verified_at')) {
                $table->timestamp('employer_verified_at')->nullable()->after('updated_at');
            }
            if (!Schema::hasColumn('users', 'verification_expires_at')) {
                $table->timestamp('verification_expires_at')->nullable()->after('employer_verified_at');
            }
            if (!Schema::hasColumn('users', 'verification_rejected_reason')) {
                $table->string('verification_rejected_reason')->nullable()->after('verification_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'employer_verified_at')) {
                $table->dropColumn('employer_verified_at');
            }
            if (Schema::hasColumn('users', 'verification_expires_at')) {
                $table->dropColumn('verification_expires_at');
            }
            if (Schema::hasColumn('users', 'verification_rejected_reason')) {
                $table->dropColumn('verification_rejected_reason');
            }
        });
    }
};
