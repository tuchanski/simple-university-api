<?php

namespace App\Exceptions;

use Exception;

class CourseNotFoundException extends Exception
{
    public function __construct() {
        parent::__construct("Course not found", 404);
    }
}
