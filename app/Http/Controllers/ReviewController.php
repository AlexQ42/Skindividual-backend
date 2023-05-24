<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use View;

class ReviewController extends Controller
{
    public function __invoke()
    {
        //
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function createReview(Request $request): JsonResponse
    {
        $request->validate([
            'value' => 'required|integer|max:5',
            'user_id' => 'required|integer',
            'event_id' => 'required|integer',
        ]);

        error_log(gettype($request->value));

        $review = new Review();
        $review->value = $request->value;
        $review->user_id = $request->user_id;
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
