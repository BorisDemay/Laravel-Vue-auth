<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Checkout;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        $endpointSecret = env('STRIPE_WEBHOOK_SECRET');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            // Verify webhook signature
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);

            // Handle the event
            switch ($event->type) {
                case 'checkout.session.completed':
                    $this->processCheckoutSessionCompleted($event->data->object);
                    break;

                case 'payment_intent.succeeded':
                    $this->processPaymentSucceeded($event->data->object);
                    break;

                default:
                    Log::warning('Unhandled event type', ['type' => $event->type]);
            }

            return response()->json(['status' => 'success'], 200);
        } catch (SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Webhook handling error', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Webhook handling error'], 500);
        }
    }

    protected function processCheckoutSessionCompleted($session)
    {
        Log::info('Processing checkout session completed', ['session_id' => $session->id]);

        Checkout::where('session_id', $session->id)->update(['status' => 'success']);
    }

    protected function processPaymentSucceeded($paymentIntent)
    {
        Log::info('Processing payment succeeded', ['payment_intent_id' => $paymentIntent->id]);
    }

    protected function processStripeEvent($event)
    {
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            Checkout::where('session_id', $session->id)->update([
                'status' => 'success',
            ]);
        }

        if ($event->type === 'checkout.session.expired') {
            $session = $event->data->object;

            Checkout::where('session_id', $session->id)->update([
                'status' => 'failed',
            ]);
        }
    }
}
