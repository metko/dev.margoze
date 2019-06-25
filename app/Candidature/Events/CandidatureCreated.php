<?php

namespace App\Candidature\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CandidatureCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $demand;
    public $userDemand;
    public $candidature;
    public $userCandidature;

    /**
     * Create a new event instance.
     */
    public function __construct($demand, $userDemand, $candidature, $userCandidature)
    {
        $this->demand = $demand;
        $this->userDemand = $userDemand;
        $this->candidature = $candidature;
        $this->userCandidature = $userCandidature;
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
