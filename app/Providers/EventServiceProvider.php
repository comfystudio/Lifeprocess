<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\NotificationEvent' => [
            'App\Listeners\NotificationEventFired',
        ],'App\Events\CreditHistoryEvent' => [
            'App\Listeners\CreditHistoryEventFired',
        ],'App\Events\CoachTransactionHistoryEvent' => [
            'App\Listeners\CoachTransactionHistoryEventFired',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
