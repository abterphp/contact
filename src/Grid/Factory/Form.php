<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Grid\Factory;

use AbterPhp\Admin\Grid\Factory\BaseFactory;
use AbterPhp\Admin\Grid\Factory\GridFactory;
use AbterPhp\Admin\Grid\Factory\PaginationFactory;
use AbterPhp\Contact\Constant\Routes;
use AbterPhp\Contact\Grid\Factory\Table\Form as Table;
use AbterPhp\Contact\Grid\Filters\Form as Filters;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Component\Actions;
use Opulence\Routing\Urls\UrlGenerator;

class Form extends BaseFactory
{
    const GROUP_IDENTIFIER = 'contactForm-identifier';
    const GROUP_TO_NAME    = 'contactForm-to-name';
    const GROUP_TO_EMAIL   = 'contactForm-to-email';

    const GETTER_IDENTIFIER = 'getIdentifier';
    const GETTER_TO_NAME    = 'getToName';
    const GETTER_TO_EMAIL   = 'getToEmail';

    /**
     * Form constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param Table             $tableFactory
     * @param GridFactory       $gridFactory
     * @param Filters           $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        Table $tableFactory,
        GridFactory $gridFactory,
        Filters $filters
    ) {
        parent::__construct($urlGenerator, $paginationFactory, $tableFactory, $gridFactory, $filters);
    }

    /**
     * @return array
     */
    public function getGetters(): array
    {
        return [
            static::GROUP_IDENTIFIER => static::GETTER_IDENTIFIER,
            static::GROUP_TO_NAME    => static::GETTER_TO_NAME,
            static::GROUP_TO_EMAIL   => static::GETTER_TO_EMAIL,
        ];
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes = [
            Html5::ATTR_HREF => Routes::ROUTE_CONTACT_FORMS_EDIT,
        ];

        $deleteAttributes = [
            Html5::ATTR_HREF => Routes::ROUTE_CONTACT_FORMS_DELETE,
        ];

        $cellActions   = new Actions();
        $cellActions[] = new Action(
            static::LABEL_EDIT,
            $this->editIntents,
            $editAttributes,
            $attributeCallbacks,
            Html5::TAG_A
        );
        $cellActions[] = new Action(
            static::LABEL_DELETE,
            $this->deleteIntents,
            $deleteAttributes,
            $attributeCallbacks,
            Html5::TAG_A
        );

        return $cellActions;
    }
}
