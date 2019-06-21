<?php

namespace App\Demand\Exceptions;

use InvalidArgumentException;

class DemandAlreadyContracted extends InvalidArgumentException
{
    public static function create(int $demandId)
    {
        return new static("This demand is already contracted. Demand id : $demandId ");
    }
}
