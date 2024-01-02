<?php

namespace App\Service;

use App\Models\Subscriber;
use Illuminate\Support\Carbon;

class SubscriberService
{
    public function __construct(protected ZotloService $service)
    {
    }

    /**
     * @throws \Exception
     */
    public function syncSubscribersFromZotlo(Subscriber $subscriber): void
    {
        $response = $this->service->getSubscriber($subscriber->unique_hash);

        $subscriber->expired_at = Carbon::parse($response->collect('result.profile')->get('expireDate'));
        $subscriber->is_active = $response->collect('result.profile')->get('status') === 'active';
        $subscriber->cancelled_at = Carbon::parse(
            $response->collect('result.profile.cancellation')->get('date')
        );

        $subscriber->save();
    }
}
