<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Bootstrappers\Validation;

use AbterPhp\Contact\Validation\Factory\Form;
use Opulence\Ioc\Bootstrappers\Bootstrapper;
use Opulence\Ioc\Bootstrappers\ILazyBootstrapper;
use Opulence\Ioc\IContainer;

/**
 * Defines the validator bootstrapper
 */
class ValidatorBootstrapper extends Bootstrapper implements ILazyBootstrapper
{
    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return [
            Form::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        parent::registerBindings($container);
    }
}
