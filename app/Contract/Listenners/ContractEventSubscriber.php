<?php

namespace App\Contract\Listenners;

use App\Contract\Events\ContractCreated;
use App\Contract\Notifications\ContractCreatedNotification;

class ContractEventSubscriber
{
    public $user;

    public function subscribe($events)
    {
        $events->listen(
            'App\Contract\Events\ContractCreated',
            'App\Contract\Listenners\ContractEventSubscriber@handleContractCreated'
        );
    }

    public function handleContractCreated(ContractCreated $event)
    {
        $ownerDemand = $event->demand->owner;
        $ownerCandidature = $event->candidature->owner;
        $ownerCandidature->notify(new ContractCreatedNotification($event->demand, $event->candidature, $event->contract));
    }
}
