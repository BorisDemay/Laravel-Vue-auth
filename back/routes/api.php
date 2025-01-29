<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\OrderController;

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::get('/products', [ProductController::class, 'index']);

    Route::apiResource('products', ProductController::class);
    // you need to connect this route to your Stripe webhook endpoint, check the checkout.session.completed and checkout.session.expired events

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/create-checkout-session', [StripeController::class, 'createSession']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{uuid}', [OrderController::class, 'show']);
    });

    Route::get('/product/{id}', [ProductController::class, 'show']);

    Route::post('/create-checkout-session-no-email', [StripeController::class, 'createSession']);

    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
});

Route::prefix('api')->middleware('auth:sanctum')->group(function () {
    // Orders routes
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{uuid}', [OrderController::class, 'show']);
});
