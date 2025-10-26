<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CheckPwdTableStructure extends Command
{
    protected $signature = 'pwd:check-structure';
    protected $description = 'Check the actual structure of pwd_profiles table';

    public function handle()
    {
        if (!Schema::hasTable('pwd_profiles')) {
            $this->error('pwd_profiles table does not exist!');
            return 1;
        }

        $this->info('🔍 Checking pwd_profiles table structure...');

        // Get table structure
        $columns = DB::select('DESCRIBE pwd_profiles');

        $this->info('Current columns in pwd_profiles table:');
        foreach ($columns as $column) {
            $this->line(" - {$column->Field} ({$column->Type})");
        }

        // Check if there's any data
        $count = DB::table('pwd_profiles')->count();
        $this->info("Total records: {$count}");

        if ($count > 0) {
            $this->info('Sample data:');
            $sample = DB::table('pwd_profiles')->first();
            dump($sample);
        }

        return 0;
    }
}
