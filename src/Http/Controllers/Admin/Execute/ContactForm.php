<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Http\Controllers\Admin\Execute;

use AbterPhp\Admin\Http\Controllers\Admin\ExecuteAbstract;
use AbterPhp\Contact\Service\Execute\Form as RepoService;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class ContactForm extends ExecuteAbstract
{
    const ENTITY_SINGULAR = 'contactForm';
    const ENTITY_PLURAL   = 'contactForms';

    const ENTITY_TITLE_SINGULAR = 'contact:contactForm';
    const ENTITY_TITLE_PLURAL   = 'contact:contactForms';

    const ROUTING_PATH = 'contact-forms';

    /**
     * ContactForm constructor.
     *
     * @param FlashService    $flashService
     * @param LoggerInterface $logger
     * @param ITranslator     $translator
     * @param UrlGenerator    $urlGenerator
     * @param RepoService     $repoService
     * @param ISession        $session
     */
    public function __construct(
        FlashService $flashService,
        LoggerInterface $logger,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        RepoService $repoService,
        ISession $session
    ) {
        parent::__construct(
            $flashService,
            $logger,
            $translator,
            $urlGenerator,
            $repoService,
            $session
        );
    }
}
