<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Permission;
use Laravolt\Avatar\Facade as Avatar;


class AdminController extends Controller
{
    public function index()
    {
        $users = User::role('admin')->get();
        return view('admin.admins.index',compact('users'));
    }

    public function create()
    {
        $permissions = Permission::where('name','<>','provider.moderator')->select('id','name')->get();
        return view('admin.admins.create',compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  =>'required|string|max:255',
            'email'                 =>'required|email|max:255|unique:users,email',
            'password'              =>['required',Password::min(8)],
            'password_confirmation' =>'required|same:password',
            'country_code'          =>'required|max:5',
            'phone_number'          =>'required|max:15',
            'status'                =>'required|in:active,inactive',
            'permissions'           =>'required|array',
            'permissions.*'         =>'required|exists:permissions,id',
            'image'                 =>'nullable|image|max:5120',
        ]);

        if (User::where('country_code', $request->country_code)->where('phone_number', $request->phone_number)->exists()) {
            return response()->json([
                'message'                   => __('phone number already taken'),
            ], 403);
        }

        $image_path = '';
        if ($request->hasFile('image') && !$request->remove_image) {
            $image = $request->file('image');
            $image_path = $image->store('users', 'public');
        } else {
            $avatar = Avatar::create($request->name)->toBase64();
            $image_content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
            $filename = 'users/' . uniqid() . '.png';
            Storage::disk('public')->put($filename, $image_content);
            $image_path = $filename;
        }

        $user = User::create([
            'name'                      => $request->name,
            'email'                     => $request->email,
            'country_code'              => $request->country_code,
            'phone_number'              => $request->phone_number,
            'image'                     => $image_path,
            'email_verified_at'         => now(),
            'phone_verified_at'         => now(),
            'password'                  => Hash::make($request->password),
        ]);

        $user->permissions()->attach($request->permissions);
        $user->assignRole('admin');

        return redirect()->route('admins.admins.index')
        ->with('success',__('Admin Created Successfully'));
    }

    public function edit(User $admin)
    {
        $permissions = Permission::where('name','<>','provider.moderator')->select('id','name')->get();
        return view('admin.admins.edit',compact('permissions','admin'));
    }

    public function update(Request $request , User $admin)
    {
        $request->validate([
            'name'                      =>'required|string|max:255',
            'email'                     =>['required','email','max:255',Rule::unique('users','email')->ignore($admin->id)],
            'password'                  =>['nullable','required_with:password_confirmation',Password::min(8)],
            'password_confirmation'     =>'nullable|required_with:password|same:password',
            'country_code'              =>'required|max:5',
            'phone_number'              =>'required|max:15',
            'status'                    =>'required|in:active,inactive',
            'permissions'               =>'required|array',
            'permissions.*'             =>'required|exists:permissions,id',
            'image'                     =>'nullable|image|max:5120',
        ]);

        if (User::where('country_code', $request->country_code)
            ->where('phone_number', $request->phone_number)
            ->where('id','<>',$admin->id)
            ->exists()) {
            return redirect()->back()
            ->with('error',__('Phone Number Already Taken'));
        }

        $image_path = $admin->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image_path = $image->store('users', 'public');
            if ($admin->image && !$this->isAvatarImage($admin->image)) {
                Controller::deleteFile($admin->image);
            }
            $admin->image = $image_path;
        } elseif (is_null($request->image) && $this->isAvatarImage($admin->image)) {
            $name = $request->name ?? $admin->name;
            $avatar = Avatar::create($name)->toBase64();
            $image_content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
            $filename = 'admins/' . uniqid() . '.png';
            Storage::disk('public')->put($filename, $image_content);
            $admin->image = $filename;
        }

        $admin->update([
            'name'                  =>$request->name,
            'email'                 =>$request->email,
            'password'              =>Hash::make($request->password),
            'country_code'          =>$request->country_code,
            'phone_number'          =>$request->phone_number,
            'status'                =>$request->status,
            'image'                 =>$admin->image,
        ]);

        $admin->permissions()->sync($request->permissions);

        return redirect()->route('admins.admins.index')
        ->with('success',__('Admin Updated Successfully'));

    }

    public function destroy(User $admin)
    {
        if($admin->image)
        {
            Storage::delete($admin->image);
        }
        $admin->delete();
        return redirect()->route('admins.admins.index')
        ->with('success',__('Admin Deleted Successfully'));
    }

    private function isAvatarImage(string $imagePath): bool
    {
        return preg_match('/users\/[a-f0-9]{13,}\.png$/', $imagePath);
    }
}
