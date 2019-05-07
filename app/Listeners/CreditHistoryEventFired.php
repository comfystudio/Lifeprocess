<?php

namespace App\Listeners;

use App\Events\CreditHistoryEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\UserCreditsHistory;

class CreditHistoryEventFired
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
     * @param  CreditHistoryEvent  $event
     * @return void
     */
    public function handle(CreditHistoryEvent $event)
    {
        // manage credit history into table..
        UserCreditsHistory::create($event->credit_history);
    }
}
