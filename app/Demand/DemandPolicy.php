<?php

namespace App\Demand;

use App\User\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DemandPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function manage(User $user, Demand $demand)
    {
        if ($user->id != $demand->owner_id) {
            $this->deny("Vous ne pouvez pas editer cette demande car elle vous n'etes pas le propriétaire.");
        }

        return true;
    }
}
