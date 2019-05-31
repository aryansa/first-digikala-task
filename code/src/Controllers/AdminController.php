<?php

namespace App\Controllers;

use App\Misc\Response\TemplateResponse;
use App\Services;

class AdminController
{
    public function index()
    {
        Services::accountService()->checkAdminUser();

        return TemplateResponse::make(
            "admin.twig",
            [
                'title' => 'admin',
                'name' => Services::accountService()->getAccount()->getEmail()
            ]
        );
    }
}