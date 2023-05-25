<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function createReview(Request $request): JsonResponse
    {
        $request->validate([
            'value' => 'required|integer|max:5',
            'event_id' => 'required|integer',
        ]);

        $user = $request->user();

        $review = new Review();
        $review->value = $request->value;
        $review->user_id = $user->id;
        $review->event_id = $request->event_id;
        $review->save();


        return new JsonResponse(null, 201);
    }


    public function destroyReview($event_id, $review_id): JsonResponse
    {
        $review = Review::find($review_id);

        if(!$review)
        {
            return response()->json(null,404);
        }

        if($review->event_id === (int)($event_id))
        {
            $review->delete();
        }
        else
        {
            return new JsonResponse("Wrong event_id for this review.", 400);
        }

        return new JsonResponse("Successfully deleted", 204);
    }

}
