<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UpdateUserOnlineStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $threshold_in_minutes = 1;
        $threshold = now()->subMinutes($threshold_in_minutes);

        User::where('last_activity', '<', $threshold)->update(['is_online' => false]);

        $this->info('User statuses updated successfully.');
    }
}
