<?php

namespace App\Services;

use App\Services;
use Symfony\Component\Yaml\Yaml;

class ConfigService
{
    private $config;

    public function __construct()
    {
        $cache = Services::apcuService();
        if (empty($config = $cache->get('config'))) {
            $this->config = Yaml::parseFile('../config/config.yaml');
            $cache->set('config', json_encode($this->config), 3600);
        } else {
            $this->config = json_decode($config, 1);
        }
    }

    public function getConfig()
    {
        return $this->config;
    }
}