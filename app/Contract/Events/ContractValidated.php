<?php

namespace App\Contract\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ContractValidated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Contract updated.
     */
    public $contract;

    /**
     * Create a new event instance.
     */
    public function __construct($contract)
    {
        $this->contract = $contract;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
