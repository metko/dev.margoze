<?php

namespace App\Contract\Listenners;

use App\Contract\Events\SettingsContractProposed;
use App\Contract\Notifications\SettingsContractProposedNotification;

class SettingsContractListenner
{
    public $user;

    public function handleSettingsContractListenner(SettingsContractProposed $event)
    {
        $event->toUser->notify((new SettingsContractProposedNotification(
         $event->contract, $event->toUser, $event->fromUser))
            ->delay(now()->addSeconds(10)));
    }
}
