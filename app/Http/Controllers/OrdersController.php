<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    // Endpoint 1
    function getOrderById(int $orderId): JsonResponse
    {
        // Validation (ID must not be negative)
        if($orderId < 0) return new JsonResponse(null, 400);

        // Searching database for queried id, automatic Error Code 404 if id not found
        $result = Order::findOrFail($orderId);

        //pagination
        $result = $result->paginate(8);

        // sending the result
        return new JsonResponse($result, 200);
    }

    //Endpoint 2
    function postOrder(Request $request): JsonResponse
    {
        // Validation User
        if($request-> user_id < 0) return new JsonResponse(null, 400);

        // Creating new Order
        $order = new Order();
        $order->user_id = $request->user_id;
        $order->save();

        // Sending the result
        return new JsonResponse(null, 201);
    }
}
