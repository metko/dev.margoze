<?php

namespace App\Candidature\Exceptions;

use InvalidArgumentException;

class CandidatureAlreadySent extends InvalidArgumentException
{
    public static function create(int $demandId)
    {
        return new static("You already post a candidature for this demand. Demand id : $demandId ");
    }
}
