<?php

use AbterPhp\Contact\Bootstrappers;
use AbterPhp\Contact\Events;
use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;
use AbterPhp\Framework\Constant\Priorities;

return [
    Module::IDENTIFIER         => 'AbterPhp\Contact',
    Module::DEPENDENCIES       => ['AbterPhp\Website'],
    Module::ENABLED            => true,
    Module::BOOTSTRAPPERS   => [
        Bootstrappers\Orm\OrmBootstrapper::class,
        Bootstrappers\Validation\ValidatorBootstrapper::class,
    ],
    Module::CLI_BOOTSTRAPPERS => [
        Bootstrappers\Database\MigrationsBootstrapper::class,
    ],
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Http\Controllers\Website\ContactBootstrapper::class,
    ],
    Module::EVENTS             => [
        Event::TEMPLATE_ENGINE_READY => [
            /** @see \AbterPhp\Contact\Events\Listeners\TemplateInitializer::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\TemplateInitializer::class)],
        ],
        Event::NAVIGATION_READY      => [
            /** @see \AbterPhp\Contact\Events\Listeners\NavigationBuilder::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\NavigationBuilder::class)],
        ],
        Event::DASHBOARD_READY       => [
            /** @see \AbterPhp\Contact\Events\Listeners\DashboardBuilder::handle */
            Priorities::NORMAL => [sprintf('%s@handle', Events\Listeners\DashboardBuilder::class)],
        ],
    ],
    Module::ROUTE_PATHS     => [
        Priorities::BELOW_NORMAL => [
            __DIR__ . '/admin-routes.php',
            __DIR__ . '/website-routes.php',
            __DIR__ . '/api-routes.php',
        ],
    ],
    Module::MIGRATION_PATHS    => [
        Priorities::NORMAL => [
            realpath(__DIR__ . '/src/Databases/Migrations'),
        ],
    ],
    Module::RESOURCE_PATH    => realpath(__DIR__ . '/resources'),
];
