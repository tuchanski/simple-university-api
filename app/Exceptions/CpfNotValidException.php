<?php

namespace App\Exceptions;

use Exception;

class CpfNotValidException extends Exception
{
    public function __construct() {
        parent::__construct('CPF not valid', 400);
    }
}
