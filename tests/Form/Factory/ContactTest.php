<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Form\Factory;

use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Contact\Domain\Entities\Form as Entity;
use Opulence\Http\Requests\RequestMethods;
use Opulence\Sessions\ISession;
use Opulence\Sessions\Session;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    /** @var Contact - System Under Test */
    protected $sut;

    /** @var ISession|MockObject */
    protected $sessionMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->sessionMock = $this->createMock(Session::class);
        $this->sessionMock->expects($this->any())->method('get')->willReturnArgument(0);

        $this->translatorMock = $this->createMock(ITranslator::class);
        $this->translatorMock->expects($this->any())->method('translate')->willReturnArgument(0);

        $this->sut = new Contact($this->sessionMock, $this->translatorMock);
    }

    public function testCreate()
    {
        $action     = 'foo';
        $method     = RequestMethods::POST;
        $showUrl    = 'bar';
        $entityId   = '97450fee-7c17-4416-8cec-084648b5dfe3';
        $identifier = 'blah';
        $name       = 'mah';

        /** @var Entity|MockObject $entityMock */
        $entityMock = $this->createMock(Entity::class);

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getName')->willReturn($name);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertStringContainsString($action, $form);
        $this->assertStringNotContainsString($showUrl, $form);
        $this->assertStringContainsString('CSRF', $form);
        $this->assertStringContainsString('POST', $form);
        $this->assertStringContainsString('name', $form);
        $this->assertStringContainsString('from_name', $form);
        $this->assertStringContainsString('from_email', $form);
        $this->assertStringContainsString('button', $form);
    }
}
