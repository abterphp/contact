<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Constant;

use AbterPhp\Framework\Constant\Routes as FrameworkRoutes;

class Routes extends FrameworkRoutes
{
    const ROUTE_CONTACT = 'contact';

    const PATH_CONTACT = '/contact/:formIdentifier';

    const ROUTE_CONTACT_FORMS        = 'contactforms';
    const ROUTE_CONTACT_FORMS_NEW    = 'contactforms-new';
    const ROUTE_CONTACT_FORMS_EDIT   = 'contactforms-edit';
    const ROUTE_CONTACT_FORMS_DELETE = 'contactforms-delete';
    const PATH_CONTACT_FORMS         = '/contactforms';
    const PATH_CONTACT_FORMS_NEW     = '/contactforms/new';
    const PATH_CONTACT_FORMS_EDIT    = '/contactforms/:entityId/edit';
    const PATH_CONTACT_FORMS_DELETE  = '/contactforms/:entityId/delete';
}
