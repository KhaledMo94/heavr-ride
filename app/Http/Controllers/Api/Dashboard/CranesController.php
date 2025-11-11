<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\CraneResource;
use App\Models\Crane;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CranesController extends Controller
{
    public $types = ['light-truck','refrigrated','large-freight','tanker-truck'];

    public function index(Request $request)
    {
        $request->validate([
            'type'              =>['nullable',Rule::in($this->types)],
            'min_capacity'      =>'nullable|numeric|min:0',
            'max_capacity'      =>'nullable|numeric|min:0',
            'license_plate'     =>'nullable|string',
            'status'            =>'nullable|in:available,in_progress',
            'is_online'         =>'nullable|in:0,1',
            'min_rating'        =>'nullable|integer|min:0|max:5',
        ]);

        $cranes = Crane::with('user')
            ->withCount('rides')
            ->when($request->filled('type'),function($q) use ($request){
                $q->where('type',$request->query('type'));
            })
            ->when($request->filled('min_capacity'),function($q) use ($request){
                $q->where('capacity','>',$request->query('min_capacity'));
            })
            ->when($request->filled('max_capacity'),function($q) use ($request){
                $q->where('capacity','<',$request->query('max_capacity'));
            })
            ->when($request->filled('license_plate'),function($q) use ($request){
                $search = "%{$request->query('license_plate')}%";
                $q->where('license_plate','like',$search);
            })
            ->when($request->filled('status'),function($q) use ($request){
                $q->where('status',$request->query('status'));
            })
            ->when($request->filled('is_online'),function($q) use ($request){
                $q->where('is_online',$request->query('is_online'));
            })
            ->when($request->filled('min_rating'),function($q) use ($request){
                $q->where('avg_rating','>',$request->query('min_rating'));
            })
            ->paginate(10);

        return CraneResource::collection($cranes);
    }

    public function show(Crane $crane)
    {
        $crane->load('user')->loadCount('rides');
        return new CraneResource($crane);
    }

    public function update(Request $request , Crane $crane)
    {
        $request->validate([
            'type'                          =>['sometimes','required',Rule::in($this->types)],
            'capacity'                      =>['sometimes','required','numeric','integer','min:0',],
            'licence_plate'                 =>['sometimes','required','string','max:150'],
            'image'                         =>['sometimes','nullable','image','max:5120'],
        ]);

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
        if($request->filled('capacity')){
            $crane->capacity = $request->capacity;
        }
        if($request->filled('license_plate')){
            $crane->license_plate = $request->license_plate;
        }

        $crane->save();

        return response()->json(status:204);
    }

    public function delete(Crane $crane)
    {
        if($crane->image){
            Controller::deleteFile($crane->image);
        }

        $crane->delete();
        return response()->json(status:204);
    }
}
