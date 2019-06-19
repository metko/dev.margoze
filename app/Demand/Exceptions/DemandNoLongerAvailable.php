<?php

namespace App\Demand\Exceptions;

use InvalidArgumentException;

class DemandNoLongerAvailable extends InvalidArgumentException
{
    public static function create(int $demandId)
    {
        return new static("This demand is no longer available. Demand id : $demandId ");
    }
}
