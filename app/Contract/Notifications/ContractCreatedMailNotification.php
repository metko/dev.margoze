<?php

namespace App\Contract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContractCreatedMailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Demand of the contract.
     */
    public $demand;

    /**
     * Contract updated.
     */
    public $contract;

    /**
     * Create a new notification instance.
     */
    public function __construct($demand, $contract)
    {
        $this->demand = $demand;
        $this->contract = $contract;
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
        $message = "Votre candidature pour la demande {$this->demand->title} à été retenu";

        return (new MailMessage())
                    ->subject('Un nouveau contrat!')
                    ->line("Votre candidature pour la demande {$this->demand->title} à été retenu.")
                    ->action('Voir le contrat', url("/contracts/{$this->contract->id}"))
                    ->line('Thank you for using our application!');
    }
}
