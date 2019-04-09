<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Template;

use AbterPhp\Contact\Constant\Routes;
use AbterPhp\Contact\Form\Factory\Contact as FormFactory;
use AbterPhp\Framework\Template\IData;
use AbterPhp\Framework\Template\ILoader;
use AbterPhp\Framework\Template\Data;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Routing\Urls\UrlGenerator;

class ContactLoader implements ILoader
{
    /** @var UrlGenerator */
    protected $urlGenerator;

    /** @var FormFactory */
    protected $formFactory;

    /**
     * ContactLoader constructor.
     *
     * @param UrlGenerator $urlGenerator
     * @param FormFactory  $formFactory
     */
    public function __construct(UrlGenerator $urlGenerator, FormFactory $formFactory)
    {
        $this->urlGenerator = $urlGenerator;
        $this->formFactory  = $formFactory;
    }

    /**
     * @param string[] $identifiers
     *
     * @return IData[]
     */
    public function load(array $identifiers): array
    {
        $url  = $this->urlGenerator->createFromName(Routes::ROUTE_CONTACT);
        $form = (string)$this->formFactory->create($url, RequestMethods::POST, '');

        $templateData = [];
        foreach ($identifiers as $identifier) {
            $templateData[] = new Data(
                $identifier,
                [],
                [$form]
            );
        }

        return $templateData;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param string[] $identifiers
     * @param string   $cacheTime
     *
     * @return bool
     */
    public function hasAnyChangedSince(array $identifiers, string $cacheTime): bool
    {
        return false;
    }
}
