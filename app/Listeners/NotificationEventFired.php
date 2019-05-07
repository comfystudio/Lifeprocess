<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Notification;
use App\Models\NotifyUser;

class NotificationEventFired
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NotificationEvent  $event
     * @return void
     */
    public function handle(NotificationEvent $event)
    {
        //Insert notification into table...
        $notification = Notification::create(['notification_text' => $event->notification['text']]);
        foreach ($event->notification['receiver_id'] as $value) {
            NotifyUser::create(['notification_id' => $notification->id, 'receiver_id' => $value]);
        }
    }
}
