<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Str;
use App\Models\Checkout;

class StripeController extends Controller
{
    public function createSession(Request $request)
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        try {
            $lineItems = collect($request->input('products'))->map(function ($product) {
                return [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $product['name'],
                        ],
                        'unit_amount' => $product['price'] * 100,
                    ],
                    'quantity' => $product['quantity'],
                ];
            })->toArray();

            // Generate a unique order reference
            $orderReference = Str::uuid();

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => env('STRIPE_SUCCESS_URL'),
                'cancel_url' => env('STRIPE_CANCEL_URL'),
                'customer_email' => $request->input('client_email'),
                'client_reference_id' => $orderReference,
            ]);

            // Save the checkout session in the database
            Checkout::create([
                'session_id' => $session->id,
                'products' => $request->input('products'),
                'status' => 'pending',
                'client_email' => $request->input('client_email'),
                'order_reference' => $orderReference,
            ]);

            return response()->json(['id' => $session->id, 'order_reference' => $orderReference]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
