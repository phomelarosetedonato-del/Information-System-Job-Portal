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
        // Login tracking fields (guarded to avoid duplicate column errors during test migrations)
        if (!Schema::hasColumn('users', 'last_login_at')) {
            $table->timestamp('last_login_at')->nullable();
        }
        if (!Schema::hasColumn('users', 'last_login_ip')) {
            $table->string('last_login_ip')->nullable();
        }
        if (!Schema::hasColumn('users', 'login_count')) {
            $table->integer('login_count')->default(0);
        }

        // Optional account status and security fields
        if (!Schema::hasColumn('users', 'is_active')) {
            $table->boolean('is_active')->default(true);
        }
        if (!Schema::hasColumn('users', 'account_locked_until')) {
            $table->timestamp('account_locked_until')->nullable();
        }
        if (!Schema::hasColumn('users', 'failed_login_attempts')) {
            $table->integer('failed_login_attempts')->default(0);
        }
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Drop columns if they exist
        $drop = [];
        foreach (['last_login_at','last_login_ip','login_count','is_active','account_locked_until','failed_login_attempts'] as $col) {
            if (Schema::hasColumn('users', $col)) {
                $drop[] = $col;
            }
        }
        if (!empty($drop)) {
            $table->dropColumn($drop);
        }
    });
}

};
