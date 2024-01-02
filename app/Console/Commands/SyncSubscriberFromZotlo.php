<?php

namespace App\Console\Commands;

use App\Jobs\SyncSubscribersFromZotlo;
use App\Models\Subscriber;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;
use Symfony\Component\Console\Command\Command as CommandAlias;

class SyncSubscriberFromZotlo extends Command
{
    protected $signature = 'app:sync-subscriber-from-zotlo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync subscribers from zotlo subscriber service';

    public function handle()
    {
        $chunkSize = 5000;

        Subscriber::query()->chunk($chunkSize, function (Collection $collection) {
            $processedJobs = [];
            $processedCount = 0;
            $processedJobs[] = new SyncSubscribersFromZotlo($collection);

            $processedCount++;

            if ($processedCount === 1) {
                Bus::batch($processedJobs)->dispatch();
            }
        });

        return CommandAlias::SUCCESS;
    }
}
