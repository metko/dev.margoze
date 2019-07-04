<?php

namespace App\Contract\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class SettingsContractRevoked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contract;
    public $toUser;
    public $fromUser;

    /**
     * Create a new event instance.
     */
    public function __construct($contract, $toUser, $fromUser)
    {
        $this->contract = $contract;
        $this->toUser = $toUser;
        $this->fromUser = $fromUser;
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
