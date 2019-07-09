<?php

namespace App\Contract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SettingsContractProposedNotificationBroadcast extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The contract updated.
     */
    public $contract;

    /**
     * The user who we want to send the notification.
     */
    public $toUser;

    /**
     * The user who cause the notification.
     */
    public $fromUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($contract, $toUser, $fromUser)
    {
        $this->contract = $contract;
        $this->toUser = $toUser;
        $this->fromUser = $fromUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toArray($notifiable)
    {
        return [
         'contract' => $this->contract,
         'toUser' => $this->toUser,
         'fromUser' => $this->fromUser,
         'message' => $this->getMessage(),
         'action_url' => $this->getActionUrl(),
     ];
    }

    /**
     * getActionUrl.
     */
    protected function getActionUrl()
    {
        return url("/contracts/{$this->contract->id}");
    }

    /**
     * getMessage.
     */
    protected function getMessage()
    {
        return  "{$this->fromUser->username} vous a proposé des détails pour le contrat {$this->contract->id}.";
    }
}
