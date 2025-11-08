<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SendTestNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:test {user_id} {type=approved} {note?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test employer verification notification to a user (approved|rejected|kept)';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $type = strtolower($this->argument('type'));
        $note = $this->argument('note') ?? 'Test notification from local run';

        $user = User::find($userId);
        if (!$user) {
            $this->error("User with id {$userId} not found.");
            return 1;
        }

        try {
            $user->notifyVerificationStatusChanged($type, $note);
            $this->info("Notification of type '{$type}' sent to user {$user->email} (id: {$user->id}).");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send notification: ' . $e->getMessage());
            return 1;
        }
    }
}
