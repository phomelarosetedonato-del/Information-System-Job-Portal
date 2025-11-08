<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('pwd_profiles')) {
            return;
        }

        // Add nullable foreign key column for disability type
        Schema::table('pwd_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('pwd_profiles', 'disability_type_id')) {
                $table->unsignedBigInteger('disability_type_id')->nullable()->after('disability_type');
            }
        });

        // Backfill disability_type_id from existing disability_type text if possible
        try {
            $types = DB::table('disability_types')->pluck('id', 'type');

            if ($types->isNotEmpty()) {
                $profiles = DB::table('pwd_profiles')->select('id', 'disability_type')->get();
                foreach ($profiles as $p) {
                    if (empty($p->disability_type)) continue;
                    // Try exact match first
                    $matchId = $types->get($p->disability_type) ?? null;
                    // Try case-insensitive alternatives
                    if (!$matchId) {
                        $found = $types->filter(function ($value, $key) use ($p) {
                            return strcasecmp($key, $p->disability_type) === 0;
                        })->first();
                        $matchId = $found ?: null;
                    }

                    if ($matchId) {
                        DB::table('pwd_profiles')->where('id', $p->id)->update(['disability_type_id' => $matchId]);
                    }
                }
            }
        } catch (\Exception $e) {
            // noop - seeding may not have run yet during early migrations
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('pwd_profiles')) {
            return;
        }

        Schema::table('pwd_profiles', function (Blueprint $table) {
            if (Schema::hasColumn('pwd_profiles', 'disability_type_id')) {
                $table->dropColumn('disability_type_id');
            }
        });
    }
};
