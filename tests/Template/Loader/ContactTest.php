<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Template\Loader;

use AbterPhp\Contact\Form\Factory\Contact as FormFactory;
use AbterPhp\Framework\Form\IForm;
use AbterPhp\Framework\I18n\ITranslator;
use AbterPhp\Framework\Template\Data;
use AbterPhp\Framework\Template\ParsedTemplate;
use Opulence\Events\Dispatchers\IEventDispatcher;
use Opulence\Routing\Urls\UrlGenerator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ContactTest extends TestCase
{
    /** @var Contact - System Under Test */
    protected $sut;

    /** @var UrlGenerator|MockObject */
    protected $urlGeneratorMock;

    /** @var FormFactory|MockObject */
    protected $formFactoryMock;

    /** @var ITranslator|MockObject */
    protected $translatorMock;

    /** @var IEventDispatcher|MockObject */
    protected $eventDispatcherMock;

    public function setUp(): void
    {
        $this->urlGeneratorMock    = $this->createMock(UrlGenerator::class);
        $this->formFactoryMock     = $this->createMock(FormFactory::class);
        $this->translatorMock      = $this->createMock(ITranslator::class);
        $this->eventDispatcherMock = $this->createMock(IEventDispatcher::class);

        $this->sut = new Contact(
            $this->urlGeneratorMock,
            $this->formFactoryMock,
            $this->translatorMock,
            $this->eventDispatcherMock
        );
    }

    public function testLoadWithoutParsedTemplates()
    {
        $parsedTemplates = [];

        $actualResult = $this->sut->load($parsedTemplates);

        $this->assertSame([], $actualResult);
    }

    public function testLoadWithOneParsedTemplates()
    {
        $urlStub = 'https://example.com/';
        $formHtml = '<form />';

        $formStub = $this->createMock(IForm::class);
        $formStub->expects($this->any())->method('__toString')->willReturn($formHtml);

        $this->urlGeneratorMock->expects($this->any())->method('createFromName')->willReturn($urlStub);
        $this->formFactoryMock->expects($this->any())->method('create')->willReturn($formStub);

        $parsedTemplates = ['foo' => $this->createMock(ParsedTemplate::class)];

        $actualResult = $this->sut->load($parsedTemplates);

        $this->assertIsArray($actualResult);
        $this->assertCount(1, $actualResult);
        $this->assertInstanceOf(Data::class, $actualResult[0]);
        $this->assertSame([$formHtml], $actualResult[0]->getTemplates());
    }

    public function testHasAnyChangedSinceReturnsWhenCalledWithoutIdentifiers()
    {
        $identifiers = [];

        $actualResult = $this->sut->hasAnyChangedSince($identifiers, '');

        $this->assertFalse($actualResult);
    }

    public function testHasAnyChangedSinceReturnsTrueWhenCalledWithIdentifiers()
    {
        $identifiers = ['foo', 'bar'];

        $actualResult = $this->sut->hasAnyChangedSince($identifiers, '');

        $this->assertTrue($actualResult);
    }
}
