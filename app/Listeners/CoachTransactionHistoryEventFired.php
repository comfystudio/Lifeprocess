<?php

namespace App\Listeners;

use App\Events\CoachTransactionHistoryEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\CoachTransactionHistory;

class CoachTransactionHistoryEventFired
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
     * @param  CoachTransactionHistoryEvent  $event
     * @return void
     */
    public function handle(CoachTransactionHistoryEvent $event)
    {
        CoachTransactionHistory::create($event->transaction_history);
    }
}
