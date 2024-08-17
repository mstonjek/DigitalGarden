<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{
    use Nette\StaticClass;

    public static function createRouter(): RouteList
    {
        $router = new RouteList();

        $router->withModule('Back')->addRoute('admin/<presenter>[/<action=default>][/<id>]', [
            'presenter' => 'Homepage',
        ]);

        $router->withModule('Front')->addRoute('<presenter>[/<action=default>][/<id>]', [
            'presenter' => 'Homepage',
        ]);
        $router->addRoute('<presenter>[/<action=default>]');

        return $router;
    }
}
