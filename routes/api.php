<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Class Skinevent
class Skinevent {
    public function __construct(
        public string   $name,
        public int      $id,
        public string   $place,
        public DateTime $date,
        public string   $skinType,
        public string   $eventType
    ) {}
}

$events = [
    new Skinevent('hallo', '3', 'hamburg', (date_create("2023-06-15")), 'dry', 'wellness'),
    new Skinevent('ciao', '7', 'munich', (date_create("2023-09-28")), 'oily', 'counselling')
];
Cache::put('events', $events);


// GET all Events + Filter
Route::get('/events', function (Request $request)
{

    //Validation
    $request->validate([
        'place' => 'string|nullable',
        'startdate' => ['date', 'nullable'],
        'enddate' => ['date', 'nullable'],
        'enddate' => 'exclude_if:startdate, null|after_or_equal:startdate',
        'skintype' => 'string|nullable',
        'eventtype' => 'string|nullable'
    ]);

    //Getting Result
    $listedEvents=Cache::get('events', []);
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


    foreach ($listedEvents as $event) {
        if (($request->input('place') === null || $event->place === $request->input('place')) &&                     // schauen ob "null" oder null zurückkommt
            (
                ($startDate === null && $endDate === null) ||
                ($startDate !== null && $endDate === null && $event->date >= $startDate) ||
                ($endDate !== null && $startDate === null && $event->date <= $endDate) ||
                ($event->date >= $startDate && $event->date <= $endDate)
            ) &&
        ($request->input('skintype') === null || $event->skinType === $request->input('skintype')) &&
        ($request->input('eventtype') === null || $event->eventType === $request->input('eventtype')))
        {
            $result[] = $event;                      //adds event to array
        }
    }
    return new JsonResponse($result, 200);
});


// GET event by ID
Route::get('/events/{eventId}', function (int $eventId)
{
    //Validation
    if($eventId < 0) return response(null, 400);

    //Getting Result
    $events = Cache::get('events', []);
    $result = null;
    foreach ($events as $event) {
        if ($event->id === $eventId) {
            $result = $event;
            break;
        }
    }
    if ($result === null) return response(null, 404);
    return new JsonResponse($result, 200);
});
