<?php

namespace App\Contract;

use App\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContractPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function before(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    /**
     * manage. If the current user can manage the contract.
     *
     * @param mixed $user
     * @param mixed $contract
     */
    public function manage(User $user, Contract $contract)
    {
        // TODO ADD USER ISOWNERDEMAND OR ISOWNERCANDIDATURE
        if ($user->isInContract($contract)) {
            return true;
        }

        return false;
    }
}
