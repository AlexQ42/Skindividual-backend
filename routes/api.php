<?php

use App\Controllers\EventsController;
use App\Http\Controllers\ReviewController;
use App\Models\SkinType;
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
    ) {}
}
class Reviews {
    public function __construct(
    public int $value,
    ){}
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
Route::get('/events', [EventsController::class, 'getEvents']);


// Route 2 - GET event by ID
Route::get('/events/{eventId}', [EventsController::class, 'getEventById']);

//Route 1 - POST review
Route::post('/events/{event_id}/reviews', [ReviewController::class, 'createReview']);
//Route 2 - DELETE review
Route::delete('/events/{event_id}/reviews/{review_id}', [ReviewController::class, 'destroyReview']);

