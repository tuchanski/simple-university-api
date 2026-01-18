<?php

namespace App\Exceptions;

use Exception;

class EmailAlreadyRegisteredException extends Exception
{
    public function __construct() {
        parent::__construct('Email already registered', 409);
    }
}
