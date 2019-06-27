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
            'demand' => $this->demand,
            'candidature_id' => $this->contract->candidature_id,
            'contract_id' => $this->contract->id,
            'user_candidature' => $this->userCandidature,
            'user_demand' => $this->userDemand,
            'message' => $this->getMessage($notifiable->id),
            'url_action' => $this->getActionUrl(),
        ];
    }

    /**
     * getMessage.
     */
    protected function getMessage($notifiable_id)
    {
        if ($this->userDemand->id == $notifiable_id) {
            return "Félicitation, votre demande <strong>{$this->demand->title}</strong> a été contracté.";
        }

        return "Félicitation, votre candidature pour la demande <strong>{$this->demand->title}</strong> a été retenu.";
    }

    /**
     * getMessage.
     */

    /**
     * getMessage.
     *
     * @param mixed $notifiable_id
     */
    protected function getActionUrl()
    {
        return route('contracts.show', $this->contract->id);
    }
}