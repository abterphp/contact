<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Grid\Factory\Table;

use AbterPhp\Admin\Grid\Factory\Table\BodyFactory;
use AbterPhp\Contact\Grid\Factory\Table\Header\Form as HeaderFactory;
use AbterPhp\Framework\Grid\Table\Table;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    /** @var Form - System Under Test */
    protected $sut;

    /** @var MockObject|HeaderFactory */
    protected $headerFactoryMock;

    /** @var MockObject|BodyFactory */
    protected $bodyFactoryMock;

    public function setUp(): void
    {
        $this->headerFactoryMock = $this->createMock(HeaderFactory::class);
        $this->bodyFactoryMock   = $this->createMock(BodyFactory::class);

        $this->sut = new Form($this->headerFactoryMock, $this->bodyFactoryMock);
    }

    public function testCreate()
    {
        $getters    = [];
        $rowActions = null;
        $params     = [];
        $baseUrl    = '';

        $actualResult = $this->sut->create($getters, $rowActions, $params, $baseUrl);

        $this->assertInstanceOf(Table::class, $actualResult);
    }
}
