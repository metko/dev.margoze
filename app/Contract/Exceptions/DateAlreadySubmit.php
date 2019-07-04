<?php

namespace App\Contract\Exceptions;

use InvalidArgumentException;

class DateAlreadySubmit extends InvalidArgumentException
{
    public static function create()
    {
        return new static('You already propose a date or this candidature. Please wait for the answer');
    }
}
