<?php

namespace App\Exceptions;

use Exception;

class ProfessorAlreadyEnrolledException extends Exception
{
    public function __construct() {
        parent::__construct("There is already a professor enrolled to this course", 400);
    }
}
