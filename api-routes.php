<?php

declare(strict_types=1);

use AbterPhp\Admin\Config\Routes as RoutesConfig;
use AbterPhp\Admin\Http\Middleware\Api;
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
        $router->group(
            [
                'path' => RoutesConfig::getApiBasePath(),
                'middleware' => [
                    Api::class,
                ],
            ],
            function (Router $router) {
                $entities = [
                    'contactforms' => 'Form',
                ];

                foreach ($entities as $route => $controllerName) {
                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::get() */
                    $router->get(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@get"
                    );

                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::list() */
                    $router->get(
                        "/${route}",
                        "Api\\${controllerName}@list"
                    );

                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::create() */
                    $router->post(
                        "/${route}",
                        "Api\\${controllerName}@create"
                    );

                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::update() */
                    $router->put(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@update"
                    );

                    /** @see \AbterPhp\Contact\Http\Controllers\Api\Form::delete() */
                    $router->delete(
                        "/${route}/:entityId",
                        "Api\\${controllerName}@delete"
                    );
                }

                /** @see \AbterPhp\Contact\Http\Controllers\Api\Message::create() */
                $router->post(
                    "/contactforms/:entityId/messages",
                    "Api\\Message@create"
                );
            }
        );
    }
);
