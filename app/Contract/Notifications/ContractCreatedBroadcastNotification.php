<?php

namespace App\Contract\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContractCreatedBroadcastNotification extends Notification implements ShouldQueue
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
     *  User who sent the demand of the contract.
     */
    public $userDemand;

    /**
     * User who sent the candidature on the demand.
     */
    public $userCandidature;

    /**
     * Create a new notification instance.
     */
    public function __construct($demand, $contract, $userDemand, $userCandidature)
    {
        $this->demand = $demand;
        $this->contract = $contract;
        $this->userDemand = $userDemand;
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
        return ['broadcast'];
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
            'message' => $this->getMessage(),
            'action_url' => $this->getActionUrl(),
        ];
    }

    /**
     * getMessage.
     */
    protected function getMessage()
    {
        return "Félicitation, votre candidature pour la demande <strong>{$this->demand->title}</strong> a été retenu.";
    }

    /**
     * getMessage.
     */
    protected function getActionUrl()
    {
        return route('contracts.show', $this->contract->id);
    }
}
