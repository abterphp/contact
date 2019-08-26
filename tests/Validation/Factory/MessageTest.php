<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\MaxLength;
use AbterPhp\Framework\Validation\Rules\Uuid;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Factories\RulesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{
    /** @var Message - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory(
            $this,
            [
                'maxLength' => new MaxLength(),
                'uuid'      => new Uuid(),
            ]
        );

        $this->sut = new Message($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'                          => [
                [],
                0,
                false,
            ],
            'valid-data'                          => [
                [
                    'from_name'  => 'foo',
                    'from_email' => 'jane@example.com',
                    'subject'    => 'bar',
                    'body'       => 'baz',
                ],
                0,
                true,
            ],
            'valid-data-missing-all-not-required' => [
                [
                    'from_name'  => 'foo',
                    'from_email' => 'jane@example.com',
                    'subject'    => 'bar',
                    'body'       => 'baz',
                ],
                0,
                true,
            ],
            'invalid-name-missing'                => [
                [
                    'from_email' => 'jane@example.com',
                    'subject'    => 'bar',
                    'body'       => 'baz',
                ],
                0,
                false,
            ],
            'invalid-name-empty'                  => [
                [
                    'from_name'  => '',
                    'from_email' => 'jane@example.com',
                    'subject'    => 'bar',
                    'body'       => 'baz',
                ],
                0,
                false,
            ],
            'invalid-email-missing'               => [
                [
                    'from_name' => 'foo',
                    'subject'   => 'bar',
                    'body'      => 'baz',
                ],
                0,
                false,
            ],
            'invalid-email-empty'                 => [
                [
                    'from_name'  => 'foo',
                    'from_email' => '',
                    'subject'    => 'bar',
                    'body'       => 'baz',
                ],
                0,
                false,
            ],
            'invalid-email-not-valid-email'       => [
                [
                    'from_name'  => 'foo',
                    'from_email' => 'jane@@example.com',
                    'subject'    => 'bar',
                    'body'       => 'baz',
                ],
                0,
                false,
            ],
            'invalid-subject-missing'             => [
                [
                    'from_name'  => 'foo',
                    'from_email' => 'jane@example.com',
                    'body'       => 'baz',
                ],
                0,
                false,
            ],
            'invalid-subject-empty'               => [
                [
                    'from_name'  => 'foo',
                    'from_email' => 'jane@example.com',
                    'subject'    => '',
                    'body'       => 'baz',
                ],
                0,
                false,
            ],
            'invalid-body-missing'                => [
                [
                    'from_name'  => 'foo',
                    'from_email' => 'jane@example.com',
                    'subject'    => 'bar',
                ],
                0,
                false,
            ],
            'invalid-body-empty'                  => [
                [
                    'from_name'  => 'foo',
                    'from_email' => 'jane@example.com',
                    'subject'    => 'bar',
                    'body'       => '',
                ],
                0,
                false,
            ],
            'invalid-body-too-long'               => [
                [
                    'from_name'  => 'foo',
                    'from_email' => 'jane@example.com',
                    'subject'    => 'bar',
                    'body'       => 'baz',
                ],
                2,
                false,
            ],
        ];
    }

    /**
     * @dataProvider createValidatorProvider
     *
     * @param array $data
     * @param int   $maxBodyLength
     * @param bool  $expectedResult
     */
    public function testCreateValidator(array $data, int $maxBodyLength, bool $expectedResult)
    {
        $validator = $this->sut->setMaxBodyLength($maxBodyLength)->createValidator();

        $this->assertInstanceOf(IValidator::class, $validator);

        $actualResult = $validator->isValid($data);

        $this->assertSame($expectedResult, $actualResult);
    }
}
