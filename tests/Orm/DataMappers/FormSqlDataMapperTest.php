<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Orm\DataMapper;

use AbterPhp\Admin\TestCase\Orm\DataMapperTestCase;
use AbterPhp\Contact\Domain\Entities\Form;
use AbterPhp\Contact\Orm\DataMappers\FormSqlDataMapper;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use AbterPhp\Framework\TestDouble\Database\MockStatementFactory;
use PHPUnit\Framework\MockObject\MockObject;

class FormSqlDataMapperTest extends DataMapperTestCase
{
    /** @var FormSqlDataMapper - System Under Test */
    protected $sut;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FormSqlDataMapper($this->readConnectionMock, $this->writeConnectionMock);
    }

    public function testAdd()
    {
        $nextId        = 'c2883287-ae5d-42d1-ab0c-7d3da2846452';
        $identifier    = 'foo';
        $name          = 'bar';
        $toName        = 'Baz';
        $toEmail       = 'baz@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = 16;

        $sql       = 'INSERT INTO contact_forms (id, name, identifier, to_name, to_email, success_url, failure_url, max_body_length) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'; // phpcs:ignore
        $values    = [
            [$nextId, \PDO::PARAM_STR],
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$toName, \PDO::PARAM_STR],
            [$toEmail, \PDO::PARAM_STR],
            [$successUrl, \PDO::PARAM_STR],
            [$failureUrl, \PDO::PARAM_STR],
            [$maxBodyLength, \PDO::PARAM_INT],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Form($nextId, $name, $identifier, $toName, $toEmail, $successUrl, $failureUrl, $maxBodyLength);

        $this->sut->add($entity);

        $this->assertSame($nextId, $entity->getId());
    }

    public function testDelete()
    {
        $id            = 'd23a94ed-b75c-43a9-9987-a783183dadd5';
        $identifier    = 'foo';
        $name          = 'bar';
        $toName        = 'Baz';
        $toEmail       = 'baz@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = 16;

        $sql       = 'UPDATE contact_forms AS contact_forms SET deleted = ? WHERE (id = ?)'; // phpcs:ignore
        $values    = [[1, \PDO::PARAM_INT], [$id, \PDO::PARAM_STR]];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Form($id, $name, $identifier, $toName, $toEmail, $successUrl, $failureUrl, $maxBodyLength);

        $this->sut->delete($entity);
    }

    public function testGetAll()
    {
        $id            = '40a59d7d-7550-4b16-a90b-89adbfec8979';
        $identifier    = 'foo';
        $name          = 'bar';
        $toName        = 'Baz';
        $toEmail       = 'baz@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = 16;

        $sql          = 'SELECT cf.id, cf.name, cf.identifier, cf.to_name, cf.to_email, cf.success_url, cf.failure_url, cf.max_body_length FROM contact_forms AS cf WHERE (cf.deleted = 0)'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'              => $id,
                'identifier'      => $identifier,
                'name'            => $name,
                'to_name'         => $toName,
                'to_email'        => $toEmail,
                'success_url'     => $successUrl,
                'failure_url'     => $failureUrl,
                'max_body_length' => $maxBodyLength,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getAll();

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPage()
    {
        $id            = 'bde8a749-b409-43c6-a061-c6a7d2dce6a0';
        $identifier    = 'foo';
        $name          = 'bar';
        $toName        = 'Baz';
        $toEmail       = 'baz@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = 16;

        $sql          = 'SELECT SQL_CALC_FOUND_ROWS cf.id, cf.name, cf.identifier, cf.to_name, cf.to_email, cf.success_url, cf.failure_url, cf.max_body_length FROM contact_forms AS cf WHERE (cf.deleted = 0) LIMIT 10 OFFSET 0'; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'              => $id,
                'identifier'      => $identifier,
                'name'            => $name,
                'to_name'         => $toName,
                'to_email'        => $toEmail,
                'success_url'     => $successUrl,
                'failure_url'     => $failureUrl,
                'max_body_length' => $maxBodyLength,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetPageWithOrdersAndConditions()
    {
        $id            = 'bde8a749-b409-43c6-a061-c6a7d2dce6a0';
        $identifier    = 'foo';
        $name          = 'bar';
        $toName        = 'Baz';
        $toEmail       = 'baz@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = 16;

        $orders     = ['block_layouts.identifier ASC'];
        $conditions = ['block_layouts.identifier LIKE \'abc%\'', 'block_layouts.identifier LIKE \'%bca\''];

        $sql          = "SELECT SQL_CALC_FOUND_ROWS cf.id, cf.name, cf.identifier, cf.to_name, cf.to_email, cf.success_url, cf.failure_url, cf.max_body_length FROM contact_forms AS cf WHERE (cf.deleted = 0) AND (block_layouts.identifier LIKE 'abc%') AND (block_layouts.identifier LIKE '%bca') ORDER BY block_layouts.identifier ASC LIMIT 10 OFFSET 0"; // phpcs:ignore
        $values       = [];
        $expectedData = [
            [
                'id'              => $id,
                'identifier'      => $identifier,
                'name'            => $name,
                'to_name'         => $toName,
                'to_email'        => $toEmail,
                'success_url'     => $successUrl,
                'failure_url'     => $failureUrl,
                'max_body_length' => $maxBodyLength,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getPage(0, 10, $orders, $conditions, []);

        $this->assertCollection($expectedData, $actualResult);
    }

    public function testGetById()
    {
        $id            = 'adbeb333-3110-42ec-a2ed-74a33db518ff';
        $identifier    = 'foo';
        $name          = 'bar';
        $toName        = 'Baz';
        $toEmail       = 'baz@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = 16;

        $sql          = 'SELECT cf.id, cf.name, cf.identifier, cf.to_name, cf.to_email, cf.success_url, cf.failure_url, cf.max_body_length FROM contact_forms AS cf WHERE (cf.deleted = 0) AND (cf.id = :form_id)'; // phpcs:ignore
        $values       = ['form_id' => [$id, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'              => $id,
                'identifier'      => $identifier,
                'name'            => $name,
                'to_name'         => $toName,
                'to_email'        => $toEmail,
                'success_url'     => $successUrl,
                'failure_url'     => $failureUrl,
                'max_body_length' => $maxBodyLength,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getById($id);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testGetByIdentifier()
    {
        $id            = 'b0538bd0-5762-417c-8208-4e6b04b72f86';
        $identifier    = 'foo';
        $name          = 'bar';
        $toName        = 'Baz';
        $toEmail       = 'baz@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = 16;

        $sql          = 'SELECT cf.id, cf.name, cf.identifier, cf.to_name, cf.to_email, cf.success_url, cf.failure_url, cf.max_body_length FROM contact_forms AS cf WHERE (cf.deleted = 0) AND (cf.identifier = :form_identifier)'; // phpcs:ignore
        $values       = ['form_identifier' => [$identifier, \PDO::PARAM_STR]];
        $expectedData = [
            [
                'id'              => $id,
                'identifier'      => $identifier,
                'name'            => $name,
                'to_name'         => $toName,
                'to_email'        => $toEmail,
                'success_url'     => $successUrl,
                'failure_url'     => $failureUrl,
                'max_body_length' => $maxBodyLength,
            ],
        ];
        $statement    = MockStatementFactory::createReadStatement($this, $values, $expectedData);
        MockStatementFactory::prepare($this, $this->readConnectionMock, $sql, $statement);

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertEntity($expectedData[0], $actualResult);
    }

    public function testUpdate()
    {
        $id            = '10ada92f-9ed8-4b7b-897a-9e10c640caec';
        $identifier    = 'foo';
        $name          = 'bar';
        $toName        = 'Baz';
        $toEmail       = 'baz@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = 16;

        $sql       = 'UPDATE contact_forms AS contact_forms SET name = ?, identifier = ?, to_name = ?, to_email = ?, success_url = ?, failure_url = ?, max_body_length = ? WHERE (id = ?) AND (deleted = 0)'; // phpcs:ignore
        $values    = [
            [$name, \PDO::PARAM_STR],
            [$identifier, \PDO::PARAM_STR],
            [$toName, \PDO::PARAM_STR],
            [$toEmail, \PDO::PARAM_STR],
            [$successUrl, \PDO::PARAM_STR],
            [$failureUrl, \PDO::PARAM_STR],
            [$maxBodyLength, \PDO::PARAM_INT],
            [$id, \PDO::PARAM_STR],
        ];
        $statement = MockStatementFactory::createWriteStatement($this, $values);
        MockStatementFactory::prepare($this, $this->writeConnectionMock, $sql, $statement);

        $entity = new Form($id, $name, $identifier, $toName, $toEmail, $successUrl, $failureUrl, $maxBodyLength);

        $this->sut->update($entity);
    }

    public function testAddThrowsExceptionIfCalledWithInvalidEntity()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var IStringerEntity|MockObject $entity */
        $entity = $this->createMock(IStringerEntity::class);

        $this->sut->add($entity);
    }

    public function testDeleteThrowsExceptionIfCalledWithInvalidEntity()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var IStringerEntity|MockObject $entity */
        $entity = $this->createMock(IStringerEntity::class);

        $this->sut->delete($entity);
    }

    public function testUpdateThrowsExceptionIfCalledWithInvalidEntity()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var IStringerEntity|MockObject $entity */
        $entity = $this->createMock(IStringerEntity::class);

        $this->sut->update($entity);
    }

    /**
     * @param array $expectedData
     * @param Form  $entity
     */
    protected function assertEntity(array $expectedData, $entity)
    {
        $this->assertInstanceOf(Form::class, $entity);
        $this->assertSame($expectedData['id'], $entity->getId());
        $this->assertSame($expectedData['identifier'], $entity->getIdentifier());
        $this->assertSame($expectedData['name'], $entity->getName());
        $this->assertSame($expectedData['to_name'], $entity->getToName());
        $this->assertSame($expectedData['to_email'], $entity->getToEmail());
        $this->assertSame($expectedData['success_url'], $entity->getSuccessUrl());
        $this->assertSame($expectedData['failure_url'], $entity->getFailureUrl());
        $this->assertSame($expectedData['max_body_length'], $entity->getMaxBodyLength());
    }
}
