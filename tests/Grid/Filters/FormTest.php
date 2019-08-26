<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Grid\Filters;

use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    /**
     * @return array
     */
    public function filterProvider(): array
    {
        return [
            [[], [], null],
        ];
    }

    /**
     * @dataProvider filterProvider
     *
     * @param string[]    $intents
     * @param array       $attributes
     * @param string|null $tag
     */
    public function testFilter(array $intents, array $attributes, ?string $tag)
    {
        $sut = new Form($intents, $attributes, $tag);

        $html = (string)$sut;

        $this->assertStringContainsString('<div class="hidable">', $html);
        $this->assertStringContainsString('<form class="filter-form"></form>', $html);
    }
}
