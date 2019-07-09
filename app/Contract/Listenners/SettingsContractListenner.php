<?php

namespace App\Contract\Listenners;

use App\Contract\Events\SettingsContractRevoked;
use App\Contract\Events\SettingsContractProposed;
use App\Contract\Notifications\SettingsContractRevokedNotification;
use App\Contract\Notifications\SettingsContractProposedNotification;
use App\Contract\Notifications\SettingsContractRevokedNotificationBroadcast;
use App\Contract\Notifications\SettingsContractProposedNotificationBroadcast;

class SettingsContractListenner
{
    public $user;

    /**
     * handleSettingsContractListenner.
     *
     * Fired when a contract get new settings
     *
     * 1 - Send email notification to the receiver user
     * 2 - Broadcast the notification fot the receiver user
     *
     * @param mixed $event
     */
    public function handleSettingsContractListenner(SettingsContractProposed $event)
    {
        $event->toUser->notify((new SettingsContractProposedNotification(
         $event->contract, $event->toUser, $event->fromUser))
            ->delay(now()->addSeconds(10)));

        $event->toUser->notify(new SettingsContractProposedNotificationBroadcast(
                $event->contract, $event->toUser, $event->fromUser));
    }

    /**
     * handleSettingsRevokedListenner.
     *
     * Fired when user revoke the new settings proposition
     *
     * 1 - Notification mail
     * 2 - BroadCast notification
     *
     * @param mixed $event
     */
    public function handleSettingsRevokedListenner(SettingsContractRevoked $event)
    {
        $event->toUser->notify((new SettingsContractRevokedNotification(
         $event->contract, $event->toUser, $event->fromUser))
            ->delay(now()->addSeconds(10)));

        $event->toUser->notify(new SettingsContractRevokedNotificationBroadcast(
                $event->contract, $event->toUser, $event->fromUser));
    }
}
