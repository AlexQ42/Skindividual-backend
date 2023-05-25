<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

// Route 1 - GET Event List + Filter, Sorting, Paging
Route::get('/events', [EventsController::class, 'getEvents']);

// Route 2 - GET event by event ID
Route::get('/events/{event_id}', [EventsController::class, 'getEventById']);

// Route 3 - GET single order by order ID
Route::get('/orders/{order_id}', [OrdersController::class, 'getOrderById']);

// Route 4 - POST order
Route::post('/orders', [OrdersController::class, 'postOrder']);

// Route 5 - GET all orders of current user
Route::get('/orders', [OrdersController::class, 'getOrders']);

// Route 6 - GET current user
Route::get('/users', [UserController::class, 'getUser']);

// Route 7 - POST User (register)
Route::post('/users', [UserController::class, 'postUser']);

// Route 8 - DELETE User (delete account)
Route::delete('/users', [UserController::class, 'deleteUser']);

// Route 9 - PATCH User
Route::patch('/users', [UserController::class, 'patchUser']);

// Route 10 - POST review
Route::post('/events/{event_id}/reviews', [ReviewController::class, 'createReview']);

// Route 11 - DELETE review
Route::delete('/events/{event_id}/reviews/{review_id}', [ReviewController::class, 'destroyReview']);

// Routes 12-14 - Authorization
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'login')->name('login');   // needed for auth via middleware
    Route::post('/login', 'login');                       // needed for logging in
    Route::post('/logout', 'logout');
});
