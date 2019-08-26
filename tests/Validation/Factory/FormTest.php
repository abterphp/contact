<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\Url;
use AbterPhp\Framework\Validation\Rules\Uuid;
use Opulence\Validation\IValidator;
use Opulence\Validation\Rules\Factories\RulesFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FormTest extends TestCase
{
    /** @var Form - System Under Test */
    protected $sut;

    /** @var RulesFactory|MockObject */
    protected $rulesFactoryMock;

    public function setUp(): void
    {
        parent::setUp();

        $this->rulesFactoryMock = StubRulesFactory::createRulesFactory(
            $this,
            [
                'uuid' => new Uuid(),
                'url'  => new Url(),
            ]
        );

        $this->sut = new Form($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorProvider(): array
    {
        return [
            'empty-data'                          => [
                [],
                false,
            ],
            'valid-data'                          => [
                [
                    'id'              => '465c91df-9cc7-47e2-a2ef-8fe645753148',
                    'identifier'      => 'qux',
                    'name'            => 'foo',
                    'to_name'         => 'bar',
                    'to_email'        => 'jane@example.com',
                    'success_url'     => 'https://example.com/success',
                    'failure_url'     => 'https://failure.example.com/',
                    'max_body_length' => '255',
                ],
                true,
            ],
            'valid-data-missing-all-not-required' => [
                [
                    'name'            => 'foo',
                    'identifier'      => 'qux',
                    'to_name'         => 'bar',
                    'to_email'        => 'jane@example.com',
                    'success_url'     => 'https://example.com/success',
                    'failure_url'     => 'https://failure.example.com/',
                    'max_body_length' => '255',
                ],
                true,
            ],
            'invalid-id-not-uuid'                 => [
                [
                    'id'         => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'identifier' => 'foo',
                ],
                false,
            ],
            'invalid-identifier-missing'                 => [
                [
                    'id'         => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'identifier' => 'foo',
                ],
                false,
            ],
            'invalid-id-not-uuid'                 => [
                [
                    'id'         => '465c91df-9cc7-47e2-a2ef-8fe64575314',
                    'identifier' => 'foo',
                ],
                false,
            ],
        ];
    }

    /**
     * @dataProvider createValidatorProvider
     *
     * @param array $data
     * @param bool  $expectedResult
     */
    public function testCreateValidator(array $data, bool $expectedResult)
    {
        $validator = $this->sut->createValidator();

        $this->assertInstanceOf(IValidator::class, $validator);

        $actualResult = $validator->isValid($data);

        $this->assertSame($expectedResult, $actualResult);
    }
}
