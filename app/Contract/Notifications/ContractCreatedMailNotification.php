<?php

namespace App\Contract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContractCreatedMailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $demand;
    public $candidature;
    public $contract;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($demand, $candidature, $contract, $user)
    {
        $this->user = $user;
        $this->demand = $demand;
        $this->candidature = $candidature;
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
        return (new MailMessage())
                    ->line("Félicitation, votre candidature  pour la demande {$this->demand->title} à été retenu.")
                    ->action('Voir le contrat', url('/login'))
                    ->line('Thank you for using our application!');
    }

    /*
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    // public function toArray($notifiable)
    // {
    //     return [
    //         'demand_id' => $this->demand->id,
    //         'demand_title' => $this->demand->title,
    //         'candidature_id' => $this->candidature->id,
    //         'message' => "Votre candidature sur la demande {$this->demand->title} à) été retenu.",
    //     ];
    // }
}
