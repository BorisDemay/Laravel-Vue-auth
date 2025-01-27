<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class OrderController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $orders = Order::with(['products'])
            ->latest()
            ->paginate(10);

        if ($request->expectsJson()) {
            return response()->json(['orders' => $orders]);
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Request $request, $uuid)
    {
        $order = Order::where('uuid', $uuid)
            ->with('products')
            ->firstOrFail();

        if ($request->expectsJson()) {
            return response()->json($order);
        }

        return view('admin.orders.show', compact('order'));
    }
}
