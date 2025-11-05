<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Laravolt\Avatar\Facade as Avatar;

class ProfileController extends Controller
{
    public function editProfile()
    {
        return view('admin.profile-edit');
    }

public function updateProfile(Request $request)
{
    $user = $request->user();
    
    if($user->hasAnyRole(['super-admin', 'admin'])){
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,' . $user->id,
            'country_code'  => 'nullable|string|max:5',
            'phone_number'  => 'nullable|string|max:255|unique:users,phone_number,' . $user->id,
            'image'         => 'nullable|image|max:2048',
            'remove_image'  => 'nullable|boolean',
        ]);
    }else{
        $request->validate([
            'name'          => 'required|string|max:255',
            'image'         => 'nullable|image|max:2048',
            'remove_image'  => 'nullable|boolean',
        ]);
    }


    $updateData = [
        'name'          => $request->name,
        'email'         => $request->email ?? $user->email,
        'country_code'  => $request->country_code ?? $user->country_code,
        'phone_number'  => $request->phone_number ?? $user->phone_number,
    ];

    // 🔹 Handle image removal
    if ($request->boolean('remove_image')) {
        if ($user->image) {
            Controller::deleteFile($user->image);
        }
        $updateData['image'] = $this->generateAvatar($request->name);
    }
    // 🔹 Handle new image upload
    elseif ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('users', 'public');

        if ($user->image) {
            Controller::deleteFile($user->image);
        }

        $updateData['image'] = $imagePath;
    }
    // 🔹 If user has no image and no upload/remove
    elseif (! $user->image) {
        $updateData['image'] = $this->generateAvatar($request->name);
    }

    $user->update($updateData);

    return redirect()->route('dashboard')
        ->with('success', __('Profile updated successfully'));
}

    private function generateAvatar($name)
    {
        $avatarBase64 = Avatar::create($name)->toBase64();
        $imageContent = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $avatarBase64));
        $filename = 'users/' . uniqid() . '.png';
        Storage::disk('public')->put($filename, $imageContent);
        return $filename;
    }


    public function editPassword()
    {
        return view('admin.password-edit');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password'                  => 'required',
            'new_password'                  => ['required', Password::min(8)],
            're_new_password'               => 'required|same:new_password',
        ]);

        $user = Auth::user();

        $check = Hash::check($request->old_password, $user->password);

        if (! $check) {
            return redirect()->back()
                ->with('error', __('Invalid Password'));
        }

        $user->password = Hash::make($request->new_password);

        return redirect()->route('dashboard')
            ->with('success', __('Password Changed Successfully'));
    }

    private function isAvatarImage(string $imagePath): bool
    {
        return preg_match('/users\/[a-f0-9]{13,}\.png$/', $imagePath);
    }
}
