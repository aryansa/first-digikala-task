<?php

namespace App\Models;


use App\Exceptions\WrongInputException;
use App\Services;

class VariantsModel
{
    public function update(?int $id, array $values)
    {
        Services::accountService()->checkAdminUser();

        if ((!isset($values['product_id']) ||
            empty($values['product_id']))) {
            throw new WrongInputException('`Product` is not selected');
        }
        if ((!isset($values['color_id']) ||
            empty($values['color_id']))) {
            throw new WrongInputException('`Color` is not selected');
        }
        if ((!isset($values['price']) ||
            !is_numeric($values['price']))) {
            throw new WrongInputException('`Price` is not integer.');
        }

        $productId = $values['product_id'];
        if ($id) {
            Services::mysqlService()->update(
                'variants',
                ['product_id', 'color_id', 'price'],
                [$values['product_id'], $values['color_id'], $values['price'], $id]
            );

        } else {
            $ids = Services::mysqlService()->insert(
                'variants',
                ['product_id', 'color_id', 'price'],
                [[$values['product_id'], $values['color_id'], $values['price']]]
            );
            $id = $ids[0];
        }
        Services::cacheService()->delete("productvariants:$productId");

        (new ProductModel())->indexForSearch($productId);

        return $id;
    }


    public function delete(string $id, string $pid)
    {
        Services::mysqlService()->delete('variants', [$id]);
        Services::cacheService()->delete("productvariants:$pid");
        Services::elasticSearchService()->deleteVariant($pid, $id);
    }
}