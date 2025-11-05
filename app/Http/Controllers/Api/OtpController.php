<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    public function resetPassword(Request $request)
    {
        $request->validate([
            'otp'             => 'required|digits:5',
            'password'        => 'required|string|min:4',
            're_password'     => 'required|same:password',
            'remember_token'   => 'required|size:60',
        ]);

        $user = User::where('remember_token', $request->remember_token)
            ->first();

        if (! $user || $user->otp_code !== $request->otp || now()->gt($user->otp_expires_at)) {
            return response()->json([
                'message' => __('Invalid or expired OTP.'),
            ], 403);
        }

        $user->password = bcrypt($request->password);
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json([
            'message' => __('Password has been reset successfully.'),
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email'                 =>'required|email|exists:users,email',
        ]);

        $user = User::where('email',$request->email)->first();

        if(! $user){
            return response()->json([
                'message'               =>__('Email Not Registered'),
                ],403);
        }

        $otp = random_int(10000, 99999);
        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->remember_token = Str::random(60);
        $user->save();
        if (! empty($user->email)) {
            try {
                Mail::to($user->email)->send(new OtpMail($otp, $user));
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email: '.$e->getMessage());
            }
        }

        return response()->json([
            'message' => 'OTP sent',
            'remember_token'        =>$user->remember_token
        ]);
    }

    public function send()
    {
        $user = Auth::guard('sanctum')->user();

        $otp = random_int(10000, 99999);

        $user->otp_code = $otp;
        $user->otp_expires_at = now()->addMinutes(5);
        $user->save();

        if (! empty($user->email)) {
            try {
                Mail::to($user->email)->sendNow(new OtpMail($otp, $user));
            } catch (\Exception $e) {
                Log::error('Failed to send OTP email: '.$e->getMessage());
            }
        }

        return response()->json([
            'message' => __('OTP sent'),
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code'                  => 'required|numeric|between:10000,99999'
        ]);

        $user = FacadesAuth::user();

        if ($user->otp_expires_at < now()) {
            return response()->json([
                'message'                   => __('Expired Code')
            ], 401);
        }

        if ($user->otp_code != $request->code) {
            return response()->json([
                'message'                   => __('Invalid Code')
            ], 401);
        }

        $user->email_verified_at = now();
        $user->save();

        return response()->json([
            'message'               =>__('Emain verified succesfully')
        ]);
    }
}
