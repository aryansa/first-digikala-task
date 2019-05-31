<?php
/**
 * Created by PhpStorm.
 * User: aryanpc
 * Date: 5/21/19
 * Time: 3:03 AM
 */

namespace App\Services;


interface CacheService
{
    function set(string $name, ?string $value,int $ttl);

    function get(string $name): ?string;

    function delete(string $name): void;
}