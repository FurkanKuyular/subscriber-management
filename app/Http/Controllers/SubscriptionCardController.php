<?php

namespace App\Http\Controllers;

use App\Service\ZotloService;
use Illuminate\Http\JsonResponse;

class SubscriptionCardController extends Controller
{
    /**
     * @throws \Exception
     */
    public function index(int $subscriberId, ZotloService $service): JsonResponse
    {
        $subscriber = auth()->user()->subscribers()->where('id', $subscriberId)->firstOrFail();

        return response()->json($service->getSubscriberCards($subscriber->unique_hash)->json());
    }
}
