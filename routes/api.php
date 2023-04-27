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
class SkinEvent {
    public function __construct(
        public string   $name,
        public int      $id,
        public string   $place,
        public DateTime $date,
        public string   $skinType,
        public string   $eventType,
        public float    $price,
        public int      $availableSpots
        //public String $description
        //public Review[] $reviews
    ) {}
}

$events = [
    new SkinEvent('hallo', '1', 'hamburg', (date_create("2023-06-15")), 'dry', 'wellness', 49.99, 30),
    new SkinEvent('ciao', '2', 'munich', (date_create("2023-09-28")), 'oily', 'counselling', 41.99, 40),
    new SkinEvent('hola', '3', 'hamburg', (date_create("2023-06-15")), 'dry', 'wellness',49.99, 30),
    new SkinEvent('hello', '4', 'frankfurt', (date_create("2023-07-28")), 'oily', 'counselling',41.99, 40),
    new SkinEvent('salut', '5', 'dresden', (date_create("2023-04-15")), 'dry', 'course', 24.99, 25),
    new SkinEvent('hi', '6', 'munich', (date_create("2023-09-18")), 'combination', 'counselling', 41.99, 40)
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
        'eventtype' => 'string|nullable',
        'search' => 'string|nullable',
        'sort' => 'string|nullable',
        'page' => ['required', 'integer'],
        'perpage' => ['required', 'integer']
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

    //filter result
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

    //sort result

    //paging
    $page = $request->input('page') ?? 1;
    $perpage = $request->input('perpage') ?? 4;
    $result = array_slice($result, ($request->input('page') - 1) * $request->input('perpage'), $request->input('perpage'));


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