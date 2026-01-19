<?php

namespace App\Exceptions;

use Exception;

class InvalidLangException extends Exception
{
    public function __construct() {
        parent::__construct("Invalid language", 400);
    }
}
