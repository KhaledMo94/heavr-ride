<?php

use App\Http\Controllers\Api\CraneController;
use App\Http\Controllers\Api\Dashboard\AdminsController;
use App\Http\Controllers\Api\Dashboard\CranesController;
use App\Http\Controllers\Api\Dashboard\DriversController;
use App\Http\Controllers\Api\Dashboard\GeneralSettingController;
use App\Http\Controllers\Api\Dashboard\RidersController;
use App\Http\Controllers\Api\Dashboard\TransactionsController as DashboardTransactionsController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\RatingsController;
use App\Http\Controllers\Api\RideController;
use App\Http\Controllers\Api\TransactionsController;
use App\Http\Controllers\Api\UserAuthController;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;


Route::post('register', [UserAuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('login', [UserAuthController::class, 'login'])->middleware('throttle:5,1');
Route::post('forgot-password', [OtpController::class, 'forgotPassword'])->middleware('throttle:30,1');
Route::post('reset-password', [OtpController::class, 'resetPassword'])->middleware('throttle:30,1');

Route::group([
    'middleware'            => ['auth:sanctum', 'throttle:100,1', 'locale'],
], function () {
    Route::post('logout', [UserAuthController::class, 'logout']);
    Route::get('user', [UserAuthController::class, 'user']);
    Route::post('send-verification-code', [OtpController::class, 'send']);
    Route::post('verify-code', [OtpController::class, 'verify']);
    Route::put('update-tokens', [UserAuthController::class, 'updateTokens']);
    Route::group([
        'middleware'            => [
            'user-unbanned'
        ],
    ], function () {
        Route::put('update', [UserAuthController::class, 'update']);
        Route::delete('delete-account', [UserAuthController::class, 'deleteAccount']);
        //----------------------------------------------------------
        Route::get('get-available-online-cranes', [CraneController::class, 'index']);
        Route::get('single-crane/{crane}', [CraneController::class, 'show']);
        //-----------------------------------------------------------
        Route::get('my-transactions', [TransactionsController::class, 'index']);
        Route::post('charge-my-wallet', [TransactionsController::class, 'chargeWallet']);
        Route::get('my-withdraw-requests',[TransactionsController::class , 'myRequests']);
        Route::post('add-withdraw-request',[TransactionsController::class , 'sendRequest']);
        //------------------------------------------------------------

        Route::middleware('role:driver')->group(function () {
            Route::post('create-crane', [CraneController::class, 'create']);
            Route::put('update-crane', [CraneController::class, 'update']);
            Route::put('crane-toggle', [CraneController::class, 'toggle']);
            Route::delete('delete-crane', [CraneController::class, 'delete']);
            Route::get('my-rides-as-driver', [RideController::class, 'myRidesAsDriver']);
            Route::post('accept-or-refuse-ride/{ride}', [RideController::class, 'acceptOrRefuseRide']);
            Route::post('start-a-ride/{ride}', [RideController::class, 'markRideAsInProgress']);
            Route::post('end-a-ride/{ride}', [RideController::class, 'markRideAsCompleted']);
        });

        Route::middleware('!role:driver')->group(function () {
            Route::post('book-a-ride', [RideController::class, 'rideSave']);
            Route::get('my-rides-as-rider', [RideController::class, 'myRidesAsRider']);
            Route::post('cancel-ride/{ride}', [RideController::class, 'cancelRide']);
            Route::post('rate-a-ride/{ride}', [RatingsController::class, 'rate']);
        });

        Route::middleware('role_or_permission_spatie:super-admin|settings')->group(function () {
            Route::get('get-settings', [GeneralSettingController::class, 'index']);
            Route::put('update-settings', [GeneralSettingController::class, 'updateSettings']);
        });

        Route::middleware('role_or_permission_spatie:super-admin|riders')->group(function () {
            Route::get('all-riders', [RidersController::class, 'index']);
            Route::put('toggle-rider/{user}', [RidersController::class, 'toggle']);
            Route::put('update-rider/{user}', [RidersController::class, 'update']);
            Route::delete('delete-rider/{user}', [RidersController::class, 'delete']);
        });

        Route::middleware('role_or_permission_spatie:super-admin|drivers')->group(function () {
            Route::get('all-drivers', [DriversController::class, 'index']);
            Route::put('toggle-driver/{user}', [DriversController::class, 'toggle']);
            Route::put('update-driver/{user}', [DriversController::class, 'update']);
            Route::delete('delete-driver/{user}', [DriversController::class, 'delete']);
        });

        Route::middleware('role_or_permission_spatie:super-admin|admins')->group(function () {
            Route::get('all-admins', [AdminsController::class, 'index']);
            Route::get('all-permissions',[AdminsController::class , 'permissions']);
            Route::post('create-admin',[AdminsController::class , 'store']);
            Route::put('toggle-admin/{user}', [AdminsController::class, 'toggle']);
            Route::put('update-admin/{user}', [AdminsController::class, 'update']);
            Route::delete('delete-admin/{user}', [AdminsController::class, 'destroy']);
        });

        Route::middleware('role_or_permission_spatie:super-admin|cranes')->group(function () {
            Route::get('all-cranes', [CranesController::class, 'index']);
            Route::get('single-crane/{crane}',[CraneController::class , 'show']);
            Route::put('update-crane/{crane}', [CranesController::class, 'update']);
            Route::delete('delete-crane/{crane}', [CranesController::class, 'delete']);
        });

        Route::middleware('role_or_permission_spatie:super-admin|   transactions')
        ->group(function () {
            Route::get('all-transactions', [DashboardTransactionsController::class, 'index']);
            Route::get('create', [DashboardTransactionsController::class, 'store']);
            Route::get('single-transaction/{transaction}',[DashboardTransactionsController::class , 'show']);
            Route::put('update-transaction/{transaction}', [DashboardTransactionsController::class, 'update']);
            Route::delete('delete-transaction/{transaction}', [DashboardTransactionsController::class, 'delete']);
        });
    });
});
