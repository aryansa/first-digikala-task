<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 6/4/19
 * Time: 9:14 PM
 */

namespace App\Controllers;


use App\Views\ProductsView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CompletionSuggesterController
{
    public function index(Request $request)
    {
        $keyword = $request->get('keyword');
        $products = (new ProductsView())->productSuggest($keyword);
        $response = [];
        if (!empty($products)) {
            $response['code'] = ['400'];
            foreach ($products as $product) {
                $response['products'][] = [
                    'id' => $product->getId(),
                    'title' => $product->getTitle(),
                    'description' => $product->getDescription()
                ];
            }
        }
        else
        {
            $response['code'] = ['404'];
        }
        return Response::create(json_encode($response));

    }

}