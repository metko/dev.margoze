<?php

namespace App\Contract\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InvalidatedContract extends HttpException
{
    /**
     * create Create the instance of the Exception.
     */
    public static function create()
    {
        return new static(500,'This contract is not valid');
    }
}
