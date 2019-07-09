<?php

namespace App\Contract\Listenners;

use App\Contract\Events\ContractCreated;
use Illuminate\Support\Facades\Notification;
use App\Contract\Notifications\ContractCreatedDBNotification;
use App\Contract\Notifications\ContractCreatedMailNotification;
use App\Contract\Notifications\ContractCreatedBroadcastNotification;
use App\Candidature\Notifications\CandidatureNotAcceptedNotificationMail;

class ContractEventSubscriber
{
    public $user;

    public function subscribe($events)
    {
        $events->listen(
            'App\Contract\Events\ContractCreated',
            'App\Contract\Listenners\ContractEventSubscriber@handleContractCreated'
        );

        $events->listen(
            'App\Contract\Events\ContractCreated',
            'App\Contract\Listenners\ContractEventSubscriber@handleCandidatureNotAcceptedNotificationMail'
        );

        $events->listen(
            'App\Contract\Events\SettingsContractProposed',
            'App\Contract\Listenners\SettingsContractListenner@handleSettingsContractListenner'
        );
        $events->listen(
            'App\Contract\Events\SettingsContractRevoked',
            'App\Contract\Listenners\SettingsContractListenner@handleSettingsRevokedListenner'
        );
    }

    /**
     * handleContractCreated.
     *
     * 1 - Send mail Notification to the owner of the candidature
     * 2 - Broadcast the notification fot the owner of the candidature
     * 3 - Send mail to announce that a new contract was created to the 2 users
     *
     *
     * @param mixed $event
     */
    public function handleContractCreated(ContractCreated $event)
    {
        $userCandidature = $event->user;
        $userDemand = $event->demand->owner;

        $userCandidature->notify((new ContractCreatedMailNotification(
            $event->demand, $event->contract))
        ->delay(now()->addSeconds(10)));

        Notification::send([$userDemand, $userCandidature], (new ContractCreatedDBNotification(
            $event->demand, $event->contract, $userDemand, $userCandidature))
        ->delay(now()->addSeconds(4)));

        $userCandidature->notify((new ContractCreatedBroadcastNotification(
            $event->demand, $event->contract, $userDemand, $userCandidature))
        ->delay(now()->addSeconds(2)));
    }

    /**
     * handleCandidatureNotAcceptedNotificationMail.
     *
     * Send mail notification to all the user who sent a candidature on this demand
     *
     * @param mixed $event
     */
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
