<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CraneResource;
use App\Models\Crane;
use App\Traits\DisplacementCalculationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CraneController extends Controller
{
    use DisplacementCalculationTrait;

    public function index(Request $request)
    {
        $request->validate([
            'type'         => 'required|in:light-truck,refrigrated,large-freight,tanker-truck',
            'min_capacity' => 'nullable|numeric',
            'latitude'     => 'required|numeric|between:-90,90',
            'longitude'    => 'required|numeric|between:-180,180',
        ]);

        $cranes = Crane::with(['user'])->online()->available()
            ->type($request->type)
            ->when($request->filled('min_capacity'), function ($cranes) use ($request) {
                $cranes->where('capacity', '>=', $request->query('min_capacity'));
            })
            ->get();

        $userLat  = (float) $request->latitude;
        $userLong = (float) $request->longitude;

        $user_ids = [];
        foreach ($cranes as $crane) {
            if ($crane->user) {
                $user_ids[] = $crane->user->id;
            }
        }

        $sorted = [];

        foreach ($user_ids as $userId) {
            $data = Cache::get("navigation_{$userId}");
            if (! $data) continue;
            if (! isset($data['lat'], $data['long'])) continue;
            $distance = $this->haversineDistance($userLat, $userLong, $data['lat'], $data['long']);
            $sorted[] = [
                'user_id'  => $userId,
                'crane'    => $cranes->where('user_id',$userId)->first(),
                'distance' => $distance,
            ];
        }

        usort($sorted, function ($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });

        return response()->json($sorted);
    }


    public function create(Request $request)
    {
        $request->validate([
            'type'              =>'required|in:light-truck,refrigrated,large-freight,tanker-truck',
            'capacity'          =>'required|integer|min:0',
            'license_plate'     =>'required|unique:cranes,license_plate|string|max:50',
            'image'             =>'nullable|image|max:5120',
        ]);

        $image_path = null;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $image_path = $image->store('cranes','public');
        }

        $user = Auth::guard('sanctum')->user();

        if($user->crane()->exists()){
            return response()->json([
                'message'               =>__('Already Has A Registered Vehicle'),
            ],401);
        }

        $crane = Crane::create([
            'type'                      =>$request->type,
            'license_plate'             =>$request->license_plate,
            'image'                     =>$image_path,
            'capacity'                  =>$request->capacity,
            'user_id'                   =>$user->id
        ]);

        return response()->json([
            'crane'                     =>new CraneResource($crane),
            'message'                   =>__('Crane Added Successfully'),
        ],201);
    }

    public function show(Crane $crane)
    {
        $crane->load('user')->loadCount('rides');
        return new CraneResource($crane);
    }

    public function update(Request $request)
    {
        $request->validate([
            'type'              =>'sometimes|required|in:light-truck,refrigrated,large-freight,tanker-truck',
            'capacity'          =>'sometimes|required|integer|min:0',
            'license_plate'     =>'sometimes|required|string|max:50',
            'image'             =>'sometimes|nullable|image|max:5120',
        ]);

        $user = Auth::guard('sanctum')->user();
        $crane = Crane::where('user_id',$user->id)->first();

        if(! $crane){
            return response()->json([
                'message'                   =>__('User Hasn`t Any Cranes Registered'),
            ],401);
        }

        $image_path = $crane->image;
        if($request->hasFile('image')){
            $image = $request->file('image');
            $image_path = $image->store('cranes','public');
            if($crane->image){
                Controller::deleteFile($crane->image);
            }
            $crane->image = $image_path;
        }

        if($request->filled('type')){
            $crane->type = $request->type;
        }

        if($request->filled('license_plate')){
            $crane->license_plate = $request->license_plate;
        }
        if($request->filled('capacity')){
            $crane->capacity = $request->capacity;
        }

        $crane->save();

        return new CraneResource($crane);
    }

    public function delete()
    {
        $user = Auth::guard('sanctum')->user();
        $crane = Crane::where('user_id',$user->id)->first();
        if(! $crane){
            return response()->json([
                'message'                   =>__('User Hasn`t Any Cranes Registered'),
            ],401);
        }
        $crane->delete();

        return response()->json(status:204);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'latitude'                  =>'required|numeric|between:-90,90',
            'longitude'                 =>'required|numeric|between:-180,180',
        ]);

        $user = Auth::guard('sanctum')->user();
        $crane = Crane::where('user_id',$user->id)->first();

        if(! $crane){
            return response()->json([
                'message'                   =>__('User Hasn`t Any Cranes Registered'),
            ]);
        }

        if($crane->is_online){
            if($crane->status == 'in_progress'){
                return response()->json([
                    'message'                   =>__('Can`t Switch To Off when On Progress'),
                ],401);
            }

            Cache::driver('redis')->forget('navigation_'.$user->id);

            $crane->is_online = false;
            $crane->save();

            return response()->json([
                'message'                   =>__('Switched To Off'),
            ]);
        }

        $crane->is_online = true;
        $crane->save();

        Cache::driver('redis')->put('navigation_'.$user->id , [
            'lat'                       =>request()->query('latitude'),
            'long'                      =>request()->query('longitude'),
        ],now()->addMinutes(30));

        return response()->json([
            'message'                   =>__('Switched To On'),
        ]);

    }

    public function updateNav(Request $request)
    {
        $request->validate([
            'latitude'                  =>'required|numeric|between:-90,90',
            'longitude'                 =>'required|numeric|between:-180,180',
        ]);

        $user = Auth::guard('sanctum')->user();

        Cache::driver('redis')->put('navigation_'.$user->id , [
            'lat'                       =>request()->query('latitude'),
            'long'                      =>request()->query('longitude'),
        ],now()->addMinutes(30));

        return response()->json([
            'message'                       =>__('Navigation updated'),
        ]);
    }

    public function listenToNav(Request $request)
    {
        $request->validate([
            'user_id'                   =>'required|integer',
        ]);

        $result = Cache::driver('redis')->get('navigation_'.$request->user_id);

        if (! $result) {
            return response()->json(['message' => __('No navigation data found')], 404);
        }

        return response()->json([
            'nav'                   =>$result,
        ]);
    }

}
