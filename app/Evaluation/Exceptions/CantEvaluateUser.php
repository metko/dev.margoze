<?php

namespace App\Evaluation\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class CantEvaluateUser extends HttpException
{
    /**
     * create Create the instance of the Exception.
     */
    public static function create()
    {
        return new static(500,'You can\'t evaluate this user.');
    }
}
