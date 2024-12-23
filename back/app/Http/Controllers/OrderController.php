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
        $user = $request->user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $orders = $user->orders()->with('products')->get();

        return response()->json(['orders' => $orders]);
    }

    public function show($uuid)
    {
        $order = Order::where('uuid', $uuid)->with('products')->firstOrFail();

        $this->authorize('view', $order);

        return response()->json($order);
    }
}
