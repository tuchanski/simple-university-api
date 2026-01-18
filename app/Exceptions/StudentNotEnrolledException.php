<?php

namespace App\Exceptions;

use Exception;

class StudentNotEnrolledException extends Exception
{
    public function __construct() {
        parent::__construct("Student not enrolled to this course", 400);
    }
}
