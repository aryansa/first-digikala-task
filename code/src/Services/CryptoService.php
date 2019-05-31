<?php

namespace App\Services;


class CryptoService
{
    public function hash(string $pass):string
    {
        return md5($pass);
    }
}