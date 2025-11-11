<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RideResource;
use App\Models\Crane;
use App\Models\Ride;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use App\Services\FirebasePushNotifications;
use App\Traits\DisplacementCalculationTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Throwable;

class RideController extends Controller
{
    use DisplacementCalculationTrait;

    public function rideSave(Request $request, FirebasePushNotifications $notification)
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

        $user = Auth::guard('sanctum')->user();

        if ($user->wallet->balance < $request->fare) {
            return response()->json([
                'message'                   => __('Charge Your Wallet')
            ], 401);
        }

        $crane_owner = User::whereHas('crane', function ($q) use ($request) {
            $q->where('id', $request->crane_id);
        })->first();

        if (!$crane_owner->fcm_token) {
            return response()->json([
                'message'                   => __('Cant Notify Crane Owner')
            ], 401);
        }

        $ride = Ride::create([
            'user_id'                   => $user->id,
            'crane_id'                  => $request->crane_id,
            'start_latitude'            => $request->start_latitude,
            'start_longitude'           => $request->start_longitude,
            'end_latitude'              => $request->end_latitude,
            'end_longitude'             => $request->end_longitude,
            'started_at'                => Carbon::parse($request->started_at),
            'fare'                      => $request->fare,
            'status'                    => 'pending',
        ]);

        //TODO: uncomment
        // $notification->sendPushNotification($crane_owner->fcm_token, 'new ride request', 'A New Ride Reservation Requested', [
        //     'ride_id'                       => $ride->id,
        // ]);

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
                $query->whereHas('crane', function ($q) use ($request) {
                    $q->where('type', $request->query('type'));
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
                $query->whereHas('crane', function ($q) use ($request) {
                    $q->where('type', $request->query('type'));
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->query('status'));
            })->paginate(10);

        return RideResource::collection($rides);
    }

    public function acceptOrRefuseRide(Request $request, FirebasePushNotifications $notification, Ride $ride)
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
        if ($request->action == 'accepted') {
            $wallet = Wallet::where('user_id', $ride->user_id)->first();

            if ($wallet->balance < $ride->fare) {
                return response()->json([
                    'message'               => __('Insufficient Balance Of Owner Wallet'),
                ], 401);
            }
        }

        try {
            DB::beginTransaction();
            $ride->save();
            if ($ride->action == 'accepted') {
                $wallet->balance = $wallet->balance - $ride->fare;
                $wallet->freeze_amount = $wallet->freeze_amount + $ride->fare;
                $wallet->save();
            }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            return $th;
        }
        //TODO: uncomment
        // $notifiable = User::find($ride->user_id);
        // if ($ride->action == 'accepted' && $notifiable->fcm_token) {
        //     $notification->sendPushNotification(
        //         $notifiable->fcm_token,
        //         'Your Ride Has Been Accepted',
        //         'Your Ride Has Been Accepted By Driver, And Ride Fare Frozed From Your Wallet Balance',
        //         ['ride_id' => $ride->id]
        //     );
        // } else {
        //     $notification->sendPushNotification(
        //         $notifiable->fcm_token,
        //         'Your Ride Has Been Refused',
        //         'Your Ride Has Been Accepted By Selected Driver, You Can Choose Another One Or Raise Your Fare',
        //         ['ride_id' => $ride->id]
        //     );
        // }
        return response()->json([
            'message'               => __("Ride ") . ($ride->status == 'accepted' ? __('Accepted') : __('Refused')),
        ]);
    }

    public function cancelRide(Request $request, FirebasePushNotifications $notification,  Ride $ride)
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

        try {
            DB::beginTransaction();
            $ride->save();
            $wallet = Wallet::where('user_id', $ride->user_id)->first();
            $wallet->balance = $wallet->balance + $ride->fare;
            $wallet->freeze_amount = $wallet->freeze_amount - $ride->fare;
            $wallet->save();
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }
        //TODO: uncomment
        // $notifiable = User::whereHas('crane',function($q) use ($ride){
        //     $q->where('id',$ride->crane_id);
        // })->first();

        // if($notifiable->fcm_token){
        //     $notification->sendPushNotification($notifiable->fcm_token ,
        //     'Your Ride Cancelled By Owner',
        //     'Your Ride Cancelled By Request Owner',
        //     [
        //         'id'                =>$ride->id,
        //     ]);
        // }

        return response()->json([
            'message'               => __("Ride Cancelled"),
        ]);
    }

    public function markRideAsInProgress(Request $request, FirebasePushNotifications $notification, Ride $ride)
    {
        $request->validate([
            'latitude'                  => 'required|numeric|between:-90,90',
            'longitude'                  => 'required|numeric|between:-180,180',
        ]);

        $user = Auth::guard('sanctum')->user();

        if (! in_array($ride->status, ['accepted'])) {
            return response()->json([
                'message'               => __('Must Be Accepted First'),
            ], 401);
        }

        $ride->load('crane.user');

        if ($ride->crane->user->id != $user->id) {
            return response()->json([
                'message'               => __('Not The Driver'),
            ], 401);
        }

        $distance = $this->haversineDistance($ride->start_latitude, $ride->start_longitude, $request->latitude, $request->longitude);

        if ($distance > 0.5) {
            return response()->json([
                'message'               => __('Must Be Near Starting Point By At Least 500m '),
            ], 401);
        }

        $ride->status = 'in_progress';
        try {
            DB::beginTransaction();
            $ride->save();
            Crane::where('id', $ride->crane_id)->update([
                'status'            => 'in_progress',
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }

        // TODO: uncomment
        // $notifiable = User::find($ride->user_id);
        // if ($notifiable->fcm_token) {
        //     $notification->sendPushNotification(
        //         $notifiable->fcm_token,
        //         'Your Ride Is Starting Now',
        //         'Driver Now Is In Your Marked Place Of Begaining The Ride',
        //         [
        //             'id'            => $ride->id,
        //         ],
        //     );
        // }

        return response()->json([
            'message'               => __("Ride Status Changed To In Progress"),
        ]);
    }

    public function markRideAsCompleted(Request $request,FirebasePushNotifications $notification, Ride $ride)
    {
        $request->validate([
            'latitude'                  => 'required|numeric|between:-90,90',
            'longitude'                  => 'required|numeric|between:-180,180',
        ]);

        $user = Auth::guard('sanctum')->user();

        if (!in_array($ride->status, ['in_progress'])) {
            return response()->json([
                'message'               => __('Must Be In Progress'),
            ], 401);
        }

        $ride->load('crane.user');

        if ($ride->crane->user->id != $user->id) {
            return response()->json([
                'message'               => __('Not The Driver'),
            ], 401);
        }

        $distance = $this->haversineDistance($ride->start_latitude, $ride->start_longitude, $request->latitude, $request->longitude);

        if ($distance < 0.3) {
            return response()->json([
                'message'               => __('Must Be Near End Point By At Least 300m '),
            ], 401);
        }

        $ride->status = 'completed';
        $ride->completed_at = now();
        $wallet_crane_owner = Wallet::where('user_id', $user->id)->first();
        $wallet_user = Wallet::where('user_id', $ride->user_id)->first();

        try {
            DB::beginTransaction();
            $ride->save();

            $wallet_crane_owner->balance = $wallet_crane_owner->balance + $ride->fare;
            $wallet_crane_owner->save();

            $wallet_user->freeze_amount = $wallet_user->freeze_amount - $ride->fare;
            $wallet_user->save();

            Transaction::create([
                'wallet_id'             => $wallet_crane_owner->id,
                'user_name'             => $user->name,
                'type'                  => 'deposit',
                'amount'                => $ride->fare,
                'status'                => 'completed',
            ]);

            Transaction::create([
                'wallet_id'             => $wallet_user->id,
                'user_name'             => User::find($ride->user_id)->name,
                'type'                  => 'withdrawal',
                'amount'                => $ride->fare,
                'status'                => 'completed',
            ]);

            Crane::where('id', $ride->crane_id)->update([
                'status'            => 'available',
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return $th;
        }

        // TODO: uncomment
        // $notifiable = User::find($ride->user_id);
        // if ($notifiable->fcm_token) {
        //     $notification->sendPushNotification(
        //         $notifiable->fcm_token,
        //         'Your Ride Ended',
        //         'Driver Now Is In Your Marked Place Of Ending The Ride',
        //         [
        //             'id'            => $ride->id,
        //         ],
        //     );
        // }

        return response()->json([
            'message'               => __("Ride Status Changed To Completed"),
        ]);
    }
}
