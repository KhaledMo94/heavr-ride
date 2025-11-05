<?php

use App\Http\Controllers\Api\CraneController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\RatingsController;
use App\Http\Controllers\Api\RideController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Support\Facades\Route;


Route::post('register', [UserAuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('login', [UserAuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('forgot-password', [OtpController::class, 'forgotPassword'])->middleware('throttle:30,1');
Route::post('reset-password', [OtpController::class, 'resetPassword'])->middleware('throttle:30,1');

Route::group([
    'middleware'            =>['auth:sanctum','throttle:100,1'],
],function(){
    Route::post('logout',[UserAuthController::class  , 'logout']);
    Route::get('user',[UserAuthController::class , 'user']);
    Route::post('send-verification-code',[OtpController::class , 'send']);
    Route::post('verify-code',[OtpController::class , 'verify']);
    Route::put('update-tokens',[UserAuthController::class , 'updateTokens']);
    Route::group([
        'middleware'            =>[
            'user-unbanned'
        ],
    ],function(){
        Route::put('update',[UserAuthController::class , 'update']);
        Route::delete('delete-account',[UserAuthController::class , 'deleteAccount']);
        //----------------------------------------------------------
        Route::get('get-available-online-cranes',[CraneController::class , 'index']);
        Route::get('single-crane/{crane}',[CraneController::class , 'show']);
        //-----------------------------------------------------------
        Route::get('my-transactions',[TransactionsController::class , 'index']);
        Route::post('charge-my-wallet',[TransactionsController::class , 'chargeWallet']);

        Route::middleware('role:driver')->group(function(){
            Route::post('create-crane',[CraneController::class , 'create']);
            Route::put('update-crane',[CraneController::class , 'update']);
            Route::put('crane-toggle',[CraneController::class , 'toggle']);
            Route::delete('delete-crane',[CraneController::class , 'delete']);
            Route::get('my-rides-as-driver',[RideController::class , 'myRidesAsDriver']);
            Route::post('accept-or-refuse-ride/{ride}',[RideController::class , 'acceptOrRefuseRide']);
            Route::post('start-a-ride/{ride}',[RideController::class , 'markRideAsInProgress']);
            Route::post('end-a-ride/{ride}',[RideController::class , 'markRideAsCompleted']);
        });

        Route::middleware('!role:driver')->group(function(){
            Route::post('book-a-ride',[RideController::class , 'rideSave']);
            Route::get('my-rides-as-rider',[RideController::class , 'myRidesAsRider']);
            Route::post('cancel-ride/{ride}',[RideController::class , 'cancelRide']);
            Route::post('rate-a-ride/{ride}',[RatingsController::class , 'rate']);
        });

    });
});
