<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Events\Listeners;

use AbterPhp\Contact\Template\Loader\Contact as Loader;
use AbterPhp\Framework\Events\TemplateEngineReady;
use AbterPhp\Framework\Template\ILoader;

class TemplateInitializer
{
    const TEMPLATE_TYPE = 'contact';

    /** @var ILoader */
    protected $loader;

    /**
     * TemplateRegistrar constructor.
     *
     * @param Loader $loader
     */
    public function __construct(Loader $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @param TemplateEngineReady $event
     */
    public function handle(TemplateEngineReady $event)
    {
        $event->getEngine()->getRenderer()->addLoader(static::TEMPLATE_TYPE, $this->loader);
    }
}
