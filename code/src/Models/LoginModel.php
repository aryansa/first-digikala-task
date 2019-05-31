<?php

namespace App\Models;

use App\Exceptions\WrongInputException;
use App\Services;

class LoginModel
{
    public function login(?string $email, ?string $password)
    {
        $cryptography = Services::cryptoService();

        $result = Services::mysqlService()->query(
            "SELECT * FROM users WHERE mail = ? AND password = ? ",
            [$email, $cryptography->hash($password)]
        );

        if (count($result) < 1) {
            throw new WrongInputException('Invalid username or password');
        }

        Services::sessionService()->getSession()->set('login', $result[0]['id']);
    }

    public function logout()
    {
        Services::sessionService()->getSession()->remove('login');
    }
}