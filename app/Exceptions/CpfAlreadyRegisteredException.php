<?php

namespace App\Exceptions;

use Exception;

class CpfAlreadyRegisteredException extends Exception
{
    public function __construct() {
        parent::__construct("CPF Already reegistered", 409);
    }
}
