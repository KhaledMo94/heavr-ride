<?php

use App\Http\Controllers\Dashboard\AdminController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\GeneralSettingController;
use App\Http\Controllers\Dashboard\SendPushNotification;
use App\Http\Controllers\Dashboard\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as'            => 'admins.',
    'prefix'        => 'admin/',
    'middleware'    => ['auth', 'locale']
], function () {
    Route::get('edit-profile', [ProfileController::class, 'editProfile'])->name('profile.edit');
    Route::put('edit-profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('edit-password', [ProfileController::class, 'editPassword'])->name('password.edit');
    Route::put('edit-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    //--------------------------------------------------

    Route::post('/language-switch', [GeneralSettingController::class, 'switchLanguage'])->name('language.switch');

    //----------------------------------------------------------------

    Route::resource('users', UserController::class)->except(['show', 'create', 'edit', 'update'])
        ->middleware('role-or-permission:super-admin,users')
        ->names([
            'index'                                 => 'users.index',
            'destroy'                               => 'users.destroy',
        ]);

    Route::put('users/{id}/toggle', [UserController::class, 'toggleStatus'])
        ->middleware('role-or-permission:super-admin,users')
        ->name('users.toggle');

    Route::post('users/export', [UserController::class, 'export'])
        ->middleware('role-or-permission:super-admin,users')
        ->name('users.export');

    //----------------------------------------------------

    Route::resource('admins', AdminController::class)->except('show')
        ->middleware('role:super-admin')
        ->names([
            'index'                             => 'admins.index',
            'create'                            => 'admins.create',
            'store'                             => 'admins.store',
            'edit'                              => 'admins.edit',
            'update'                            => 'admins.update',
            'destroy'                           => 'admins.destroy',
        ]);

    //------------------------------------------------------------------

    Route::get('notify-users', [SendPushNotification::class, 'notifyUsers'])->name('notifications.user');
    Route::post('notify-users', [SendPushNotification::class, 'sendUsersNotification'])->name('notifications.user');
    Route::get('notify-cashiers', [SendPushNotification::class, 'notifyCashiers'])->name('notifications.cashier');
    Route::post('notify-cashiers', [SendPushNotification::class, 'sendCashierNotification'])->name('notifications.cashier');

});
