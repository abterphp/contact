<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Grid\Factory\Table\Header;

use AbterPhp\Admin\Grid\Factory\Table\HeaderFactory;

class Form extends HeaderFactory
{
    public const GROUP_IDENTIFIER = 'contactForm-identifier';
    public const GROUP_TO_NAME    = 'contactForm-to-name';
    public const GROUP_TO_EMAIL   = 'contactForm-to-email';

    private const HEADER_IDENTIFIER = 'contact:formIdentifier';
    private const HEADER_TO_NAME    = 'contact:formToName';
    private const HEADER_TO_EMAIL   = 'contact:formToEmail';

    /** @var array */
    protected $headers = [
        self::GROUP_IDENTIFIER => self::HEADER_IDENTIFIER,
        self::GROUP_TO_NAME    => self::HEADER_TO_NAME,
        self::GROUP_TO_EMAIL   => self::HEADER_TO_EMAIL,
    ];

    /** @var array */
    protected $inputNames = [
        self::GROUP_IDENTIFIER => 'identifier',
        self::GROUP_TO_NAME    => 'to_name',
        self::GROUP_TO_EMAIL   => 'to_email',
    ];

    /** @var array */
    protected $fieldNames = [
        self::GROUP_IDENTIFIER => 'category_forms.identifier',
        self::GROUP_TO_NAME    => 'category_forms.to_name',
        self::GROUP_TO_EMAIL   => 'category_forms.to_email',
    ];
}
