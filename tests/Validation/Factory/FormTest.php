<?php

declare(strict_types=1);

namespace AbterPhp\Contact\Validation\Factory;

use AbterPhp\Admin\TestDouble\Validation\StubRulesFactory;
use AbterPhp\Framework\Validation\Rules\Forbidden;
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
                'forbidden' => new Forbidden(),
                'uuid'      => new Uuid(),
                'url'       => new Url(),
            ]
        );

        $this->sut = new Form($this->rulesFactoryMock);
    }

    /**
     * @return array
     */
    public function createValidatorSuccessProvider(): array
    {
        return [
            'valid-data'                          => [
                [
                    'identifier'      => 'qux',
                    'name'            => 'foo',
                    'to_name'         => 'bar',
                    'to_email'        => 'jane@example.com',
                    'success_url'     => 'https://example.com/success',
                    'failure_url'     => 'https://failure.example.com/',
                    'max_body_length' => '255',
                ],
            ],
            'valid-data-missing-all-not-required' => [
                [
                    'name'            => 'foo',
                    'to_name'         => 'bar',
                    'to_email'        => 'jane@example.com',
                    'success_url'     => 'https://example.com/success',
                    'failure_url'     => 'https://failure.example.com/',
                    'max_body_length' => '255',
                ],
            ],
        ];
    }

    /**
     * @dataProvider createValidatorSuccessProvider
     *
     * @param array $data
     */
    public function testCreateValidatorSuccess(array $data)
    {
        $this->runTestCreateValidator($data, true);
    }

    /**
     * @return array
     */
    public function createValidatorFailureProvider(): array
    {
        return [
            'invalid-data-missing-name'            => [
                [
                    'to_name'         => 'bar',
                    'to_email'        => 'jane@example.com',
                    'success_url'     => 'https://example.com/success',
                    'failure_url'     => 'https://failure.example.com/',
                    'max_body_length' => '255',
                ],
            ],
            'invalid-data-missing-to_name'         => [
                [
                    'name'            => 'foo',
                    'to_email'        => 'jane@example.com',
                    'success_url'     => 'https://example.com/success',
                    'failure_url'     => 'https://failure.example.com/',
                    'max_body_length' => '255',
                ],
            ],
            'invalid-data-missing-to_email'        => [
                [
                    'name'            => 'foo',
                    'to_name'         => 'bar',
                    'success_url'     => 'https://example.com/success',
                    'failure_url'     => 'https://failure.example.com/',
                    'max_body_length' => '255',
                ],
            ],
            'invalid-data-missing-success_url'     => [
                [
                    'name'            => 'foo',
                    'to_name'         => 'bar',
                    'to_email'        => 'jane@example.com',
                    'failure_url'     => 'https://failure.example.com/',
                    'max_body_length' => '255',
                ],
            ],
            'invalid-data-missing-failure_url'     => [
                [
                    'name'            => 'foo',
                    'to_name'         => 'bar',
                    'to_email'        => 'jane@example.com',
                    'success_url'     => 'https://example.com/success',
                    'max_body_length' => '255',
                ],
            ],
            'invalid-data-missing-max_body_length' => [
                [
                    'name'        => 'foo',
                    'to_name'     => 'bar',
                    'to_email'    => 'jane@example.com',
                    'success_url' => 'https://example.com/success',
                    'failure_url' => 'https://failure.example.com/',
                ],
            ],
            'invalid-data-non-numeric-body_length' => [
                [
                    'name'            => 'foo',
                    'to_name'         => 'bar',
                    'to_email'        => 'jane@example.com',
                    'success_url'     => 'https://example.com/success',
                    'failure_url'     => 'https://failure.example.com/',
                    'max_body_length' => 'abc',
                ],
            ],
        ];
    }

    /**
     * @dataProvider createValidatorFailureProvider
     *
     * @param array $data
     */
    public function testCreateValidatorFailure(array $data)
    {
        $this->runTestCreateValidator($data, false);
    }

    /**
     * @param array $data
     * @param bool  $expectedResult
     */
    public function runTestCreateValidator(array $data, bool $expectedResult)
    {
        $validator = $this->sut->createValidator();

        $this->assertInstanceOf(IValidator::class, $validator);

        $actualResult = $validator->isValid($data);

        $this->assertSame($expectedResult, $actualResult);
    }
}
