<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            // Only add columns that don't exist
            $columnsToAdd = [
                'disability_severity' => ['type' => 'string', 'nullable' => true, 'after' => 'disability_type'],
                // Add other columns here with the same pattern if needed
            ];

            foreach ($columnsToAdd as $columnName => $options) {
                if (!Schema::hasColumn('pwd_profiles', $columnName)) {
                    $column = $table->{$options['type']}($columnName);
                    if ($options['nullable'] ?? false) {
                        $column->nullable();
                    }
                    if (isset($options['after'])) {
                        $column->after($options['after']);
                    }
                }
            }
        });
    }

    public function down()
    {
        Schema::table('pwd_profiles', function (Blueprint $table) {
            $table->dropColumn(['disability_severity' /*, add other columns here */]);
        });
    }
};
