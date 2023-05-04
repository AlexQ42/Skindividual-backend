<?php

use App\Controllers\EventsController;
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

//Enum SkinType
enum SkinType: string
{
    case Dry = 'dry';
    case Oily = 'oily';
    case Combination = 'combination';
    case Normal = 'normal';
}


//Class SkinEvent
class SkinEvent {
    public function __construct(
        public string   $name,
        public int      $id,
        public string   $place,
        public DateTime $date,
        public SkinType $skinType,
        public string   $eventType,
        public float    $price,
        public int      $availableSpots,
        public String   $description
        //public Review[] $reviews
    ) {}
}

//create event-array (dummy) for cache - TODO: replace with ORM
$events = [
    new SkinEvent('hallo', 1, 'hamburg', (date_create("2023-06-15")), SkinType::Dry, 'wellness', 49.99, 30, 'bla'),
    new SkinEvent('ciao', 2, 'munich', (date_create("2023-09-28")), SkinType::Oily, 'counselling', 41.99, 40, 'bla'),
    new SkinEvent('hola', 3, 'hamburg', (date_create("2023-06-15")), SkinType::Dry, 'wellness',49.99, 30, 'bla'),
    new SkinEvent('hello', 4, 'frankfurt', (date_create("2023-07-28")), SkinType::Dry, 'counselling',41.99, 40, 'bla'),
    new SkinEvent('salut', 5, 'dresden', (date_create("2023-04-15")), SkinType::Combination, 'course', 24.99, 25, 'bla'),
    new SkinEvent('hi', 6, 'munich', (date_create("2023-09-18")), SkinType::Oily, 'counselling', 41.99, 40, 'bla')
];
Cache::put('events', $events);


// Route 1 - GET Event List + Filter, Sorting, Paging
Route::get('/events', function (Request $request)
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
    $allEvents=Cache::get('events', []);


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
});


// Route 2 - GET event by ID
Route::get('/events/{eventId}', [EventsController::class, 'getEventById']);
