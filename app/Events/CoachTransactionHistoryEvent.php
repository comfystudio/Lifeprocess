<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CoachTransactionHistoryEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $transaction_history;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($transaction_history)
    {
        $this->transaction_history = $transaction_history;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
