<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Constant;

use AbterPhp\Framework\Constant\Route as FrameworkRoute;

class Route extends FrameworkRoute
{
    public const CONTACT = 'contact';

    public const CONTACT_FORMS_LIST   = 'contact-forms-list';
    public const CONTACT_FORMS_NEW    = 'contact-forms-new';
    public const CONTACT_FORMS_CREATE = 'contact-forms-create';
    public const CONTACT_FORMS_EDIT   = 'contact-forms-edit';
    public const CONTACT_FORMS_UPDATE = 'contact-forms-update';
    public const CONTACT_FORMS_DELETE = 'contact-forms-delete';
    public const CONTACT_FORMS_BASE   = 'contact-forms-base';
    public const CONTACT_FORMS_ENTITY = 'contact-forms-entity';
}
