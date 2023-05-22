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

        // Searching database for queried id, automatic Error Code 404 if id not found
        $result = Event::findOrFail($eventId);

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

        // create query for Database
        $query=Event::query();

        // apply place and enum filters
        if(request('place')) $query->whereRaw('LOWER(events.place) LIKE ?', ['%' . strtolower($request->input('place')) . '%']);
        if(request('eventtype')) $query->whereRaw('LOWER(events.eventtype) LIKE ?', ['%' . strtolower($request->input('eventtype')) . '%']);
        if(request('skintype')) $query->whereRaw('LOWER(events.skintype) LIKE ?', ['%' . strtolower($request->input('skintype')) . '%']);

        // convert query parameter dates to DateTime
        if($request->input('startdate') != null && $request->input('startdate') > today())
        {
            $startDate = date_create($request->input('startdate'));
            $startDate->setTime(0,0,0,0);
        }
        else $startDate = today();

        if($request->input('enddate') != null)
        {
            $endDate = date_create($request->input('enddate'));
            $endDate->setTime(0,0,0,0);
        }
        else $endDate = null;

        // apply date filters
        if($startDate !== null) $query->where('date', '>=', $startDate);
        if($endDate !== null) $query->where('date', '<=', $endDate);

        $query->orderBy('date');

        if ($request->input('sort') === 'price') $query->orderBy('price', 'desc');

        $result = $query->get();

        if ($request->input('sort') === 'reviews')
        {
                // TODO sort by reviews
                //Select avg(value) from reviews r join events e group by event_id;// TODO falsch
                //Select * from events order by (SELECT AVG(r.value) FROM events e join reviews r on e.id = r.event_id GROUP BY event_id)
                /*$subquery =
                    Event::selectRaw("SELECT e.id, AVG(r.value) FROM events e join reviews r on e.id = r.event_id GROUP BY event_id");
                $query->orderBy($subquery);*/

            $result->sort(
                function () {

                }
            );
        }


        // pagination with ORM
        /*$perpage = $request->input('per-page') ?? 4;
        if ($request->input('page') !==null) $query->paginate($perpage);*/

        // pagination with php: cutting out the list part that is requested
        $page = $request->input('page') ?? 1;
        $perPage = $request->input('per-page') ?? 4;
        $resultArray = $result->toArray();
        $resultArray = array_slice($resultArray, ($page - 1) * $perPage, $perPage);


        // sending the result
        return new JsonResponse($resultArray, 200);
    }
}