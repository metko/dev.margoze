<?php

namespace App\Candidature\Listenners;

use App\Candidature\Events\CandidatureCreated;
use App\Candidature\Notifications\CandidatureCreatedNotification;

class CandidatureEventSubscriber
{
    public $user;

    /**
     * subscribe.
     *
     * @param mixed $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Candidature\Events\CandidatureCreated',
            'App\Candidature\Listenners\CandidatureEventSubscriber@handleCandidatureCreated'
        );
    }

    /**
     * When a candidature is created, send a mail message and broadcast to the owner of the demand.
     *
     * @param mixed $event
     */
    public function handleCandidatureCreated(CandidatureCreated $event)
    {
        $event->userDemand->notify((new CandidatureCreatedNotification(
            $event->demand, $event->candidature, $event->userCandidature
         ))->delay(now()->addSeconds(2)));
    }
}
