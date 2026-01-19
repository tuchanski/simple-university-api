<?php

namespace App\Exceptions;

use Exception;

class InvalidLevelException extends Exception
{
    public function __construct() {
        parent::__construct("Invalid level", 400);
    }
}
