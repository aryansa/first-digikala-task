<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/26/19
 * Time: 6:45 AM
 */

namespace App\Views;


use App\Misc\Containers\Variant;
use App\Services;

class VariantsView
{
    /**
     * @param array $ids
     * @return Variant[]|null
     */
    public function getVariants(array $ids): ?array
    {
        if (empty($ids)) {
            return [];
        }
        $result = Services::mysqlService()->select('variants', $ids);
        if (empty($result)) {
            return [];
        }

        $containers = [];

        foreach ($result as $id => $fields) {
            if ($fields['product_id'] != null) {
                $variant = new Variant($fields['id'], $fields['product_id'], $fields['color_id'], $fields['price']);
                $product = (new ProductsView())->getProduct($variant->getProductId());
                $variant->setProduct($product);

                $color = (new ColorView())->getColor($variant->getColorId());
                $variant->setColor($color);

                $containers[$fields['id']] = $variant;
            }
        }

        return $containers;
    }

    public function getAll()
    {
        $list = array_column(Services::mysqlService()->selectAll('variants'), 'id');

        return $this->getVariants($list);
    }


    public function getVariant(int $id): Variant
    {
        return $this->getVariants([$id])[$id];
    }

}