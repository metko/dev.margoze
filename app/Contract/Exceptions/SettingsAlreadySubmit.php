<?php

namespace App\Contract\Exceptions;

use InvalidArgumentException;

class SettingsAlreadySubmit extends InvalidArgumentException
{
    /**
     * create Create the instance of the Exception.
     */
    public static function create()
    {
        return new static('You already propose a settings for this candidature. Please wait for the answer');
    }
}
