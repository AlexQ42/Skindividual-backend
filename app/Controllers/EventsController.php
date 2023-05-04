<?php

namespace App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class EventsController{
    function getEventById(int $eventId)
    {
        // Validation (ID must not be negative)
        if($eventId < 0) return response(null, 400);

        // Searching event list for queried id
        $events = Cache::get('events', []);
        $result = null;
        foreach ($events as $event) {
            if ($event->id === $eventId) {
                $result = $event;
                break;
            }
        }

        // Error Code if id not found
        if ($result === null) return response(null, 404);


        // sending the result
        return new JsonResponse($result, 200);
    }
}