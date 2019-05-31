<?php

namespace App\Controllers;

use App\Exceptions\WrongInputException;
use App\Misc\Response\TemplateResponse;
use App\Models\ColorModel;
use App\Services;
use App\Views\ColorView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminColorsController
{
    public function index()
    {
        Services::accountService()->checkAdminUser();
        return TemplateResponse::make(
            "indexcolors.twig",
            [
                'title' => 'colors list',
                'colors' => (new ColorView())->getAll()
            ]
        );
    }

    public function new(Request $request)
    {
        $messages = [];
        if ($request->getMethod() == 'POST') {
            $errorMessage = null;
            try {
                (new ColorModel())->update(null, $request->request->all());
                $messages['success'] = 'Color updated';
            } catch (WrongInputException $e) {
                $messages['error'] = $e->getMessage();
            }
        }
        return TemplateResponse::make(
            "newcolor.twig",
            [
                'title' => 'new color',
                'action' => "/admin/managecolors/new/",
                'messages' => $messages
            ]
        );

    }

    public function update(Request $request, int $id)
    {

        if (empty($id)) {
            throw new WrongInputException('Id is empty');
        }

        $messages = [];
        if ($request->getMethod() == 'POST') {
            $errorMessage = null;
            try {
                (new ColorModel())->update($id, $request->request->all());
                $messages['success'] = 'Color updated';
            } catch (WrongInputException $e) {
                $messages['error'] = $e->getMessage();
            }
        }

        return TemplateResponse::make(
            "newcolor.twig",
            [
                'title' => 'new color',
                'action' => "/admin/managecolors/update/$id",
                'color' => (new ColorView())->getAll(),
                'messages' => $messages
            ]
        );

    }

    public function delete(int $id)
    {

        if (empty($id)) {
            throw new WrongInputException('Id is empty');
        }

        (new ColorModel())->delete($id);
        return new RedirectResponse('/admin/managecolors/');

    }
}