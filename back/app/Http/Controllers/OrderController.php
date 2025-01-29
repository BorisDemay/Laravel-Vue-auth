<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderDetails;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            // For API: only show user's orders
            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            $orders = $user->orders()->with('products')->latest()->get();
            return response()->json(['orders' => $orders]);
        }

        // For web admin: show all orders without restrictions
        $orders = Order::with('products')->latest()->paginate(10);
        return view('admin.orders.index', ['orders' => $orders]);
    }

    public function show(Request $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)->with('products')->firstOrFail();

        if ($request->expectsJson()) {
            return response()->json($order);
        }

        return view('admin.orders.show', ['order' => $order]);
    }

    public function updateStatus(Request $request, $uuid)
    {
        $request->validate([
            'status' => 'required|in:pending,completed'
        ]);

        $order = Order::where('uuid', $uuid)->firstOrFail();
        $order->update(['status' => $request->status]);

        if ($request->expectsJson()) {
            return response()->json($order);
        }

        return redirect()->route('orders.index')
            ->with('success', 'Statut de la commande mis à jour avec succès');
    }

    public function sendEmail(Request $request, $uuid)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $order = Order::where('uuid', $uuid)->with('products')->firstOrFail();
        
        try {
            Mail::to($order->client_email)->send(new OrderDetails($order, $request->message));
            
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Email envoyé avec succès']);
            }
            
            return redirect()->back()->with('success', 'Email envoyé avec succès');
        } catch (\Exception $e) {
            Log::error('Email sending failed', ['error' => $e->getMessage()]);
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Échec de l\'envoi de l\'email'], 500);
            }
            
            return redirect()->back()->with('error', 'Échec de l\'envoi de l\'email');
        }
    }
}
