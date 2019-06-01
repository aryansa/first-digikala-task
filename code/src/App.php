<?php

namespace App;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

class App
{
    private static $dispatcher;
    private static $container;

    public function run()
    {
        $session = Services::sessionService();

        $routes = new RouteCollection();
        Routes::register($routes);
        $request = Request::createFromGlobals();
        $request->setSession($session->getSession());
        $matcher = new UrlMatcher($routes, new RequestContext());

        $dispatcher = self::getDispatcher();
        $dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));
        Events::register($dispatcher);
        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

        $response = $kernel->handle($request);
        $response->send();
        $kernel->terminate($request, $response);


    }

    /**
     * @return mixed
     */
    public static function getDispatcher()
    {
        if (!isset(self::$dispatcher))
            self::$dispatcher = new EventDispatcher();

        return self::$dispatcher;
    }

    /**
     * @return mixed
     */
    public static function getContainer(): ContainerBuilder
    {
        if (!isset(self::$container)) {
            self::$container = new ContainerBuilder();
            Services::register(self::$container);
        }
        return self::$container;
    }
}