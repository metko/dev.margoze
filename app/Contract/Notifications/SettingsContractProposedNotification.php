<?php

namespace App\Contract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SettingsContractProposedNotification extends Notification implements ShouldQueue
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
                    ->subject('Nouvelle proposition!')
                    ->line($this->getMessage())
                    ->action('Voir le contrat', $this->getActionUrl())
                    ->line('Thank you for using our application!');
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
        return  "Une proposition pour le contrat {$this->contract->id} à été soumis par {$this->fromUser->username}.";
    }
}
