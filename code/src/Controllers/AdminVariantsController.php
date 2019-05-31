<?php

namespace App\Controllers;


use App\Exceptions\WrongInputException;
use App\Misc\Response\TemplateResponse;
use App\Models\VariantsModel;
use App\Services;
use App\Views\ColorView;
use App\Views\ProductsView;
use App\Views\VariantsView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminVariantsController
{
    public function index()
    {
        Services::accountService()->checkAdminUser();

        return TemplateResponse::make(
            "indexvariants.twig",
            [
                'title' => 'variants list',
                'variants' => (new VariantsView())->getAll()
            ]
        );
    }

    public function update(Request $request, int $id)
    {
        Services::accountService()->checkAdminUser();

        $messages = [];
        if ($request->getMethod() == 'POST') {
            $errorMessage = null;
            try {
                (new VariantsModel())->update($id, $request->request->all());

                $messages['success'] = 'Variant updated';
            } catch (WrongInputException $e) {
                $messages['error'] = $e->getMessage();
            }
        }

        return TemplateResponse::make(
            "managevariant.twig",
            [
                'title' => 'update variant',
                'action' => "/admin/managevariants/update/$id",
                'variant' => (new VariantsView())->getVariant($id),
                'messages' => $messages,
                'colors' => (new ColorView())->getAll(),
                'products' => (new ProductsView())->getAll(),
            ]
        );
    }

    public function new(Request $request)
    {
        Services::accountService()->checkAdminUser();

        $messages = [];
        if ($request->getMethod() == 'POST') {
            try {
                (new VariantsModel())->update(null, $request->request->all());
                $messages['success'] = 'Variant insert';
            } catch (WrongInputException $e) {
                $messages['error'] = $e->getMessage();
            }
        }
        
        return TemplateResponse::make(
            "managevariant.twig",
            [
                'title' => 'new variant',
                'action' => "/admin/managevariants/new/",
                'messages' => $messages,
                'colors' => (new ColorView())->getAll(),
                'products' => (new ProductsView())->getAll(),
            ]
        );
    }

    public function delete(int $id, Request $request)
    {
        Services::accountService()->checkAdminUser();

        (new VariantsModel())->delete($id, $request->get("pid"));
        return new RedirectResponse('/admin/managevariants/');
    }
}