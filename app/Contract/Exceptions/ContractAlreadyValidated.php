<?php

namespace App\Contract\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ContractAlreadyValidated extends HttpException
{
    public static function create()
    {
        return new static(500,'This contracted is already validated. Impossible de update it');
    }
}
