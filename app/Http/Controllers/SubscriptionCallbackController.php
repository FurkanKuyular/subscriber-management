<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionCallbackRequest;
use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class SubscriptionCallbackController extends Controller
{
    public function store(SubscriptionCallbackRequest $request): JsonResponse
    {
        $collection = $request->collect('parameters.profile');

        $subscriber = Subscriber::query()
            ->where('unique_hash', $collection->get('subscriberId'))
            ->firstOrFail();

        DB::beginTransaction();

        $subscriber->expired_at = Carbon::parse($collection->get('expireDate'));
        $subscriber->is_active = $collection->get('status') === 'active';
        $subscriber->cancelled_at = optional(Carbon::parse($collection->get('cancellation.date')));

        $subscriber->save();

        DB::commit();

        return response()->json([], Response::HTTP_CREATED);
    }
}
