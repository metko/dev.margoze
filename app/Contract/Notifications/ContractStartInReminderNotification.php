<?php

namespace App\Contract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContractStartInReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * User .
     */
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
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
                    ->subject("Votre contract avec {$this->user['other_user_username']}")
                    ->line($this->getMessage())
                    ->action('Voir le contrat', url("/contracts/{$this->user['contract_id']}"))
                    ->line('Thank you for using our application!');
    }

    public function getMessage()
    {
        return 'Préparez vous, votre contrat démare bientot';
    }
}
