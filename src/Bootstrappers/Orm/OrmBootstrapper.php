<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Bootstrappers\Orm;

use AbterPhp\Admin\Bootstrappers\Orm\OrmBootstrapper as AbterAdminOrmBootstrapper;
use AbterPhp\Contact\Domain\Entities\Form;
use AbterPhp\Contact\Orm\DataMappers\FormSqlDataMapper;
use AbterPhp\Contact\Orm\FormRepo;
use Opulence\Ioc\IContainer;
use Opulence\Ioc\IocException;
use Opulence\Orm\IUnitOfWork;
use RuntimeException;

class OrmBootstrapper extends AbterAdminOrmBootstrapper
{
    /** @var array */
    protected $repoMappers = [
        FormRepo::class => [FormSqlDataMapper::class, Form::class],
    ];

    /**
     * @inheritdoc
     */
    public function getBindings(): array
    {
        return array_keys($this->repoMappers);
    }

    /**
     * @inheritdoc
     */
    public function registerBindings(IContainer $container)
    {
        try {
            $unitOfWork = $container->resolve(IUnitOfWork::class);
            $this->bindRepositories($container, $unitOfWork);
        } catch (IocException $ex) {
            $namespace = explode('\\', __NAMESPACE__)[0];
            throw new RuntimeException("Failed to register $namespace bindings", 0, $ex);
        }
    }
}
