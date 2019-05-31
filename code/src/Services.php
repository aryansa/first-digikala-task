<?php

namespace App;


use App\Services\AdminAccountService;
use App\Services\ApcuService;
use App\Services\AuthService;
use App\Services\CacheService;
use App\Services\ConfigService;
use App\Services\CryptoService;
use App\Services\ElasticSearchService;
use App\Services\MemcachedService;
use App\Services\MySqlService;
use App\Services\SessionService;
use App\Services\TwigService;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Services
{
    public static function register(ContainerBuilder $containerBuilder)
    {
        $containerBuilder->register(ElasticSearchService::class, ElasticSearchService::class);
        $containerBuilder->register(ApcuService::class, ApcuService::class);
        $containerBuilder->register(CacheService::class, MemcachedService::class);
        $containerBuilder->register(ConfigService::class, ConfigService::class);
        $containerBuilder->register(MySqlService::class, MySqlService::class);
        $containerBuilder->register(CryptoService::class, CryptoService::class);
        $containerBuilder->register(TwigService::class, TwigService::class);
        $containerBuilder->register(AuthService::class, AuthService::class);
        $containerBuilder->register(AdminAccountService::class, AdminAccountService::class);
        $containerBuilder->register(SessionService::class, SessionService::class);
    }

    public static function twigService(): TwigService
    {
        return App::getContainer()->get(TwigService::class);
    }

    public static function mysqlService(): MySqlService
    {
        return App::getContainer()->get(MySqlService::class);
    }

    public static function elasticSearchService(): ElasticSearchService
    {
        return App::getContainer()->get(ElasticSearchService::class);
    }

    public static function accountService(): AdminAccountService
    {
        return App::getContainer()->get(AdminAccountService::class);
    }

    public static function cacheService(): CacheService
    {
        return App::getContainer()->get(CacheService::class);
    }

    public static function configService(): ConfigService
    {
        return App::getContainer()->get(ConfigService::class);
    }

    public static function sessionService(): SessionService
    {
        return App::getContainer()->get(SessionService::class);
    }

    public static function cryptoService(): CryptoService
    {
        return App::getContainer()->get(CryptoService::class);
    }

    public static function apcuService(): ApcuService
    {
        return App::getContainer()->get(ApcuService::class);
    }
}