<?php

namespace App\Contract\Listenners;

use App\Contract\Events\ContractCreated;
use App\Contract\Notifications\ContractCreatedDBNotification;
use App\Contract\Notifications\ContractCreatedMailNotification;
use App\Candidature\Notifications\CandidatureNotAcceptedNotificationMail;

class ContractEventSubscriber
{
    public $user;

    public function subscribe($events)
    {
        $events->listen(
            'App\Contract\Events\ContractCreated',
            'App\Contract\Listenners\ContractEventSubscriber@handleContractCreatedForUserDemand'
        );

        $events->listen(
            'App\Contract\Events\ContractCreated',
            'App\Contract\Listenners\ContractEventSubscriber@handleContractCreatedForUserCandidature'
        );

        $events->listen(
            'App\Contract\Events\ContractCreated',
            'App\Contract\Listenners\ContractEventSubscriber@handleCandidatureNotAcceptedNotificationMail'
        );
    }

    public function handleContractCreatedForUserCandidature(ContractCreated $event)
    {
        $userCandidature = $event->user;
        $userDemand = $event->demand->owner;

        $userCandidature->notify((new ContractCreatedMailNotification(
            $event->demand, $event->contract))
        ->delay(now()->addSeconds(10)));

        $userCandidature->notify((new ContractCreatedDBNotification(
            $event->demand, $event->contract, $userDemand, $userCandidature))
        ->delay(now()->addSeconds(2)));
    }

    public function handleContractCreatedForUserDemand(ContractCreated $event)
    {
        $userCandidature = $event->user;
        $userDemand = $event->demand->owner;

        $userDemand->notify((new ContractCreatedDBNotification(
            $event->demand, $event->contract, $userDemand, $userCandidature))
        ->delay(now()->addSeconds(4)));
    }

    public function handleCandidatureNotAcceptedNotificationMail(ContractCreated $event)
    {
        $userCandidature = $event->user;
        $userDemand = $event->demand->owner;

        $users = $event->demand->candidatures->where('owner_id', '!=', $userCandidature->id)
                ->map(function ($candidature) {
                    return $candidature->owner;
                });

        $when = now()->addSeconds(30);
        foreach ($users as $user) {
            $user->notify((new CandidatureNotAcceptedNotificationMail($userDemand, $user))
                    ->delay($when));
            $when->addSeconds(30);
        }
    }
}
