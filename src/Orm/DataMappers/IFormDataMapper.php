<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Orm\DataMappers;

use AbterPhp\Contact\Domain\Entities\Form as Entity;
use Opulence\Orm\DataMappers\IDataMapper;

interface IFormDataMapper extends IDataMapper
{
    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $filters
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $filters, array $params): array;

    /**
     * @param string $identifier
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getByIdentifier(string $identifier): ?Entity;
}
