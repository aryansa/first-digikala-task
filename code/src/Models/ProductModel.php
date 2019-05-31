<?php

namespace App\Models;

use App\Exceptions\WrongInputException;
use App\Services;
use App\Views\ProductsView;
use App\Views\VariantsView;
use Elasticsearch\Common\Exceptions\Missing404Exception;

class ProductModel
{
    public function update(?int $id, array $values)
    {
        if ((!isset($values['title']) ||
            empty($values['title']))) {
            throw new WrongInputException('`Title` is empty');
        }
        if ((!isset($values['description']) ||
            empty($values['description']))) {
            throw new WrongInputException('`Description` is empty.');
        }
        if ($id) {
            Services::mysqlService()->update(
                'products',
                ['title', 'description'],
                [$values['title'], $values['description'], $id]
            );
        } else {
            $ids = Services::mysqlService()->insert(
                'products',
                ['title', 'description'],
                [[$values['title'], $values['description']]]
            );
            $id = $ids[0];
        }

        $this->indexForSearch($id);

        return $id;
    }

    public function indexForSearch(int $id)
    {
        $product = (new ProductsView())->getProduct($id);
        $data = [
            'id' => $product->getId(),
            'title' => $product->getTitle(),
            'description' => $product->getDescription(),
            'variants' => []
        ];

        $variantIds = array_column(
            Services::mysqlService()->query('SELECT id FROM variants WHERE product_id = ?', [$id]) ?? [],
            'id'
        );

        if ($variantIds) {
            $variants = (new VariantsView())->getVariants($variantIds);
            foreach ($variants as $variant) {
                $data['variants'][] = [
                    'id' => $variant->getId(),
                    'color_id' => $variant->getColorId(),
                    'price' => $variant->getPrice()
                ];

            }
        };

        Services::elasticSearchService()->index($data);
    }

    public function delete(int $id)
    {
        Services::mysqlService()->delete('products', [$id]);
        Services::cacheService()->delete("productvariants:$id");
        try {
            Services::elasticSearchService()->delete($id);
        } catch (Missing404Exception $exception) {

        }

    }
}