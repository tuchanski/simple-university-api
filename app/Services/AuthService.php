<?php

namespace App\Services;

use App\Models\User;

class AuthService
{

    public function register(array $data) : User {
        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);

        $user->save();

        return $user;
    }

    public function login(array $data) : User {

    }


}
