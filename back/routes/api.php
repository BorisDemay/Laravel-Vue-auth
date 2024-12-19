<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StripeWebhookController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');

    Route::apiResource('products', ProductController::class);

    Route::post('/create-checkout-session', [StripeController::class, 'createSession']);

    // you need to connect this route to your Stripe webhook endpoint, check the checkout.session.completed and checkout.session.expired events
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
});
