<?php

namespace App\Console\Commands;

use App\Models\PasswordHistory;
use Illuminate\Console\Command;

class CleanupPasswordHistory extends Command
{
    protected $signature = 'password-history:cleanup {--days=365 : Remove records older than this many days}';
    protected $description = 'Clean up old password history records';

    public function handle()
    {
        $days = $this->option('days');
        $deleted = PasswordHistory::olderThan($days)->delete();

        $this->info("Deleted {$deleted} password history records older than {$days} days.");

        // Log the cleanup
        \Log::info("Password history cleanup: Deleted {$deleted} records older than {$days} days.");

        return Command::SUCCESS;
    }
}
