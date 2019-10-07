<?php

namespace App\Credit\Exceptions;

use InvalidArgumentException;

class NoCreditsAvailable extends InvalidArgumentException
{
    public static function create()
    {
        return new static("You doesn't have enought credits. Please upgrade your account");
    }
}
