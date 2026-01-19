<?php

namespace App\Exceptions;

use Exception;

class ProfessorNotEnrolledException extends Exception
{
    public function __construct() {
        parent::__construct("Professor not enrolled to this course", 400);
    }
}
