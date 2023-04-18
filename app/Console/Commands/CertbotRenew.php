<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CertbotRenew extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'certbot:renew';

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
        // return Command::SUCCESS;
        $output = shell_exec('sudo certbot renew --dry-run');

        $this->info($output);
    }
}
