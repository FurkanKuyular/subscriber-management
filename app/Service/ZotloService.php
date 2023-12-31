<?php

namespace App\Service;

use App\Models\Subscriber;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ZotloService
{
    /**
     * @throws \Exception
     */
    public function createSubscriber(Subscriber $subscriber, Collection $collection): bool
    {
        /** @var Response $response */
        $response = Http::zotlo()->post('/v1/payment/credit-card', [
            'cardNo' => $subscriber->card_number,
            'cardOwner' => $subscriber->card_owner,
            'expireMonth' => $subscriber->expire_month ?
                str($subscriber->expire_month)->padLeft(2, '0')->value() :
                str($subscriber->expire_month)->toString(),
            'expireYear' => str($subscriber->expire_year)->substr(-2)->value(),
            'cvv' => $subscriber->cvv,
            'packageId' => config('zotlo.package_id'),
            'language' => str($collection->get('language'))->upper()->value(),
            'platform' => config('app.name'),
            'subscriberId' => $subscriber->unique_hash,
            'subscriberPhoneNumber' => $subscriber->user()->phone,
            'subscriberFirstname' => $subscriber->user()->first_name,
            'subscriberLastname' => $subscriber->user()->last_name,
            'subscriberIpAddress' => $collection->get('x_ip'),
            'subscriberCountry' => $subscriber->user()->country_iso_code,
            'subscriberEmail' => $subscriber->user()->email,
            'redirectUrl' => config('app.url') . '/api/callback',
            'discountPercent' => $collection->get('discount_percent'),
            'quantity' => $collection->get('quantity'),
            'force3ds' => $collection->get('force_3ds'),
            'useWallet' => $collection->get('use_wallet'),
        ]);

        if ($response->failed()) {
            logger()->error($response->body());

            throw new \Exception();
        }

        $subscriber->expired_at = Carbon::parse($response->collect('result.profile')->get('expireDate'));
        $subscriber->is_active = $response->collect('result.profile')->get('status') === 'active';

        $subscriber->save();

        return true;
    }

    /**
     * @throws \Exception
     */
    public function getSubscriber(string $subscriberUniqueHash): Response
    {
        $response = Http::zotlo()->get('/v1/subscription/profile', [
            'subscriberId' => $subscriberUniqueHash,
            'packageId' => config('zotlo.package_id'),
        ]);

        if ($response->failed()) {
            logger()->error($response->body());

            throw new \Exception();
        }

        return $response;
    }

    /**
     * @throws \Exception
     */
    public function cancelSubscriber(string $subscriberUniqueHash, string $reason, bool $forceCancellation): bool
    {
        $response = Http::zotlo()->post('/v1/subscription/cancellation', [
            'subscriberId' => $subscriberUniqueHash,
            'packageId' => config('zotlo.package_id'),
            'cancellationReason' => $reason,
            'force' => $forceCancellation,
        ]);

        if ($response->failed()) {
            logger()->error($response->body());

            throw new \Exception();
        }

        return true;
    }

    /**
     * @throws \Exception
     */
    public function getSubscriberCards(string $subscriberUniqueHash): Response
    {
        $response = Http::zotlo()->get('/v1/subscription/card-list', [
            'subscriberId' => $subscriberUniqueHash,
        ]);

        if ($response->failed()) {
            logger()->error($response->body());

            throw new \Exception();
        }

        return $response;
    }
}
