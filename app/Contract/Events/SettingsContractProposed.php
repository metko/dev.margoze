<?php

namespace App\Contract\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class SettingsContractProposed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Contract updated.
     */
    public $contract;

    /**
     *  User who we want to send the notification.
     */
    public $toUser;

    /**
     * User who cause the notification.
     */
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
        return new PrivateChannel('contracts.'.$this->contract->id);
    }
}
