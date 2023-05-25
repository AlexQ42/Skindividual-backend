<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrdersController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // Endpoint 1
    function getOrderById(int $order_id): JsonResponse
    {
        // Validation (ID must not be negative)
        if($order_id < 0) return new JsonResponse(null, 400);

        // Searching database for queried id, automatic Error Code 404 if id not found
        $result = Order::findOrFail($order_id);

        //pagination
        $result = $result->paginate(8);

        // sending the result
        return new JsonResponse($result, 200);
    }

    //Endpoint 2
    function postOrder(Request $request): JsonResponse
    {
        $user = $request->user();

        // Validation User ID
        if($user->id < 0) return new JsonResponse("User ID is not valid", 400);

        // Creating new Order
        $order = new Order();
        $order->user_id = $user->id;
        $order->save();

        // Sending the result
        return new JsonResponse("Order successfully created", 201);
    }

    // Endpoint 3
    function getOrders(Request $request): JsonResponse
    {
        $user_id = $request->user()->id;
        error_log($user_id);

        $result = Order::query()->select()->where('user_id', '=', $user_id)
            ->orderBy('created_at', 'desc')->with('tickets');

        $result = $result->paginate(8, null, null, $request->input('page') ?? 1);

        return new JsonResponse($result, 200);
    }
}
