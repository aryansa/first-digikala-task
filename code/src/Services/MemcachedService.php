<?php

namespace App\Services;

use App\Services;
use Memcached;

class MemcachedService implements CacheService
{
    private $memcached;

    /**
     * MemcachedService constructor.
     */
    public function __construct()
    {

        $this->memcached = new Memcached();
        $config = Services::configService()->getConfig();

        $this->memcached->addServer(
            $config['memcached']['host'],
            $config['memcached']['port']
        );
    }

    function set(string $name, ?string $value, int $ttl)
    {
        return $this->memcached->set($name, $value, $ttl);
    }

    function get(string $name): ?string
    {
        return $this->memcached->get($name);
    }

    function delete(string $name): void
    {
        $this->memcached->delete($name);
    }
}