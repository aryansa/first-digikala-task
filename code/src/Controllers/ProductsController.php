<?php

namespace App\Controllers;

use App\Misc\Response\TemplateResponse;
use App\Views\ColorView;
use App\Views\ProductsView;
use App\Views\VariantsView;
use Symfony\Component\HttpFoundation\Request;

class ProductsController
{
    public function index(Request $request)
    {
        return TemplateResponse::make(
            "index.twig",
            [
                'title' => 'Products',
                'products' => (new ProductsView())->search($request),
                'colors' => (new ColorView())->getAll()
            ]
        );
    }

    public function show(int $id)
    {
        return TemplateResponse::make(
            "showproduct.twig",
            [
                'title' => 'prd',
                'product' => (new ProductsView())->getProduct($id),
                'variants' => (new VariantsView())
                    ->getVariants((new ProductsView())->getProductVariants($id))
            ]
        );
    }
}
