<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Orm;

use AbterPhp\Contact\Domain\Entities\Form as Entity;
use AbterPhp\Contact\Orm\DataMappers\FormSqlDataMapper;
use AbterPhp\Framework\Orm\IGridRepo;
use Opulence\Orm\Repositories\Repository;

class FormRepo extends Repository implements IGridRepo
{
    /**
     * @param int      $limitFrom
     * @param int      $pageSize
     * @param string[] $orders
     * @param array    $conditions
     * @param array    $params
     *
     * @return Entity[]
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $conditions, array $params): array
    {
        /** @see FormSqlDataMapper::getPage() */
        return $this->getFromDataMapper('getPage', [$limitFrom, $pageSize, $orders, $conditions, $params]);
    }

    /**
     * @param string $identifier
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        /** @see FormSqlDataMapper::getPage() */
        return $this->getFromDataMapper('getByIdentifier', [$identifier]);
    }
}
