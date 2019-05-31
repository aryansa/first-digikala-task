<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/26/19
 * Time: 6:41 AM
 */

namespace App\Models;


use App\App;
use App\Exceptions\WrongInputException;
use App\Services;
use App\Services\MySqlService;

class ColorModel
{
    public function update(?int $id, array $values)
    {
        if ((!isset($values['name']) ||
            empty($values['name']))) {
            throw new WrongInputException('`Name` is empty.');
        }
        if ($id) {
            Services::mysqlService()->update(
                'color',
                ['name'],
                [$values['name'], $id]
            );
        } else {
            Services::mysqlService()->insert(
                'color',
                ['name'],
                [[$values['name']]]
            );
        }
    }

    public function delete(int $id)
    {
        Services::mysqlService()->delete('color', [$id]);
    }
}