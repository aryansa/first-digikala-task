<?php

namespace App\Views;


use App\Misc\Containers\Color;
use App\Services;

class ColorView
{
    /**
     * @param array $ids
     * @return Color[]|null
     */
    public function getColors(array $ids): ?array
    {
        if (empty($ids)) {
            return [];
        }

        $result = Services::mysqlService()->select('color', $ids);

        if (empty($result)) {
            return [];
        }

        $containers = [];
        foreach ($result as $id => $fields) {
            $containers[$fields['id']] = new Color($fields['id'], $fields['name']);
        }

        return $containers;
    }

    public function getAll()
    {
        $list = array_column(Services::mysqlService()->selectAll('color'), 'id');

        return $this->getColors($list);
    }

    public function getColor(int $id): Color
    {
        return $this->getColors([$id])[$id];
    }
}