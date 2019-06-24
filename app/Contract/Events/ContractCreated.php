<?php

namespace App\Contract\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class ContractCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $demand;
    public $candidature;
    public $contract;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct($demand, $candidature, $contract, $user)
    {
        $this->user = $user;
        $this->demand = $demand;
        $this->candidature = $candidature;
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
