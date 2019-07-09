<?php

namespace App\Candidature\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CandidatureCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The demand of the candidature.
     */
    public $demand;

    /**
     * The user owner of the demand.
     */
    public $userDemand;

    /**
     * The candidature of the demand.
     */
    public $candidature;

    /**
     * The user owner of the candidature.
     */
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
        return new Channel('demands.'.$this->demand->id);
    }
}
