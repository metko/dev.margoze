<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class CantContactUser extends HttpException
{
    public static function create()
    {
        return new static(500,'Impossible de contacter cet utilisateur');
    }
}
