<?php

namespace App\Http\Controllers;

use App\Events\SubscriberCreated;
use App\Http\Requests\SubscriptionStoreRequest;
use App\Models\Subscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionController extends Controller
{
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
}
