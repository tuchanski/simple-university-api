<?php

namespace App\Exceptions;

use Exception;

class InvalidGenderException extends Exception
{
    public function __construct() {
        parent::__construct("Invalid gender", 400);
    }
}
