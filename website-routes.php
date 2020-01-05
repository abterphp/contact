<?php

declare(strict_types=1);

use AbterPhp\Contact\Constant\Route as RouteConstant;
use Opulence\Routing\Router;

/**
 * ----------------------------------------------------------
 * Create all of the routes for the HTTP kernel
 * ----------------------------------------------------------
 *
 * @var Router $router
 */
$router->group(
    ['controllerNamespace' => 'AbterPhp\Contact\Http\Controllers'],
    function (Router $router) {
        /** @see \AbterPhp\Contact\Http\Controllers\Website\Contact::submit() */
        $router->post(
            '/contact/:formIdentifier',
            'Website\Contact@submit',
            [RouteConstant::OPTION_NAME => RouteConstant::CONTACT]
        );
    }
);
