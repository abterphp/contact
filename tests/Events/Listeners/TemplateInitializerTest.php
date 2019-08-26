<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Events\Listeners;

use AbterPhp\Framework\Events\TemplateEngineReady;
use AbterPhp\Framework\Template\Engine;
use AbterPhp\Framework\Template\Renderer;
use AbterPhp\Contact\Template\Loader\Contact as ContactLoader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TemplateInitializerTest extends TestCase
{
    /** @var TemplateInitializer - System Under Test */
    protected $sut;

    /** @var ContactLoader|MockObject */
    protected $contactLoaderMock;

    public function setUp(): void
    {
        $this->contactLoaderMock        = $this->createMock(ContactLoader::class);

        $this->sut = new TemplateInitializer($this->contactLoaderMock);
    }

    public function testHandle()
    {
        $rendererMock = $this->createMock(Renderer::class);
        $rendererMock
            ->expects($this->at(0))
            ->method('addLoader')
            ->with(TemplateInitializer::TEMPLATE_TYPE, $this->contactLoaderMock)
            ->willReturnSelf();

        $engineMock = $this->createMock(Engine::class);
        $engineMock->expects($this->atLeastOnce())->method('getRenderer')->willReturn($rendererMock);

        $event = new TemplateEngineReady($engineMock);

        $this->sut->handle($event);
    }
}
