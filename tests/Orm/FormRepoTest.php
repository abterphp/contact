<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Orm;

use AbterPhp\Admin\TestCase\Orm\RepoTestCase;
use AbterPhp\Contact\Domain\Entities\Form as Entity;
use AbterPhp\Contact\Orm\DataMappers\FormSqlDataMapper;
use Opulence\Orm\DataMappers\IDataMapper;
use Opulence\Orm\IEntityRegistry;
use PHPUnit\Framework\MockObject\MockObject;

class FormRepoTest extends RepoTestCase
{
    /** @var FormRepo - System Under Test */
    protected $sut;

    /** @var FormSqlDataMapper|MockObject */
    protected $dataMapperMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new FormRepo($this->className, $this->dataMapperMock, $this->unitOfWorkMock);
    }

    /**
     * @return FormSqlDataMapper|MockObject
     */
    protected function createDataMapperMock(): IDataMapper
    {
        /** @var FormSqlDataMapper|MockObject $mock */
        $mock = $this->createMock(FormSqlDataMapper::class);

        return $mock;
    }

    public function testGetAll()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', '', 0);
        $entityStub1 = new Entity('foo1', 'foo-1', '', '', '', '', '', 0);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getAll')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getAll();

        $this->assertSame($entities, $actualResult);
    }

    public function testGetByIdFromCache()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', '', 0);

        $entityRegistry = $this->createEntityRegistryStub($entityStub0);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->never())->method('getById');

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testGetByIdFromDataMapper()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', '', 0);

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $this->dataMapperMock->expects($this->once())->method('getById')->willReturn($entityStub0);

        $id = 'foo';

        $actualResult = $this->sut->getById($id);

        $this->assertSame($entityStub0, $actualResult);
    }

    public function testAdd()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', '', 0);

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForInsertion')->with($entityStub0);

        $this->sut->add($entityStub0);
    }

    public function testDelete()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', '', 0);

        $this->unitOfWorkMock->expects($this->once())->method('scheduleForDeletion')->with($entityStub0);

        $this->sut->delete($entityStub0);
    }

    public function testGetPage()
    {
        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', '', 0);
        $entityStub1 = new Entity('foo1', 'foo-1', '', '', '', '', '', 0);
        $entities    = [$entityStub0, $entityStub1];

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getPage')->willReturn($entities);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getPage(0, 10, [], [], []);

        $this->assertSame($entities, $actualResult);
    }

    public function testGetIdentifier()
    {
        $identifier = 'foo-0';

        $entityStub0 = new Entity('foo0', 'foo-0', '', '', '', '', '', 0);

        $entityRegistry = $this->createEntityRegistryStub(null);

        $this->dataMapperMock->expects($this->once())->method('getByIdentifier')->willReturn($entityStub0);

        $this->unitOfWorkMock->expects($this->any())->method('getEntityRegistry')->willReturn($entityRegistry);

        $actualResult = $this->sut->getByIdentifier($identifier);

        $this->assertSame($entityStub0, $actualResult);
    }

    /**
     * @param Entity|null $entity
     *
     * @return MockObject
     */
    protected function createEntityRegistryStub(?Entity $entity): MockObject
    {
        $entityRegistry = $this->createMock(IEntityRegistry::class);
        $entityRegistry->expects($this->any())->method('registerEntity');
        $entityRegistry->expects($this->any())->method('getEntity')->willReturn($entity);

        return $entityRegistry;
    }
}
