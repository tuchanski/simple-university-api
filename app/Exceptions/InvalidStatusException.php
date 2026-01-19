<?php

namespace App\Exceptions;

use Exception;

class InvalidStatusException extends Exception
{
    public function __construct() {
        parent::__construct("Invalid status", 400);
    }
}
