<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

        // Validation User ID
        if($user->id < 0) return new JsonResponse("User ID is not valid", 400);

        //TODO create Tickets and so on

        // Creating new Order
        $order = new Order();
        $order->user_id = $user->id;

        $tickets = $request->order;
        print(implode(", ", $tickets[0]));

        $order->save();

        foreach ($tickets as $ticket)
        {
            for($i = 1; $i<=(int)$ticket["amount"]; $i++) {
                $newTicket = new Ticket();
                $newTicket->order_id = $order->id;
                $newTicket->event_id = (int)$ticket["event_id"];
                $newTicket->save();
            }
        }

        // Sending the result
        return new JsonResponse("Order successfully created", 201);
    }

    // Endpoint 3
    function getOrders(Request $request): JsonResponse
    {
        $user_id = $request->user()->id;

        $result = Order::query()->select()->where('user_id', '=', $user_id)
            ->orderBy('created_at', 'desc');

        $result = $result->paginate(8, null, null, $request->input('page') ?? 1);

        return new JsonResponse($result, 200);
    }
}
