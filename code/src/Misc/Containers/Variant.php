<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/30/19
 * Time: 2:42 AM
 */

namespace App\Misc\Containers;


class Variant
{
    /** @var int $id */
    private $id;

    /** @var int $productId */
    private $productId;

    /** @var int $colorId */
    private $colorId;

    /** @var int $price */
    private $price;

    /** @var Product $product */
    private $product;

    /** @var Color $color */
    private $color;

    /**
     * Variant constructor.
     * @param int $id
     * @param int $productId
     * @param int $colorId
     * @param int $price
     */
    public function __construct(int $id, int $productId, int $colorId, int $price)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->colorId = $colorId;
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getProductId(): int
    {
        return $this->productId;
    }

    /**
     * @return int
     */
    public function getColorId(): int
    {
        return $this->colorId;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return Color
     */
    public function getColor(): Color
    {
        return $this->color;
    }

    /**
     * @param Color $color
     */
    public function setColor(Color $color): void
    {
        $this->color = $color;
    }


}