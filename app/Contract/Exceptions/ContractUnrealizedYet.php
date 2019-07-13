<?php

namespace App\Contract\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ContractUnrealizedYet extends HttpException
{
    /**
     * create Create the instance of the Exception.
     */
    public static function create()
    {
        return new static(500,'This contract is not realised yet, please wait until the realisation of the contract');
    }
}
