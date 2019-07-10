<?php

namespace App\Evaluation\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UserAlreadyEvaluated extends HttpException
{
    /**
     * create Create the instance of the Exception.
     */
    public static function create()
    {
        return new static(500,'You have already evaluate this user');
    }
}
