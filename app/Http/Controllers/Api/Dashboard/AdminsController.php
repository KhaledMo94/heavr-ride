<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Facade as Avatar;
use Spatie\Permission\Models\Permission;

class AdminsController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search'                => 'nullable|string|max:100',
            'status'                => 'nullable|in:active,inactive'
        ]);

        $users = User::with([
            'wallet',
            'permissions'
        ])->role('admin')

            ->when($request->filled('search'), function ($query) use ($request) {
                $search = "%{$request->query('search')}%";
                $query->where('name', 'like', $search)
                    ->orWhere('email', 'like', $search);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->query('status'));
            })->paginate(10);

        return UserResource::collection($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'phone'         => 'nullable|regex:/^01[0125][0-9]{8}$/',
            'password'      => 'required|string|min:8',
            'permissions'   => 'required|array',
            'permissions.*' => 'exists:permissions,name',
            'image'         => 'nullable|image|max:5120',
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
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
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'phone_number'     => $validated['phone'] ?? null,
            'password'  => bcrypt($validated['password']),
            'image'     => $image_path,
            'email_verified_at' =>now(),
            'phone_verified_at' =>now(),
        ]);

        $user->assignRole('admin');

        $user->syncPermissions($validated['permissions']);

        return response()->json([
            'message' => __('User created successfully.'),
            'user'    => new UserResource($user->load(['roles', 'permissions'])),
        ], 201);
    }

    public function toggle(User $user)
    {
        if ( !$user->hasRole('admin'))
        {
            return response([
                'message'           =>__('Not An Admin'),
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

    public function update(Request $request, User $user)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name'          => 'sometimes|required|string|max:255',
            'email'         => 'sometimes|required|email|unique:users,email,' . $user->id,
            'phone'         => 'nullable|regex:/^01[0125][0-9]{8}$/',
            'password'      => 'nullable|string|min:8|confirmed',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
            'image'         => 'nullable|image|max:5120',
        ]);

        $image_path = null;
        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $image_path = $request->file('image')->store('users', 'public');
        } elseif (!$user->image && $request->filled('name')) {
            $avatar = Avatar::create($request->name)->toBase64();
            $image_content = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatar));
            $filename = 'users/' . uniqid() . '.png';
            Storage::disk('public')->put($filename, $image_content);
            $image_path = $filename;
        } else {
            $image_path = $user->image;
        }

        $user->update([
            'name'      => $validated['name'] ?? $user->name,
            'email'     => $validated['email'] ?? $user->email,
            'phone_number'     => $validated['phone'] ?? $user->phone,
            'password'  => !empty($validated['password']) ? bcrypt($validated['password']) : $user->password,
            'image'     => $image_path,
        ]);

        if (isset($validated['permissions'])) {
            $user->syncPermissions($validated['permissions']);
        }

        return response()->json([
            'message' => __('User updated successfully.'),
            'user'    => new UserResource($user->load(['roles', 'permissions'])),
        ]);
    }

    public function destroy(User $user)
    {
        if (Auth::guard('sanctum')->id() === $user->id) {
            return response()->json([
                'message' => __('You cannot delete your own account.'),
            ], 403);
        }

        if ($user->hasRole('super-admin')) {
            return response()->json([
                'message' => __('You cannot delete a super admin.'),
            ], 403);
        }

        if (! $user->hasRole('admin')) {
            return response()->json([
                'message' => __('Only admins can be deleted.'),
            ], 403);
        }

        $user->syncRoles([]);
        $user->syncPermissions([]);

        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $user->delete();

        return response()->json([
            'message' => __('User deleted successfully.'),
        ]);
    }

    public function permissions()
    {
        $permissions = Permission::pluck('name')->toArray();

        return response()->json([
            'permissions' => $permissions,
        ]);
    }
}
