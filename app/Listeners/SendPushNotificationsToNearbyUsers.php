<?php

namespace App\Listeners;

use App\Events\ServiceProvidersCreatedEvent;
use App\Jobs\SendFirebaseNotificationJob;
use App\Models\User;
use App\Services\FirebasePushNotifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\Job;

class SendPushNotificationsToNearbyUsers
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ServiceProvidersCreatedEvent $event , FirebasePushNotifications $firebasePushNotifications
    ): void
    {
        $city_id = $event->provider->city_id;

        $tokens = User::whereDoesntHave('roles')
        ->whereNotNull('fcm_token')
        ->where('city_id',$city_id)
        ->pluck('fcm_token')->toArray();

        foreach($tokens as $token){
            dispatch(new SendFirebaseNotificationJob(
            $event->provider->getTranslation('name',app()->getLocale()).__(" Service Provider Now Available In Your City"), 
            __('We have Added A New Service Provider Near You , Let`s Get Some Discount!'), 
                $token));
        }

    }
}
