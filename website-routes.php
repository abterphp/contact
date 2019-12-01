<?php

declare(strict_types=1);

use AbterPhp\Contact\Constant\Routes as RoutesConstant;
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
            RoutesConstant::PATH_CONTACT,
            'Website\Contact@submit',
            [RoutesConstant::OPTION_NAME => RoutesConstant::ROUTE_CONTACT]
        );
    }
);
