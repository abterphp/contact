<?php

use AbterPhp\Contact;
use AbterPhp\Framework\Constant\Event;
use AbterPhp\Framework\Constant\Module;
use AbterPhp\Framework\Constant\Priorities;

return [
    Module::IDENTIFIER         => 'AbterPhp\Contact',
    Module::DEPENDENCIES       => ['AbterPhp\Website'],
    Module::ENABLED            => true,
    Module::HTTP_BOOTSTRAPPERS => [
        Bootstrappers\Http\Controllers\Website\ContactBootstrapper::class,
    ],
    Module::EVENTS             => [
        Event::TEMPLATE_ENGINE_READY => [
            /** @see \AbterPhp\Contact\Events\Listeners\TemplateInitializer::handle */
            sprintf('%s@handle', Events\Listeners\TemplateInitializer::class),
        ],
        Event::DASHBOARD_READY       => [
            /** @see \AbterPhp\Contact\Events\Listeners\DashboardBuilder::handle */
            sprintf('%s@handle', Events\Listeners\DashboardBuilder::class),
        ],
    ],
    Module::ROUTE_PATHS        => [
        Priorities::NORMAL => [
            __DIR__ . '/routes.php',
        ],
    ],
    Module::MIGRATION_PATHS    => [
        Priorities::NORMAL => [
            realpath(__DIR__ . '/Databases/Migrations'),
        ],
    ],
];
