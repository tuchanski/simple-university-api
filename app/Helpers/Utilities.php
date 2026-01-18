<?php

namespace App\Helpers;

use App\Enums\Gender;

class Utilities
{
    public static function isGenderValid($gender) : bool
    {
        $genderOptions = Gender::cases();

        foreach ($genderOptions as $genderOption) {
            if ($genderOption->value === $gender) {
                return true;
            }
        }

        return false;
    }

    public static function isEmailValid($email) : bool
    {
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        return filter_var($sanitizedEmail, FILTER_VALIDATE_EMAIL);
    }
}
