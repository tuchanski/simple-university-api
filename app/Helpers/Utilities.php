<?php

namespace App\Helpers;

use App\Enums\Gender;
use App\Enums\Language;
use App\Enums\Level;
use App\Enums\Status;

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

    public static function isLevelValid($level) : bool {
        $levelOptions = Level::cases();

        foreach ($levelOptions as $levelOption) {
            if ($levelOption->value === $level) {
                return true;
            }
        }

        return false;
    }

    public static function isStatusValid($status) : bool {
        $statusOptions = Status::cases();

        foreach ($statusOptions as $statusOption) {
            if ($statusOption->value === $status) {
                return true;
            }
        }

        return false;
    }

    public static function isLangValid($lang) : bool {
        $langOptions = Language::cases();

        foreach ($langOptions as $langOption) {
            if ($langOption->value === $lang) {
                return true;
            }
        }

        return false;
    }
}
