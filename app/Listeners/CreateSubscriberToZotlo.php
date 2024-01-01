<?php

namespace App\Listeners;

use App\Events\SubscriberCreated;
use App\Service\ZotloService;

class CreateSubscriberToZotlo
{
    public function __construct(protected ZotloService $service)
    {
    }

    /**
     * @throws \Exception
     */
    public function __invoke(SubscriberCreated $event): void
    {
        $this->service->createSubscriber($event->getSubscriber(), $event->getCollection());
    }
}
