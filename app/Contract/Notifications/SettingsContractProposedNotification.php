<?php

namespace App\Contract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SettingsContractProposedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $contarct;
    public $toUser;
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
                    ->line($this->getMessage)
                    ->action('Voir le contrat', $this->getActionUrl())
                    ->line('Thank you for using our application!');
    }

    protected function getActionUrl()
    {
        return url("/contracts/{$this->contract->id}");
    }

    protected function getMessage()
    {
        return  "Une proposition pour le contrat {$this->contract->id} à été soumis par {$this->fromUser->username}.";
    }
}
