<?php

namespace App\Contract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContractCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $demand;
    public $candidature;
    public $contract;
    public $userCandidature;
    public $userDemand;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->userCandidature = $data['userCandidature'];
        $this->userDemand = $data['userDemand'];
        $this->demand = $data['demand'];
        $this->candidature = $data['candidature'];
        $this->contract = $data['contract'];
        $this->message = $data['message'];
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
        return ['database', 'mail'];
    }

    /*
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'demand_id' => $this->demand->id,
            'demand_title' => $this->demand->title,
            'candidature_id' => $this->candidature->id,
            'contract_id' => $this->contract->id,
            'user_offer_id' => $this->userOffer->id,
            'user_offer_username' => $this->userOffer->username,
            'user_demand_id' => $this->userDemand->id,
            'user_demand_username' => $this->userDemand->username,
            'message' => $this->message,
        ];
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
                    ->line($this->message)
                    ->action('Voir le contrat', url('/contracts'))
                    ->line('Thank you for using our application!');
    }
}
