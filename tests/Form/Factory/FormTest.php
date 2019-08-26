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

class FormTest extends TestCase
{
    /** @var Form - System Under Test */
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

        $this->sut = new Form($this->sessionMock, $this->translatorMock);
    }

    public function testCreate()
    {
        $action     = 'foo';
        $method     = RequestMethods::POST;
        $showUrl    = 'bar';
        $entityId   = '97450fee-7c17-4416-8cec-084648b5dfe3';
        $identifier = 'blah';
        $body       = 'mah';

        /** @var Entity|MockObject $entityMock */
        $entityMock = $this->createMock(Entity::class);

        $entityMock->expects($this->any())->method('getId')->willReturn($entityId);
        $entityMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $entityMock->expects($this->any())->method('getName')->willReturn($body);

        $form = (string)$this->sut->create($action, $method, $showUrl, $entityMock);

        $this->assertStringContainsString($action, $form);
        $this->assertStringContainsString($showUrl, $form);
        $this->assertStringContainsString('CSRF', $form);
        $this->assertStringContainsString('POST', $form);
        $this->assertStringContainsString('identifier', $form);
        $this->assertStringContainsString('to_name', $form);
        $this->assertStringContainsString('to_email', $form);
        $this->assertStringContainsString('success_url', $form);
        $this->assertStringContainsString('failure_url', $form);
        $this->assertStringContainsString('max_body_length', $form);
        $this->assertStringContainsString('button', $form);
    }
}
