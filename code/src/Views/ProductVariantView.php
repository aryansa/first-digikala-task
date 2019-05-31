<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/28/19
 * Time: 6:12 PM
 */

namespace App\Views;


use App\App;
use App\Services\MemcachedService;
use App\Services\MySqlService;

class ProductVariantView
{

    /** @var MySqlService $serviceMysql */
    private $serviceMysql;
    /** @var MemcachedService $cacheservice */
    private $cacheservice;

    /**
     * ProductVariantView constructor.
     */
    public function __construct()
    {
        $this->serviceMysql = App::getContainer()->get(MySqlService::class);
        $this->cacheservice = App::getContainer()->get(MemcachedService::class);
    }

    public static function getCacheKey($id): string
    {
        return "prvariant:$id";
    }

    public function getList()
    {

    }

    public function getOne(int $id)
    {


        $cacheKey = self::getCacheKey($id);
        $results = [];
        $toFromDatabase = [];
        $value = $this->cacheservice->get($cacheKey);
        if (!empty($value)) {
            $results = json_decode($value, 1);
        } else {
            $toFromDatabase[] = $id;
        }

        if (!empty($toFromDatabase)) {
            $variantid = $this->serviceMysql->query("SELECT va.id
              FROM variants va
              LEFT JOIN products p ON p.id = va.product_id
              WHERE p.id = ?
              ", $toFromDatabase);
            foreach ($variantid as $var) {

                $variantsid [] = $var['id'];

            }
            if ($variantsid != null) {
                $value = $this->serviceMysql->select('variants', $variantsid);

                $colors = [];
                $prices = [];
                foreach ($value as $item) {
                    $colors[] = $item['color_id'];
                    $prices[] = $item['price'];
                }

                $colors = $this->serviceMysql->select('color', $colors);
                $colornames = [];
                foreach ($colors as $color) {
                    $colornames[] = $color['name'];
                }
                for ($i = 0; $i < count($value); $i++) {
                    $results[$variantsid[$i]] = ['id' => $variantsid[$i], 'price' => $prices[$i], 'color' => $colornames[$i],'color_id' =>$colors[$i]];
                }
            }
            $prd = new ProductsView();
            $prd = $prd->getProducts($id);
            $results = ['product' => $prd, 'variants' => $results];
            $this->cacheservice->set(self::getCacheKey($id), json_encode($results), 3500);

        }
        return $results;

    }

}