<?php

namespace App;

use App\Controllers\AdminColorsController;
use App\Controllers\AdminController;
use App\Controllers\AdminProductsController;
use App\Controllers\AdminVariantsController;
use App\Controllers\CompletionSuggesterController;
use App\Controllers\InstallControllers;
use App\Controllers\LoginController;
use App\Controllers\LogoutController;
use App\Controllers\ProductsController;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Routes
{

    public static function register(RouteCollection $collection)
    {
        $routes = $collection;
        self::addRoute($routes, '/', ProductsController::class, 'index');
        self::addRoute($routes, 'login/', LoginController::class, 'index');
        self::addRoute($routes, 'logout/', LogoutController::class, 'index');
        self::addRoute($routes, '/admin/', AdminController::class, "index");
        self::addRoute($routes, '/admin/manageproduct/', AdminProductsController::class, "index");
        self::addRoute($routes, '/admin/manageproduct/new/', AdminProductsController::class, "new");
        self::addRoute($routes, '/admin/manageproduct/update/{id}', AdminProductsController::class, "update");
        self::addRoute($routes, '/admin/manageproduct/delete/{id}', AdminProductsController::class, "delete");
        self::addRoute($routes, '/admin/managevariants/new/', AdminVariantsController::class, "new");
        self::addRoute($routes, '/admin/managevariants/', AdminVariantsController::class, "index");
        self::addRoute($routes, '/admin/managevariants/update/{id}', AdminVariantsController::class, "update");
        self::addRoute($routes, '/admin/managevariants/delete/{id}', AdminVariantsController::class, "delete");
        self::addRoute($routes, '/admin/mana/viewgeadmins/', AdminController::class, "manageAdmin");
        self::addRoute($routes, '/admin/managecolors/', AdminColorsController::class, "index");
        self::addRoute($routes, '/admin/managecolors/new/', AdminColorsController::class, "new");
        self::addRoute($routes, '/admin/managecolors/update/{id}', AdminColorsController::class, "update");
        self::addRoute($routes, '/admin/managecolors/delete/{id}', AdminColorsController::class, "delete");
        self::addRoute($routes, '/view/{id}', ProductsController::class, "show");
        self::addRoute($routes, '/install/', InstallControllers::class, "install");
        self::addRoute($routes, '/suggester/', CompletionSuggesterController::class, "index");
    }
    
    private static function addRoute(RouteCollection $routeCollection, string $url, string $controller, string $method)
    {
        $routeCollection->add($controller . "::" . $method, new Route($url, ['_controller' => $controller . "::" . $method]));
    }

}