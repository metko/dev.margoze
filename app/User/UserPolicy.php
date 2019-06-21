<?php

namespace App\User;

use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
    }

    public function manage(User $auth, User $user)
    {
        return $auth->id == $user->id;
    }
}
