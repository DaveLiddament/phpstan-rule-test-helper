<?php

namespace DaveLiddament\PhpstanRuleTestHelper\Tests;

use DaveLiddament\PhpstanRuleTestHelper\StringErrorMessageConverter;
use PHPUnit\Framework\TestCase;

final class StringErrorMessageConverterTest extends TestCase
{
    /** @return array<string, array{0:string, 1:string, 2:string}> */
    public function happyPathDataProvider(): array
    {
        return [
            'String with no placeholders' => [
                'Test error message',
                '',
                'Test error message',
            ],
            'String with one placeholder' => [
                'Test error message with {0}',
                '123',
                'Test error message with 123',
            ],
            'String with two placeholders' => [
                'Test error message with {0} and {1}',
                '123|456',
                'Test error message with 123 and 456',
            ],
            'String with three placeholders' => [
                'Test error message with {0}, {1} and {2}',
                '123|456|789',
                'Test error message with 123, 456 and 789',
            ],
            'String with repeated placeholders' => [
                'Test error message with {0}, {1} and {0}',
                '123|456',
                'Test error message with 123, 456 and 123',
            ],
            'String with no placeholders and extra data' => [
                'Test error message',
                '123',
                'Test error message',
            ],
            'String with one placeholder and extra data' => [
                'Test error message with {0}',
                '123|456',
                'Test error message with 123',
            ],
        ];
    }

    /**  @dataProvider happyPathDataProvider */
    public function testHappyPath(string $input, string $errorMessageContext, string $expectedOutput): void
    {
        $stringErrorMessageConverter = new StringErrorMessageConverter($input);
        $this->assertSame(
            $expectedOutput,
            $stringErrorMessageConverter->getErrorMessage($errorMessageContext),
        );
    }
}
