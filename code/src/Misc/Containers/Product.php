<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/30/19
 * Time: 2:42 AM
 */

namespace App\Misc\Containers;


class Product
{
    /** @var int $id */
    private $id;
    /** @var string $title */
    private $title;
    /** @var string $description */
    private $description;

    /**
     * Product constructor.
     * @param $id
     * @param $title
     * @param $description
     */
    public function __construct($id, $title, $description)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}