<?php

namespace App\Exceptions;

use Exception;

class InvalidEmailException extends Exception
{
    public function __construct() {
        parent::__construct("Invalid e-mail", 400);
    }
}
