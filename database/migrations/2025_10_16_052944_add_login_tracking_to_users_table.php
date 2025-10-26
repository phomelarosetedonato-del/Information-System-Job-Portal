<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Login tracking fields
        $table->timestamp('last_login_at')->nullable();
        $table->string('last_login_ip')->nullable();
        $table->integer('login_count')->default(0);

        // Optional account status and security fields
        $table->boolean('is_active')->default(true);
        $table->timestamp('account_locked_until')->nullable();
        $table->integer('failed_login_attempts')->default(0);
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn([
            'last_login_at',
            'last_login_ip',
            'login_count',
            'is_active',
            'account_locked_until',
            'failed_login_attempts',
        ]);
    });
}

};
