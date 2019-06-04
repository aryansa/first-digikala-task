<?php

namespace App\Views;

use App\Misc\Containers\Product;
use App\Misc\Containers\ProductSearch;
use App\Services;
use Symfony\Component\HttpFoundation\Request;

class ProductsView
{
    /**
     * @param array $ids
     * @return Product[]|null
     */
    public function getProducts(array $ids): ?array
    {
        if (empty($ids)) {
            return [];
        }

        $result = Services::mysqlService()->select('products', $ids);

        if (empty($result)) {
            return [];
        }

        $containers = [];
        foreach ($result as $id => $fields) {
            $containers[$fields['id']] = new Product($fields['id'], $fields['title'], $fields['description']);
        }

        return $containers;
    }

    public function getProduct(int $id): Product
    {
        return $this->getProducts([$id])[$id];
    }

    public function getAll()
    {
        $list = array_column(Services::mysqlService()->selectAll('products'), 'id');

        return $this->getProducts($list);
    }

    public function search(Request $request)
    {
        $productSearch = new ProductSearch();
        if (strlen($minPrice = $request->get('min_price'))) {
            $productSearch->setMinPrice($minPrice);
        }

        if (strlen($maxPrice = $request->get('max_price'))) {
            $productSearch->setMaxPrice($maxPrice);
        }

        if (!empty($keyword = $request->get('keyword'))) {
            $productSearch->setKeyword($keyword);
        }

        if (!empty($colors = $request->get('color'))) {
            $productSearch->setColors($colors);
        }

        $ids = Services::elasticSearchService()->search($productSearch);

        return $this->getProducts($ids);
    }

    public function productSuggest($keyword)
    {
        $ids = Services::elasticSearchService()->suggest($keyword);

        return $this->getProducts($ids);

    }

    public function getProductVariants(int $id)
    {

        $results = Services::cacheService()->get("productvariants:$id");
        if (!empty($results)) {
            return json_decode($results, 1);
        }

        $results = Services::mysqlService()->query(
            "SELECT id FROM variants WHERE product_id = ?",
            [$id]
        );

        $results = array_column($results, 'id');
        Services::cacheService()->set("productvariants:$id", json_encode($results), 3600);

        return $results;
    }
}