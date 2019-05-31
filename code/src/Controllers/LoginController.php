<?php

namespace App\Controllers;

use App\Exceptions\WrongInputException;
use App\Misc\Response\TemplateResponse;
use App\Models\LoginModel;
use App\Services;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginController
{
    public function index(Request $request): Response
    {
        $session = Services::sessionService();
        if ($session->getSession()->has('login')) {
            return new RedirectResponse('/admin/');
        }

        $errorMessage = null;
        if ($request->getMethod() == 'POST') {
            try {
                (new LoginModel())->login($request->get('email'), $request->get('password'));
                return new RedirectResponse('/admin/');
            } catch (WrongInputException $e) {
                $errorMessage = $e->getMessage();
            }
        }

        return TemplateResponse::make(
            "login.twig",
            ['title' => 'login', 'error' => $errorMessage]
        );
    }
}