<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Template\Loader;

use AbterPhp\Contact\Constant\Event;
use AbterPhp\Contact\Constant\Routes;
use AbterPhp\Contact\Form\Factory\Contact as FormFactory;
use AbterPhp\Framework\Events\FormReady;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Framework\Template\ParsedTemplate;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Routing\Urls\UrlGenerator;

class Contact implements ILoader
{
    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var FormFactory */
    protected $formFactory;

    /** @var ITranslator */
    protected $translator;

    /** @var IEventDispatcher */
    protected $eventDispatcher;

    /**
     * Contact constructor.
     *
     * @param UrlGenerator     $urlGenerator
     * @param FormFactory      $formFactory
     * @param ITranslator      $translator
     * @param IEventDispatcher $eventDispatcher
     */
    public function __construct(
        UrlGenerator $urlGenerator,
        FormFactory $formFactory,
        ITranslator $translator,
        IEventDispatcher $eventDispatcher
    ) {
        $this->urlGenerator    = $urlGenerator;
        $this->formFactory     = $formFactory;
        $this->translator      = $translator;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param ParsedTemplate[] $parsedTemplates
     *
     * @return IData[]
     */
    public function load(array $parsedTemplates): array
    {
        $identifiers = array_keys($parsedTemplates);

        $templateData = [];
        foreach ($identifiers as $identifier) {
            $url  = $this->urlGenerator->createFromName(Routes::ROUTE_CONTACT, $identifier);
            $form = $this->formFactory->create($url, RequestMethods::POST, '');

            $form->setTranslator($this->translator);

            $this->eventDispatcher->dispatch(Event::FORM_READY, new FormReady($form));

            $templateData[] = new Data(
                $identifier,
                [],
                [(string)$form]
            );
        }

        return $templateData;
    }

    /**
     * Because the form will come with a non-reusable CSRF token, it needs to invalidate the page if it contains
     * a contact form
     * (It's possible to work around this, but it wouldn't be easy as Opulence checks CSRF tokens automatically.)
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        return count($identifiers);
    }
}
