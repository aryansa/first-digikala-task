<?php

namespace App\Controllers;


use App\Models\LoginModel;
use App\Services;
use Symfony\Component\HttpFoundation\RedirectResponse;


class LogoutController
{
    public function index()
    {
        Services::accountService()->checkAdminUser();

        (new LoginModel())->logout();
        return new RedirectResponse('/login/');
    }
}