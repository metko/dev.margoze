<?php

namespace App\Candidature\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CandidatureCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The demand updated.
     */
    public $demand;

    /**
     * The candidature of the demand.
     */
    public $candidature;

    /**
     * The user of the candidature.
     */
    public $userCandidature;

    /**
     * Create a new notification instance.
     */
    public function __construct($demand, $candidature, $userCandidature)
    {
        $this->demand = $demand;
        $this->candidature = $candidature;
        $this->userCandidature = $userCandidature;
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
        return ['mail', 'database', 'broadcast'];
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
                    ->subject('Super')
                    ->line($this->getMessage())
                    ->action('Voir la demande', $this->getActionUrl())
                    ->line('Thank you for using our application!');
    }

    /**
     * toArray.
     */
    public function toArray()
    {
        return [
            'demand' => $this->demand,
            'candidature' => $this->candidature,
            'action_url' => $this->getActionUrl(),
            'message' => $this->getMessage(),
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
        return "<strong>{$this->userCandidature->username}</strong> vous a envoyé sa candidature pour votre demande <strong>{$this->demand->title}</strong>";
    }
}
