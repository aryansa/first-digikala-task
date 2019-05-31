<?php

namespace App\Services;

class ApcuService implements CacheService
{
    function set(string $name, ?string $value, int $ttl)
    {
        apcu_store($name, $value, $ttl);
    }

    function get(string $name): ?string
    {
        return apcu_fetch($name);
    }

    function delete(string $name): void
    {
        apcu_delete($name);
    }

}