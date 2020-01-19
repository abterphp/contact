<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Orm\DataMappers;

use AbterPhp\Contact\Domain\Entities\Form as Entity;
use Opulence\Orm\DataMappers\SqlDataMapper;
use Opulence\QueryBuilders\Expression;
use Opulence\QueryBuilders\MySql\QueryBuilder;
use Opulence\QueryBuilders\MySql\SelectQuery;

/** @phan-file-suppress PhanTypeMismatchArgument */

class FormSqlDataMapper extends SqlDataMapper implements IFormDataMapper
{
    /**
     * @param Entity $entity
     */
    public function add($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $query = (new QueryBuilder())
            ->insert(
                'contact_forms',
                [
                    'id'              => [$entity->getId(), \PDO::PARAM_STR],
                    'name'            => [$entity->getName(), \PDO::PARAM_STR],
                    'identifier'      => [$entity->getIdentifier(), \PDO::PARAM_STR],
                    'to_name'         => [$entity->getToName(), \PDO::PARAM_STR],
                    'to_email'        => [$entity->getToEmail(), \PDO::PARAM_STR],
                    'success_url'     => [$entity->getSuccessUrl(), \PDO::PARAM_STR],
                    'failure_url'     => [$entity->getFailureUrl(), \PDO::PARAM_STR],
                    'max_body_length' => [$entity->getMaxBodyLength(), \PDO::PARAM_INT],
                ]
            );

        $sql = $query->getSql();

        $statement = $this->writeConnection->prepare($sql);
        $statement->bindValues($query->getParameters());
        $statement->execute();
    }

    /**
     * @param Entity $entity
     *
     * @throws \Opulence\QueryBuilders\InvalidQueryException
     */
    public function delete($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $query = (new QueryBuilder())
            ->update('contact_forms', 'contact_forms', ['deleted_at' => new Expression('NOW()')])
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
     * @throws \Opulence\Orm\OrmException
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
     * @throws \Opulence\Orm\OrmException
     */
    public function getPage(int $limitFrom, int $pageSize, array $orders, array $conditions, array $params): array
    {
        $query = $this->getBaseQuery()
            ->limit($pageSize)
            ->offset($limitFrom);

        if (!$orders) {
            $query->orderBy('cf.name ASC');
        }
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
     * @param string $id
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
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
     * @param string $identifier
     *
     * @return Entity|null
     * @throws \Opulence\Orm\OrmException
     */
    public function getByIdentifier(string $identifier): ?Entity
    {
        $query = $this->getBaseQuery()->andWhere('cf.identifier = :form_identifier');

        $parameters = [
            'form_identifier' => [$identifier, \PDO::PARAM_STR],
        ];

        $sql = $query->getSql();

        return $this->read($sql, $parameters, self::VALUE_TYPE_ENTITY, true);
    }

    /**
     * @param Entity $entity
     *
     * @throws \Opulence\QueryBuilders\InvalidQueryException
     */
    public function update($entity)
    {
        assert($entity instanceof Entity, new \InvalidArgumentException());

        $query = (new QueryBuilder())
            ->update(
                'contact_forms',
                'contact_forms',
                [
                    'name'            => [$entity->getName(), \PDO::PARAM_STR],
                    'identifier'      => [$entity->getIdentifier(), \PDO::PARAM_STR],
                    'to_name'         => [$entity->getToName(), \PDO::PARAM_STR],
                    'to_email'        => [$entity->getToEmail(), \PDO::PARAM_STR],
                    'success_url'     => [$entity->getSuccessUrl(), \PDO::PARAM_STR],
                    'failure_url'     => [$entity->getFailureUrl(), \PDO::PARAM_STR],
                    'max_body_length' => [$entity->getMaxBodyLength(), \PDO::PARAM_INT],
                ]
            )
            ->where('id = ?')
            ->andWhere('deleted_at IS NULL')
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
            $hash['to_email'],
            $hash['success_url'],
            $hash['failure_url'],
            (int)$hash['max_body_length']
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
                'cf.to_email',
                'cf.success_url',
                'cf.failure_url',
                'cf.max_body_length'
            )
            ->from('contact_forms', 'cf')
            ->where('cf.deleted_at IS NULL');

        return $query;
    }
}
