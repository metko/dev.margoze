<?php

namespace App\Contract\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UserDoesntBelongsToContract extends HttpException
{
    public static function create()
    {
        return new static(500, "User does't belongs to the contract");
    }
}
