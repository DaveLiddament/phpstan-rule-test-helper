<?php

namespace DaveLiddament\PhpstanRuleTestHelper\Tests\Internal;

use DaveLiddament\PhpstanRuleTestHelper\Internal\ErrorMessageParseException;
use DaveLiddament\PhpstanRuleTestHelper\Internal\PlaceHolderSubstituter;
use PHPUnit\Framework\TestCase;

final class PlaceholderSubstitutorTest extends TestCase
{
    /**
     * @return array<array-key, array{0:string, 1:list<string>, 2:string}>
     */
    public function happyPathDataProvider(): array
    {
        return [
            [
                'Test error message',
                [],
                'Test error message',
            ],
            [
                'Test error message with {0}',
                ['123'],
                'Test error message with 123',
            ],
            [
                'Test error message with {0} and {1}',
                ['123', '456'],
                'Test error message with 123 and 456',
            ],
            [
                'Test error message with {0}, {1} and {2}',
                ['123', '456', '789'],
                'Test error message with 123, 456 and 789',
            ],
            [
                'Test error message with {0}, {1} and {0}',
                ['123', '456'],
                'Test error message with 123, 456 and 123',
            ],
            [
                'Test error message with {1} and {0}',
                ['123', '456', '789'],
                'Test error message with 456 and 123',
            ],
        ];
    }

    /**
     * @dataProvider happyPathDataProvider
     *
     * @param list<string> $values
     */
    public function testHappyPath(string $input, array $values, string $expectedOutput): void
    {
        $this->assertSame(
            $expectedOutput,
            PlaceHolderSubstituter::substitute($input, $values),
        );
    }

    /**
     * @return array<string, array{0:string, 1:list<string>}>
     */
    public function invalidDataProvider(): array
    {
        return [
            'No closed placeholder' => [
                'Test error  {0 message',
                ['123'],
            ],
            'Invalid placeholder' => [
                'Test error {zero} message',
                ['123'],
            ],
            'Too many placeholders' => [
                'Test error message with {0}',
                [],
            ],
            'Too many placeholders 2' => [
                'Test error message with {0} and {1}',
                ['123'],
            ],
        ];
    }

    /**
     * @dataProvider invalidDataProvider
     *
     * @param list<string> $values
     */
    public function testInvalidPlaceHolder(string $input, array $values): void
    {
        $this->expectException(ErrorMessageParseException::class);
        PlaceHolderSubstituter::substitute($input, $values);
    }
}
