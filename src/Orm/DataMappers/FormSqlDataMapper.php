<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Orm\DataMappers;

use AbterPhp\Contact\Domain\Entities\Form as Entity;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

class FormSqlDataMapper extends SqlDataMapper implements IFormDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Form entity.');
        }

        $query = (new QueryBuilder())
            ->insert(
                'contact_forms',
                [
                    'id'         => [$entity->getId(), \PDO::PARAM_STR],
                    'name'       => [$entity->getName(), \PDO::PARAM_STR],
                    'identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR],
                    'to_name'    => [$entity->getToName(), \PDO::PARAM_STR],
                    'to_email'   => [$entity->getToEmail(), \PDO::PARAM_STR],
                ]
            );

        $sql = $query->getSql();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param Entity $entity
     */
    public function delete($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Form entity.');
        }

        $query = (new QueryBuilder())
            ->update('contact_forms', 'contact_forms', ['deleted' => [1, \PDO::PARAM_INT]])
            ->where('id = ?')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();
    }

    /**
     * @return Entity[]
     */
    public function getAll(): array
    {
        $query = $this->getBaseQuery();

        $sql = $query->getSql();

        return $this->read($sql, [], self::VALUE_TYPE_ARRAY);
    }

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
        $query = $this->getBaseQuery()
            ->limit($pageSize)
            ->offset($limitFrom);

        foreach ($orders as $order) {
            $query->addOrderBy($order);
        }

        foreach ($conditions as $condition) {
            $query->andWhere($condition);
        }

        $replaceCount = 1;

        $sql = $query->getSql();
        $sql = str_replace('SELECT', 'SELECT SQL_CALC_FOUND_ROWS', $sql, $replaceCount);

        return $this->read($sql, $params, self::VALUE_TYPE_ARRAY);
    }

    /**
     * @param int|string $id
     *
     * @return array|null
     */
    public function getById($id)
    {
        $query = $this->getBaseQuery()->andWhere('cf.id = :form_id');

        $parameters = [
            'form_id' => [$id, \PDO::PARAM_STR],
        ];

        $sql = $query->getSql();

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param Entity $entity
     */
    public function update($entity)
    {
        if (!($entity instanceof Entity)) {
            throw new \InvalidArgumentException(__CLASS__ . ':' . __FUNCTION__ . ' expects a Form entity.');
        }

        $query = (new QueryBuilder())
            ->update(
                'contact_forms',
                'contact_forms',
                [
                    'name'       => [$entity->getName(), \PDO::PARAM_STR],
                    'identifier' => [$entity->getIdentifier(), \PDO::PARAM_STR],
                    'to_name'    => [$entity->getToName(), \PDO::PARAM_STR],
                    'to_email'   => [$entity->getToEmail(), \PDO::PARAM_STR],
                ]
            )
            ->where('id = ?')
            ->andWhere('deleted = 0')
            ->addUnnamedPlaceholderValue($entity->getId(), \PDO::PARAM_STR);

        $sql    = $query->getSql();
        $params = $query->getParameters();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($params);
        $statement->execute();
    }

    /**
     * @param array $hash
     *
     * @return Entity
     */
    protected function loadEntity(array $hash)
    {
        return new Entity(
            $hash['id'],
            $hash['name'],
            $hash['identifier'],
            $hash['to_name'],
            $hash['to_email']
        );
    }

    /**
     * @return SelectQuery
     */
    private function getBaseQuery(): SelectQuery
    {
        /** @var SelectQuery $query */
        $query = (new QueryBuilder())
            ->select(
                'cf.id',
                'cf.name',
                'cf.identifier',
                'cf.to_name',
                'cf.to_email'
            )
            ->from('contact_forms', 'cf')
            ->where('cf.deleted = 0');

        return $query;
    }
}
