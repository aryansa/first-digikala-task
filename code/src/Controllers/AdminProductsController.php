<?php

namespace App\Controllers;

use App\Exceptions\WrongInputException;
use App\Misc\Response\TemplateResponse;
use App\Models\ProductModel;
use App\Services;
use App\Views\ProductsView;
use App\Views\VariantsView;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class AdminProductsController
{
    public function index()
    {
        Services::accountService()->checkAdminUser();

        return TemplateResponse::make(
            "indexproducts.twig",
            [
                'title' => 'products list',
                'products' => (new ProductsView())->getAll(),
            ]
        );
    }

    public function new(Request $request)
    {
        $messages = [];
        if ($request->get('title') &&
            $request->getMethod() == 'POST') {
            $errorMessage = null;
            try {
                (new ProductModel())->update(null, $request->request->all());
                $messages['success'] = 'Product inserted.';
            } catch (WrongInputException $e) {
                $messages['error'] = $e->getMessage();
            }
        }
        return TemplateResponse::make(
            "newproduct.twig",
            [
                'title' => 'new product',
                'action' => "/admin/manageproduct/new/",
                'messages' => $messages
            ]
        );
    }

    public function update(Request $request, int $id)
    {
        Services::accountService()->checkAdminUser();

        if (empty($id)) {
            throw new WrongInputException('Id is empty.');
        }

        $messages = [];
        if ($request->getMethod() == 'POST') {
            $errorMessage = null;
            try {
                (new ProductModel())->update($id, $request->request->all());
                $messages['success'] = 'Product updated';
            } catch (WrongInputException $e) {
                $messages['error'] = $e->getMessage();
            }
        }

        return TemplateResponse::make("newproduct.twig", ['title' => 'update product',
            'action' => "/admin/manageproduct/update/$id",
            'messages' => $messages,
            'product' => (new ProductsView())->getProduct($id)
        ]);
    }

    public function delete(int $id)
    {
        if (empty($id)) {
            throw new WrongInputException('Id is empty.');
        }

        (new ProductModel())->delete($id);
        return new RedirectResponse('/admin/manageproduct/');
    }
}