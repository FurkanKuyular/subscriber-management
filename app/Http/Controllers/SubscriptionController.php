<?php

namespace App\Http\Controllers;

use App\Events\SubscriberCreated;
use App\Http\Requests\SubscriberDestroyRequest;
use App\Http\Requests\SubscriptionStoreRequest;
use App\Http\Resources\SubscriberCollection;
use App\Models\Subscriber;
use App\Service\ZotloService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
    public function index(): SubscriberCollection
    {
        return new SubscriberCollection(auth()->user()->subscribers()->paginate());
    }

    public function store(SubscriptionStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();

        $subscriber = Subscriber::query()->create($request->only([
                'card_number',
                'card_owner',
                'expire_year',
                'expire_month',
                'cvv',
            ]) + [
                'unique_hash' =>
                    md5(implode($request->only(['card_number', 'expire_year', 'expire_month', 'cvv'], ',')))
            ]);

        auth()->user()->subscribers()->attach($subscriber->id);

        SubscriberCreated::dispatch(
            $subscriber,
            $request->collect(['x_ip', 'discount_percent', 'quantity', 'force_3ds', 'use_wallet', 'language'])
        );

        DB::commit();

        return response()->json([], Response::HTTP_CREATED);
    }

    /**
     * @throws \Exception
     */
    public function show(int $subscriberId, ZotloService $service): JsonResponse
    {
        $subscriber = auth()->user()->subscribers()->where('id', $subscriberId)->firstOrFail();

        return response()->json($service->getSubscriber($subscriber->unique_hash));
    }

    /**
     * @throws \Exception
     */
    public function destroy(int $subscriberId, SubscriberDestroyRequest $request, ZotloService $service): JsonResponse
    {
        $subscriber = auth()->user()->subscribers()->where('id', $subscriberId)->firstOrFail();

        $service->cancelSubscriber(
            $subscriber->unique_hash,
            $request->get('reason'),
            $request->get('force_cancellation')
        );

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
