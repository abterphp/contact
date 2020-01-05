<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Grid\Factory;

use AbterPhp\Admin\Grid\Factory\BaseFactory;
use AbterPhp\Admin\Grid\Factory\GridFactory;
use AbterPhp\Admin\Grid\Factory\PaginationFactory;
use AbterPhp\Contact\Constant\Route;
use AbterPhp\Contact\Grid\Factory\Table\Form as TableFactory;
use AbterPhp\Contact\Grid\Factory\Table\Header\Form as HeaderFactory;
use AbterPhp\Contact\Grid\Filters\Form as Filters;
use AbterPhp\Framework\Constant\Html5;
use AbterPhp\Framework\Grid\Action\Action;
use AbterPhp\Framework\Grid\Component\Actions;
use Opulence\Routing\Urls\UrlGenerator;

class Form extends BaseFactory
{
    private const GETTER_IDENTIFIER = 'getIdentifier';
    private const GETTER_TO_NAME    = 'getToName';
    private const GETTER_TO_EMAIL   = 'getToEmail';

    /**
     * Form constructor.
     *
     * @param UrlGenerator      $urlGenerator
     * @param PaginationFactory $paginationFactory
     * @param TableFactory      $tableFactory
     * @param GridFactory       $gridFactory
     * @param Filters           $filters
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        PaginationFactory $paginationFactory,
        TableFactory $tableFactory,
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
            HeaderFactory::GROUP_IDENTIFIER => static::GETTER_IDENTIFIER,
            HeaderFactory::GROUP_TO_NAME    => static::GETTER_TO_NAME,
            HeaderFactory::GROUP_TO_EMAIL   => static::GETTER_TO_EMAIL,
        ];
    }

    /**
     * @return Actions
     */
    protected function getRowActions(): Actions
    {
        $attributeCallbacks = $this->getAttributeCallbacks();

        $editAttributes = [
            Html5::ATTR_HREF => Route::CONTACT_FORMS_EDIT,
        ];

        $deleteAttributes = [
            Html5::ATTR_HREF => Route::CONTACT_FORMS_DELETE,
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
