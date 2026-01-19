<?php

namespace App\Exceptions;

use Exception;

class ProfessorNotFoundException extends Exception
{
    public function __construct() {
        parent::__construct("Professor not found", 404);
    }
}
