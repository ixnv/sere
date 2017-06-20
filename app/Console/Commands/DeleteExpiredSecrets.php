<?php

namespace App\Console\Commands;

use App\Models\Secret;
use Illuminate\Console\Command;

class DeleteExpiredSecrets extends Command
{
    protected $signature = 'secrets:delete-expired';

    protected $description = 'Deletes expired secrets from database';

    public function handle()
    {
        $this->info('start deleting expired secrets');

        Secret::where('expires_at', '<', date('Y-m-d H:i:s', time()))->limit(1e6)->chunk(1000, function ($secrets) {
            Secret::destroy($secrets->pluck('uuid')->toArray());
            $this->info('processed: ' . $secrets->count());
        });

        $this->info('deletion done');
    }
}