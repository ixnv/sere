<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteExpiredSecrets extends Command
{
    protected $signature = 'secrets:delete-expired';

    protected $description = 'Deletes expired secrets from database';

    public function handle()
    {
        /*
         * here's the catch - we can run it "every second"-ish, but it will definitely overlap and be hella slow with "withoutOverlapping"
         * instead we'll run it every minute, not every second. if one will try to "bruteforce", it will be deleted after 3 attempts (in our model code)
         */

        $this->info('Start deleting expired secrets');

        Db::table('secrets')->where('expires_at', '<', date('Y-m-d H:i:s', time() + 1))->delete();

        $this->info('Deletion done');
    }
}