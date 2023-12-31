<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SubscriptionCallbackController;
use App\Http\Controllers\SubscriptionCardController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('subscribers', SubscriptionController::class)->except('update');
    Route::apiResource('subscribers.cards', SubscriptionCardController::class)->only('index');
});

Route::apiResource('subscribers/callback', SubscriptionCallbackController::class)->only('store');
