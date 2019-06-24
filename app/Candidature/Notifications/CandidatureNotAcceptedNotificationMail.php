<?php

namespace App\Candidature\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CandidatureNotAcceptedNotificationMail extends Notification implements ShouldQueue
{
    use Queueable;

    public $demand;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($demand, $user)
    {
        $this->demand = $demand;
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
                    ->line("Désolé, votre candidature  pour la demande {$this->demand->title} n'a pas été retenu.")
                    ->action('Voir la demande', url("/demands/{$this->demand->id}"))
                    ->line('Thank you for using our application!');
    }
}
