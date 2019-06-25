<?php

namespace App\Contract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContractCreatedDBNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $demand;
    public $contract;
    public $userCandidature;
    public $userDemand;

    /**
     * Create a new notification instance.
     */
    public function __construct($demand, $contract, $userDemand, $userCandidature)
    {
        $this->userCandidature = $userCandidature;
        $this->userDemand = $userDemand;
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
        return ['database'];
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
            'candidature_id' => $this->contract->candidature_id,
            'contract_id' => $this->contract->id,
            'user_candidature_id' => $this->userCandidature->id,
            'user_candidature_username' => $this->userCandidature->username,
            'user_demand_id' => $this->userDemand->id,
            'user_demand_username' => $this->userDemand->username,
            'message' => $this->getMessage($notifiable->id),
        ];
    }

    /**
     * getMessage.
     */
    protected function getMessage($notifiable_id)
    {
        if ($this->userDemand->id == $notifiable_id) {
            return "Félicitation, votre demande {$this->demand->title} a été contracté.";
        }

        return "Félicitation, votre candidature pour la demande {$this->demand->title} a été retenu.";
    }
}
