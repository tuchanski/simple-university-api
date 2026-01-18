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
}
