<?php

namespace App\Candidature\Exceptions;

use InvalidArgumentException;

class CandidatureBelongsToOwnerDemand extends InvalidArgumentException
{
    public static function create(int $demandId)
    {
        return new static("You are trying to submit an offer on a demand that belong to you. Demand id : $demandId ");
    }
}
