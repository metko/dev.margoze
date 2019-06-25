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

    public function show(User $user, Contract $contract)
    {
        // TODO ADD USER ISOWNERDEMAND OR ISOWNERCANDIDATURE
        if ($user->id == $contract->demand_owner_id || $user->id == $contract->candidature_owner_id) {
            return true;
        }

        return false;
    }
}
