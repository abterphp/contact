<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Http\Controllers\Admin\Form;

use AbterPhp\Admin\Http\Controllers\Admin\FormAbstract;
use AbterPhp\Contact\Domain\Entities\Form as Entity;
use AbterPhp\Contact\Form\Factory\Form as FormFactory;
use AbterPhp\Contact\Orm\FormRepo as Repo;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Session\FlashService;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use Opulence\Sessions\ISession;
use Psr\Log\LoggerInterface;

class ContactForm extends FormAbstract
{
    const ENTITY_SINGULAR = 'contactForm';
    const ENTITY_PLURAL   = 'contactForms';

    const ENTITY_TITLE_SINGULAR = 'contact:contactForm';
    const ENTITY_TITLE_PLURAL   = 'contact:contactForms';

    const ROUTING_PATH = 'contact-forms';

    /** @var string */
    protected $resource = 'contactforms';

    /**
     * ContactForm constructor.
     *
     * @param FlashService     $flashService
     * @param ITranslator      $translator
     * @param UrlGenerator     $urlGenerator
     * @param LoggerInterface  $logger
     * @param Repo             $repo
     * @param ISession         $session
     * @param FormFactory      $formFactory
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        FlashService $flashService,
        ITranslator $translator,
        UrlGenerator $urlGenerator,
        LoggerInterface $logger,
        Repo $repo,
        ISession $session,
        FormFactory $formFactory,
        IEventDispatcher $eventDispatcher
    ) {
        parent::__construct(
            $flashService,
            $translator,
            $urlGenerator,
            $logger,
            $repo,
            $session,
            $formFactory,
            $eventDispatcher
        );
    }

    /**
     * @param string $entityId
     *
     * @return Entity
     */
    protected function createEntity(string $entityId): IStringerEntity
    {
        return new Entity($entityId, '', '', '', '', '', '', 0);
    }
}
