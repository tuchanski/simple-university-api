<?php

namespace App\Exceptions;

use Exception;

class StudentAlreadyEnrolledException extends Exception
{
    public function __construct() {
        parent::__construct("Student already enrolled to this course", 400);
    }
}
