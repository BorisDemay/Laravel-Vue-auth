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
        try {
            $clientEmail = $this->getClientEmail($request);

            $lineItems = $this->formatLineItems($request->input('products'));

            $orderReference = Str::uuid();
            $session = $this->createStripeSession($lineItems, $orderReference, $clientEmail);

            $this->storeCheckout($session->id, $request->input('products'), $clientEmail, $orderReference);

            return response()->json(['id' => $session->id, 'order_reference' => $orderReference]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get the client email, either from the authenticated user or the request.
     */
    private function getClientEmail(Request $request): ?string
    {
        $clientEmail = $request->user()?->email ?? $request->input('email');
        if (!$clientEmail) {
            throw new \Exception('Client email is required');
        }
        return $clientEmail;
    }

    /**
     * Format the line items for the Stripe session.
     */
    private function formatLineItems(array $products): array
    {
        return collect($products)->map(function ($product) {
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
    }

    /**
     * Create a Stripe session.
     */
    private function createStripeSession(array $lineItems, string $orderReference, ?string $clientEmail = null): Session
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        $sessionData = [
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => env('STRIPE_SUCCESS_URL'),
            'cancel_url' => env('STRIPE_CANCEL_URL'),
            'client_reference_id' => $orderReference,
        ];

        if ($clientEmail) {
            $sessionData['customer_email'] = $clientEmail;
        }

        return Session::create($sessionData);
    }

    /**
     * Store the checkout session in the database.
     */
    private function storeCheckout(string $sessionId, array $products, ?string $clientEmail, string $orderReference): void
    {
        Checkout::create([
            'session_id' => $sessionId,
            'products' => $products,
            'status' => 'pending',
            'client_email' => $clientEmail,
            'order_reference' => $orderReference,
        ]);
    }
}
