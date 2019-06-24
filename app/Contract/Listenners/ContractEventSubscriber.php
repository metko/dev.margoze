<?php

namespace App\Contract\Listenners;

use App\Contract\Events\ContractCreated;
use Illuminate\Support\Facades\Notification;
use App\Contract\Notifications\ContractCreatedNotification;
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

        Notification::route('mail', $userCandidature->email)
            ->notify((new ContractCreatedNotification([
                'demand' => $event->demand,
                'candidature' => $event->candidature,
                'contract' => $event->contract,
                'userDemand' => $userDemand,
                'userCandidature' => $userCandidature,
                'message' => "Votre candidature pour la demande {$event->demand->title} à été retenu",
            ]))
                ->delay(now()->addSeconds(10)));

        Notification::route('database', $userCandidature->email)
            ->notify((new ContractCreatedNotification([
                    'demand' => $event->demand,
                    'candidature' => $event->candidature,
                    'contract' => $event->contract,
                    'userDemand' => $userDemand,
                    'userCandidature' => $userCandidature,
                    'message' => "Votre candidature pour la demande {$event->demand->title} à été retenu",
                ]))
                ->delay(now()->addSeconds(3)));
    }

    public function handleContractCreatedForUserDemand(ContractCreated $event)
    {
        $userCandidature = $event->user;
        $userDemand = $event->demand->owner;

        Notification::route('database', $userDemand->email)
            ->notify((new ContractCreatedNotification([
                    'demand' => $event->demand,
                    'candidature' => $event->candidature,
                    'contract' => $event->contract,
                    'userDemand' => $userDemand,
                    'userCandidature' => $userCandidature,
                    'message' => "Votre candidature pour la demande {$event->demand->title} à été retenu",
                ]))
                ->delay(now()->addSeconds(3)));
    }

    public function handleCandidatureNotAcceptedNotificationMail(ContractCreated $event)
    {
        $userCandidature = $event->user;
        $userDemand = $event->demand->owner;
        $users = $event->demand->candidatures->where('owner_id', '!=', $userCandidature->id)
                ->map(function ($candidature) {
                    return $candidature->owner;
                });
        foreach ($users as $user) {
            $user->notify((new CandidatureNotAcceptedNotificationMail($userDemand, $user))
                    ->delay(now()->addSeconds(10)));
        }
    }
}
