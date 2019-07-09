<?php

namespace App\Candidature\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CandidatureNotAcceptedNotificationMail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The demand of the contract.
     */
    public $demand;

    /**
     * The demand of the contract.
     */
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
        return ['mail', 'database'];
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

    /**
     * toArray.
     */
    public function toArray()
    {
        return [
            'demand_id' => $this->demand->id,
            'demand_title' => $this->demand->title,
            'message' => $this->getMessage(),
            'action_url' => $this->getActionUrl(),
        ];
    }

    /**
     * getActionUrl.
     */
    public function getActionUrl()
    {
        return route('demands.show', $this->demand->id);
    }

    /**
     * getMessage.
     */
    public function getMessage()
    {
        return "Votre candidatur pour la demande <strong>{$this->demand->title}</strong> n'à pas été retenu.";
    }
}
