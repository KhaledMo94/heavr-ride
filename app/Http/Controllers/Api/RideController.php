<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RideResource;
use App\Models\Crane;
use App\Models\Ride;
use App\Traits\DisplacementCalculationTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RideController extends Controller
{
    use DisplacementCalculationTrait;

    public function rideSave(Request $request)
    {
        $request->validate([
            'crane_id'                  => 'required|exists:cranes,id',
            'start_latitude'            => 'required|between:-90,90',
            'end_latitude'              => 'required|between:-90,90',
            'start_longitude'           => 'required|between:-180,180',
            'end_longitude'             => 'required|between:-180,180',
            'starts_at'                 => ['required', 'date', 'after:now'],
            'fare'                      => 'required|numeric|min:0',
        ]);

        $crane = Crane::findOr($request->crane_id, function () {
            return response()->json([
                'message'                   => __('Crane Not Found')
            ], 404);
        });

        if ($crane->status != 'available') {
            return response()->json([
                'message'                   => __('Crane Not Available')
            ], 401);
        }

        if (! $crane->is_online) {
            return response()->json([
                'message'                   => __('Crane Not Online To Accept Requests')
            ], 401);
        }

        $ride = Ride::create([
            'user_id'                   => Auth::guard('sanctum')->id(),
            'crane_id'                  => $request->crane_id,
            'start_latitude'            => $request->start_latitude,
            'start_longitude'           => $request->start_longitude,
            'end_latitude'              => $request->end_latitude,
            'end_longitude'             => $request->end_longitude,
            'started_at'                => Carbon::parse($request->started_at),
            'fare'                      => $request->fare,
        ]);

        //todo: Notify Crane user
        //todo: Deduct from wallet to freeze

        return new RideResource($ride->load([
            'crane.user',
            'user'
        ]));
    }

    public function myRidesAsRider(Request $request)
    {
        $request->validate([
            'type'              => 'nullable|in:light-truck,refrigrated,large-freight,tanker-truck',
            'status'            => 'nullable|in:pending,accepted,refused,in_progress,completed,cancelled',
        ]);
        $user = Auth::guard('sanctum')->user();

        $rides = Ride::with(['crane.user'])->where('user_id', $user->id)
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->whereHas('crane', function ($q) use ($request){
                    $q->where('type',$request->query('type'));
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->query('status'));
            })->paginate(10);

        return RideResource::collection($rides);
    }

    public function myRidesAsDriver(Request $request)
    {
        $request->validate([
            'type'              => 'nullable|in:light-truck,refrigrated,large-freight,tanker-truck',
            'status'            => 'nullable|in:pending,in_progress,accepted,refused,completed,cancelled',
        ]);

        $user = Auth::guard('sanctum')->user();

        $rides = Ride::with('user')
            ->whereHas('crane.user', function ($query) use ($user) {
                $query->where('id', $user->id);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->whereHas('crane', function ($q) use ($request){
                    $q->where('type',$request->query('type'));
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->query('status'));
            })->paginate(10);

        return RideResource::collection($rides);
    }

    public function acceptOrRefuseRide(Request $request, Ride $ride)
    {
        $request->validate([
            'action'            => 'required|in:accepted,refused',
        ]);

        $user = Auth::guard('sanctum')->user();
        $user->load('crane');

        if (!$user->crane) {
            return response()->json([
                'message'               => __('Can`t Accept Or Refuse'),
            ], 401);
        }

        if ($user->crane->id != $ride->crane_id) {
            return response()->json([
                'message'               => __('Can`t Accept Or Refuse'),
            ], 401);
        }

        if ($ride->status != 'pending') {
            return response()->json([
                'message'               => __('Can`t Accept Or Refuse'),
            ], 401);
        }

        $ride->status = $request->action;
        $ride->save();

        //TODO:: return from freeze to balance if not accepted

        return response()->json([
            'message'               => __("Ride ") . ($ride->status == 'accepted' ? __('Accepted') : __('Refused')),
        ]);
    }

    public function cancelRide(Request $request, Ride $ride)
    {
        $user = Auth::guard('sanctum')->user();

        if ($ride->user_id != $user->id) {
            return response()->json([
                'message'               => __('Can`t Cancel Others Ride'),
            ], 401);
        }

        if (!in_array($ride->status, ['pending', 'accepted'])) {
            return response()->json([
                'message'               => __('Pending And Accepted Are Only Allowed'),
            ], 401);
        }

        $ride->status = 'cancelled';
        $ride->save();

        //TODO:: return from freeze ( if no charges applied)

        return response()->json([
            'message'               => __("Ride Cancelled"),
        ]);
    }

    public function markRideAsInProgress(Request $request , Ride $ride)
    {
        $request->validate([
            'latitude'                  =>'required|numeric|between:-90,90',
            'longitude'                  =>'required|numeric|between:-180,180',
        ]);

        $user = Auth::guard('sanctum')->user();

        if (! in_array($ride->status, ['accepted'])) {
            return response()->json([
                'message'               => __('Must Be Accepted First'),
            ], 401);
        }

        $ride->load('crane.user');

        if($ride->crane->user->id != $user->id){
            return response()->json([
                'message'               => __('Not The Driver'),
            ], 401);
        }

        $distance = $this->haversineDistance($ride->start_latitude , $ride->start_longitude , $request->latitude , $request->longitude);

        if($distance > 0.5){
            return response()->json([
                'message'               => __('Must Be Near Starting Point By At Least 500m '),
            ], 401);
        }

        $ride->status = 'in_progress';
        $ride->save();

        return response()->json([
            'message'               => __("Ride Status Changed To In Progress"),
        ]);
    }

    public function markRideAsCompleted(Request $request , Ride $ride)
    {
        $request->validate([
            'latitude'                  =>'required|numeric|between:-90,90',
            'longitude'                  =>'required|numeric|between:-180,180',
        ]);

        $user = Auth::guard('sanctum')->user();

        if (!in_array($ride->status, ['in_progress'])) {
            return response()->json([
                'message'               => __('Must Be In Progress'),
            ], 401);
        }

        $ride->load('crane.user');

        if($ride->crane->user->id != $user->id){
            return response()->json([
                'message'               => __('Not The Driver'),
            ], 401);
        }

        $distance = $this->haversineDistance($ride->start_latitude , $ride->start_longitude , $request->latitude , $request->longitude);

        if($distance < 0.3){
            return response()->json([
                'message'               => __('Must Be Near End Point By At Least 300m '),
            ], 401);
        }

        $ride->status = 'completed';
        $ride->save();

        //to do :: deduct from user wallet and put to driver after

        return response()->json([
            'message'               => __("Ride Status Changed To Completed"),
        ]);
    }
}
