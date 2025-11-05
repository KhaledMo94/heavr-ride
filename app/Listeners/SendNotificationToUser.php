<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Services\FirebasePushNotifications;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationToUser implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $locale = app()->getLocale();
        $client  = $event->client;

        $notification = new FirebasePushNotifications();

        if ($client->fcm_token) {
            $title = __('You will pay :amount EGP', ['amount' => $event->amount]);
            $body  = __('Your order created by :name successfully.', [
                'name' => $event->serviceProvider->getTranslation('name', $locale)
            ]);
            $notification->sendPushNotification(
                $client->fcm_token,$title , $body
            );
        }
    }
}
