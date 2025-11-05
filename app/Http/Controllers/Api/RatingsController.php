<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Crane;
use App\Models\Review;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingsController extends Controller
{
    public function index(Crane $crane)
    {
        $query = Review::whereHas('ride',function($q) use ($crane){
            $q->where('crane_id',$crane->id);
        })->paginate(10);

        return ReviewResource::collection($query);
    }

    public function rate(Request $request , Ride $ride)
    {
        $request->validate([
            'rating'                =>'required|integer|min:0|max:5',
            'comment'               =>'nullable|string',
        ]);

        $user = Auth::guard('sanctum')->user();

        if(Review::where('ride_id',$ride->id)->exists()){
            return response()->json([
                'message'                   =>__('Already Reviewed'),
            ],401);
        }

        if($ride->user_id != $user->id){
            return response()->json([
                'message'                   =>__('Must Be A Ride Rider'),
            ],401);
        }

        $crane = Crane::where('id',$ride->crane_id)->first();
        if(! $crane){
            return response()->json([
                'message'                   =>__('Can`t Review'),
            ],401);
        }

        $review = Review::create([
            'comment'               =>$request->comment,
            'rating'                =>$request->rating,
            'ride_id'               =>$ride->id,
        ]);
        $crane->ratings_count = $crane->ratings_count +1 ;
        $crane->avg_rating =($crane->avg_rating + $request->rating) / $crane->ratings_count;
        $crane->save();

        return new ReviewResource($review);
    }


}
