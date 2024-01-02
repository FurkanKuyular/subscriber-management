<?php

namespace App\Jobs;

use App\Models\Subscriber;
use App\Service\SubscriberService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncSubscribersFromZotlo implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(protected Collection $collection)
    {
    }

    public function __invoke(SubscriberService $service): void
    {
        $this->collection->each(
            /*** @throws \Exception */
            function (Subscriber $subscriber) use ($service) {
            $service->syncSubscribersFromZotlo($subscriber);
        });
    }
}
