<?php

namespace App\Events;

use App\Models\Subscriber;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class SubscriberCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(protected Subscriber $subscriber, protected Collection $collection)
    {
    }

    public function getSubscriber(): Subscriber
    {
        return $this->subscriber;
    }

    /**
     * @return Collection
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }
}
