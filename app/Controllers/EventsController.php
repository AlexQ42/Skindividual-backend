<?php

namespace App\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Event;

class EventsController
{
    // Endpoint 1
    function getEventById(int $eventId): JsonResponse
    {
        // Validation (ID must not be negative)
        if($eventId < 0) return new JsonResponse(null, 400);

        // Searching database for queried id
        $result = Event::find($eventId);

        // Error Code if id not found
        if ($result === null) return new JsonResponse(null, 404);


        // sending the result
        return new JsonResponse($result, 200);
    }


    // Endpoint 2
    function getEvents(Request $request): JsonResponse
    {
        // Validation of request (Query Params)
        $request->validate([
            'place' => 'string|nullable',
            'startdate' => ['date', 'nullable'],
            'enddate' => ['date', 'nullable'],
            'enddate' => 'exclude_if:startdate, null|after_or_equal:startdate',
            'skintype' => 'string|nullable',
            'eventtype' => 'string|nullable',
            'search' => 'string|nullable',
            'sort' => 'string|nullable',
            'page' => ['required', 'integer'],
            'per-page' => ['required', 'integer']
        ]);


        // Get event array from Cache - TODO: replace with ORM
        $allEvents=Event::all();

        /*if(request('place'))
        {
            $allEvents->where('LOWER(place)', '=', $request->input('place'));
        }*/

        //filter event array, save in result array - TODO: replace with ORM
        $result = [];

        if($request->input('startdate') != null)
        {
            $startDate = date_create($request->input('startdate'));
            $startDate->setTime(0,0,0,0);
        }
        else $startDate = null;

        if($request->input('enddate') != null)
        {
            $endDate = date_create($request->input('enddate'));
            $endDate->setTime(0,0,0,0);
        }
        else $endDate = null;

        foreach ($allEvents as $event) {
            if (($request->input('place') === null || $event->place === $request->input('place')) &&                     // schauen ob "null" oder null zurückkommt
                (
                    ($startDate === null && $endDate === null) ||
                    ($startDate !== null && $endDate === null && $event->date >= $startDate) ||
                    ($endDate !== null && $startDate === null && $event->date <= $endDate) ||
                    ($event->date >= $startDate && $event->date <= $endDate)
                ) &&
                ($request->input('skintype') === null || $event->skinType->value === $request->input('skintype')) &&
                ($request->input('eventtype') === null || $event->eventType === $request->input('eventtype')))
            {
                $result[] = $event;                      //adds event to array
            }
        }

        // sort result
        //TODO: sort with ORM

        // paging: cutting out the list part that is requested
        $page = $request->input('page') ?? 1;
        $perpage = $request->input('per-page') ?? 4;
        $result = array_slice($result, ($page - 1) * $perpage, $perpage);


        // sending the result
        return new JsonResponse($result, 200);
    }
}