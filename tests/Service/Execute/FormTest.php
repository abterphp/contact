<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Service\Execute;

use AbterPhp\Contact\Domain\Entities\Form as Entity;
use AbterPhp\Contact\Orm\FormRepo as GridRepo;
use AbterPhp\Contact\Validation\Factory\Form as ValidatorFactory;
use AbterPhp\Framework\Domain\Entities\IStringerEntity;
use Cocur\Slugify\Slugify;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Orm\IUnitOfWork;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Errors\ErrorCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    /** @var Form - System Under Test */
    protected $sut;

    /** @var GridRepo|MockObject */
    protected $gridRepoMock;

    /** @var ValidatorFactory|MockObject */
    protected $validatorFactoryMock;

    /** @var IUnitOfWork|MockObject */
    protected $unitOfWorkMock;

    /** @var IEventDispatcher|MockObject */
    protected $eventDispatcherMock;

    /** @var Slugify|MockObject */
    protected $slugifyMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->gridRepoMock         = $this->createMock(GridRepo::class);
        $this->validatorFactoryMock = $this->createMock(ValidatorFactory::class);
        $this->unitOfWorkMock       = $this->createMock(IUnitOfWork::class);
        $this->eventDispatcherMock  = $this->createMock(IEventDispatcher::class);
        $this->slugifyMock          = $this->createMock(Slugify::class);

        $this->sut = new Form(
            $this->gridRepoMock,
            $this->validatorFactoryMock,
            $this->unitOfWorkMock,
            $this->eventDispatcherMock,
            $this->slugifyMock
        );
    }

    public function testCreateEntity()
    {
        $id = 'foo';

        $actualResult = $this->sut->createEntity($id);

        $this->assertInstanceOf(Entity::class, $actualResult);
        $this->assertSame($id, $actualResult->getId());
    }

    public function testCreate()
    {
        $name          = 'foo';
        $toName        = 'bar';
        $toEmail       = 'jane@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = '255';

        $postData = [
            'name'            => $name,
            'identifier'      => '',
            'to_name'         => $toName,
            'to_email'        => $toEmail,
            'success_url'     => $successUrl,
            'failure_url'     => $failureUrl,
            'max_body_length' => $maxBodyLength,
        ];

        $this->gridRepoMock->expects($this->once())->method('add');
        $this->eventDispatcherMock->expects($this->atLeastOnce())->method('dispatch');
        $this->unitOfWorkMock->expects($this->once())->method('commit');
        $this->slugifyMock->expects($this->any())->method('slugify')->willReturnArgument(0);

        /** @var IStringerEntity|Entity $actualResult */
        $actualResult = $this->sut->create($postData, []);

        $this->assertInstanceOf(Entity::class, $actualResult);
        $this->assertEmpty($actualResult->getId());
        $this->assertSame($name, $actualResult->getIdentifier());
    }

    public function testUpdate()
    {
        $id            = '5c003d37-c59e-43eb-a471-e7b3c031fbeb';
        $identifier    = 'bar';
        $name          = 'foo';
        $toName        = 'bar';
        $toEmail       = 'jane@example.com';
        $successUrl    = 'https://example.com/success';
        $failureUrl    = 'https://failure.example.com/';
        $maxBodyLength = '255';

        $postData = [
            'name'            => $name,
            'identifier'      => $identifier,
            'to_name'         => $toName,
            'to_email'        => $toEmail,
            'success_url'     => $successUrl,
            'failure_url'     => $failureUrl,
            'max_body_length' => $maxBodyLength,
        ];

        $entity = $this->sut->createEntity($id);

        $this->gridRepoMock->expects($this->never())->method('add');
        $this->gridRepoMock->expects($this->never())->method('delete');
        $this->eventDispatcherMock->expects($this->atLeastOnce())->method('dispatch');
        $this->unitOfWorkMock->expects($this->once())->method('commit');
        $this->slugifyMock->expects($this->any())->method('slugify')->willReturnArgument(0);

        $actualResult = $this->sut->update($entity, $postData, []);

        $this->assertTrue($actualResult);
    }

    public function testUpdateThrowsExceptionWhenCalledWithWrongEntity()
    {
        $this->expectException(\InvalidArgumentException::class);

        /** @var IStringerEntity|MockObject $entityStub */
        $entityStub = $this->createMock(IStringerEntity::class);

        $this->sut->update($entityStub, [], []);
    }

    public function testDelete()
    {
        $id     = 'foo';
        $entity = $this->sut->createEntity($id);

        $this->gridRepoMock->expects($this->once())->method('delete');
        $this->eventDispatcherMock->expects($this->atLeastOnce())->method('dispatch');
        $this->unitOfWorkMock->expects($this->once())->method('commit');

        $actualResult = $this->sut->delete($entity);

        $this->assertTrue($actualResult);
    }

    public function testRetrieveEntity()
    {
        $id     = 'foo';
        $entity = $this->sut->createEntity($id);

        $this->gridRepoMock->expects($this->once())->method('getById')->willReturn($entity);

        $actualResult = $this->sut->retrieveEntity($id);

        $this->assertSame($entity, $actualResult);
    }

    public function testRetrieveList()
    {
        $offset     = 0;
        $limit      = 2;
        $orders     = [];
        $conditions = [];
        $params     = [];

        $id0            = 'foo';
        $entity0        = $this->sut->createEntity($id0);
        $id1            = 'bar';
        $entity1        = $this->sut->createEntity($id1);
        $expectedResult = [$entity0, $entity1];

        $this->gridRepoMock->expects($this->once())->method('getPage')->willReturn($expectedResult);

        $actualResult = $this->sut->retrieveList($offset, $limit, $orders, $conditions, $params);

        $this->assertSame($expectedResult, $actualResult);
    }

    public function testValidateFormSuccess()
    {
        $postData = ['foo' => 'bar'];

        $validatorMock = $this->createMock(IValidator::class);
        $validatorMock->expects($this->once())->method('isValid')->with($postData)->willReturn(true);
        $validatorMock->expects($this->never())->method('getErrors');

        $this->validatorFactoryMock->expects($this->once())->method('createValidator')->willReturn($validatorMock);

        $result = $this->sut->validateForm($postData);

        $this->assertSame([], $result);
    }

    public function testValidateFormFailure()
    {
        $postData = ['foo' => 'bar'];

        $errorsStub        = new ErrorCollection();
        $errorsStub['foo'] = ['foo error'];

        $validatorMock = $this->createMock(IValidator::class);
        $validatorMock->expects($this->once())->method('isValid')->with($postData)->willReturn(false);
        $validatorMock->expects($this->once())->method('getErrors')->willReturn($errorsStub);

        $this->validatorFactoryMock->expects($this->once())->method('createValidator')->willReturn($validatorMock);

        $result = $this->sut->validateForm($postData);

        $this->assertSame(['foo' => ['foo error']], $result);
    }

    public function testValidateCreatesOnlyOneValidator()
    {
        $postData = ['foo' => 'bar'];

        $validatorMock = $this->createMock(IValidator::class);
        $validatorMock->expects($this->any())->method('isValid')->with($postData)->willReturn(true);
        $validatorMock->expects($this->any())->method('getErrors');

        $this->validatorFactoryMock->expects($this->once())->method('createValidator')->willReturn($validatorMock);

        $firstRun  = $this->sut->validateForm($postData);
        $secondRun = $this->sut->validateForm($postData);

        $this->assertSame($firstRun, $secondRun);
    }
}
