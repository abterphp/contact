<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Constant;

use AbterPhp\Framework\Constant\Routes as FrameworkRoutes;

class Routes extends FrameworkRoutes
{
    const ROUTE_CONTACT = 'contact';

    const PATH_CONTACT = '/contact/:formIdentifier';

    const ROUTE_CONTACT_FORMS        = 'contact-forms';
    const ROUTE_CONTACT_FORMS_NEW    = 'contact-forms-new';
    const ROUTE_CONTACT_FORMS_EDIT   = 'contact-forms-edit';
    const ROUTE_CONTACT_FORMS_DELETE = 'contact-forms-delete';
    const PATH_CONTACT_FORMS         = '/contact-forms';
    const PATH_CONTACT_FORMS_NEW     = '/contact-forms/new';
    const PATH_CONTACT_FORMS_EDIT    = '/contact-forms/:entityId/edit';
    const PATH_CONTACT_FORMS_DELETE  = '/contact-forms/:entityId/delete';
}
