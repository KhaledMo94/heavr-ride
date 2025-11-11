<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RidersController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search'                =>'nullable|string|max:100',
            'status'                =>'nullable|in:active,inactive'
        ]);

        $users = User::with([
            'wallet'
        ])->whereDoesntHave('roles')

        ->when($request->filled('search'),function($query) use ($request){
            $search = "%{$request->query('search')}%";
            $query->where('name','like',$search)
                ->orWhere('email','like',$search);

        })
        ->when($request->filled('status'),function($query) use ($request){
            $query->where('status',$request->query('status'));
        })->paginate(10);

        return UserResource::collection($users);
    }

    public function toggle(User $user)
    {
        if ( $user->roles()->exists())
        {
            return response([
                'message'           =>__('Not A Rider'),
            ],401);
        }

        if($user->status =='active'){
            $user->status = 'inactive';
            $user->save();
            $user->tokens()->delete();
            return response([
                'message'           =>__('Deactivated'),
            ]);
        }else{
            $user->status = 'active';
            $user->save();
            return response([
                'message'           =>__('Activated'),
            ]);
        }
    }

    public function update(Request $request , User $user)
    {
        if ( $user->roles()->exists())
        {
            return response([
                'message'           =>__('Not A Rider'),
            ],401);
        }
        $request->validate([
            'name'                  =>'sometimes|required|max:255',
            'email'                 =>'sometimes|required|email|unique:users,email,'.$user->id,
            'phone'                 =>'sometimes|required|regex:/^01[0125][0-9]{8}$/',
            'image'                 =>'sometimes|nullable|image|max:5120',
            'status'                =>'sometimes|required|in:active,inactive',
        ]);


        if($request->hasFile('image')){
            $image = $request->file('image');
            $path = $image->store('users','public');
            if($user->image){
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $path;
        }

        if($request->filled('name')){
            $user->name = $request->name;
        }

        if($request->filled('email')){
            $user->email = $request->email;
        }

        if($request->filled('phone')){
            $user->phone_number = $request->phone;
        }

        if($request->filled('status')){
            $user->status = $request->status;
        }
        $user->save();

        return response()->json([
            'message'               =>__('User Updated')
        ]);
    }

    public function delete(User $user)
    {
        $user->load('wallet');

        if($user->wallet?->balance > 0 || $user->wallet?->freeze_amount >0)
        {
            return response()->json([
                'message'               =>__("Cant Delete Wallet Has {$user->wallet->balance}"),
            ],401);
        }

        if($user->image){
            Controller::deleteFile($user->image);
        }

        $user->delete();

        return response()->json(status:204);
    }
}
