<?php

namespace App\Helpers;

use App\Enums\Gender;
use App\Enums\Language;
use App\Enums\Level;
use App\Enums\Status;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;

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

    public static function isCpfValid($cpf) : bool {
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
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

    public static function isAuthUserAdmin() : bool {
        return auth()->user()->is_admin;
    }

}
