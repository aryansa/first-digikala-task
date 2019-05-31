<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\Session;

class SessionService
{

    private static $session;

    /**
     * SessionService constructor.
     */
    public function __construct()
    {
        self::$session = new Session();
        self::$session->start();
    }

    public function getSession()
    {
        return self::$session;
    }
}