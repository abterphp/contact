<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Http\Controllers\Admin\Grid;

use AbterPhp\Admin\Http\Controllers\Admin\GridAbstract;
use AbterPhp\Contact\Service\RepoGrid\Form as RepoGrid;
use AbterPhp\Framework\Assets\AssetManager;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Psr\Log\LoggerInterface;

class ContactForm extends GridAbstract
{
    const ENTITY_SINGULAR = 'contactForm';
    const ENTITY_PLURAL   = 'contactForms';

    const ENTITY_TITLE_SINGULAR = 'contact:contactForm';
    const ENTITY_TITLE_PLURAL   = 'contact:contactForms';

    const ROUTING_PATH = 'contact-forms';

    /** @var string */
    protected $resource = 'contactforms';

    /**
     * Form constructor.
     *
     * @param FlashService     $flashService
     * @param LoggerInterface  $logger
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param AssetManager     $assets
     * @param RepoGrid         $repoGrid
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        FlashService $flashService,
        LoggerInterface $logger,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        AssetManager $assets,
        RepoGrid $repoGrid,
        IEventDispatcher $eventDispatcher
    ) {
        parent::__construct(
            $flashService,
            $logger,
            $translator,
            $urlGenerator,
            $assets,
            $repoGrid,
            $eventDispatcher
        );
    }
}
