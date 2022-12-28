<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests;

use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;
use DaveLiddament\PhpstanRuleTestHelper\Internal\ErrorMessageParseException;
use PHPUnit\Framework\TestCase;

final class ErrorMessageFormatterTest extends TestCase
{
    public function testErrorMessageAsParts(): void
    {
        $errorFormatter = new TwoPartErrorMessageFormatter();
        $this->assertSame(
            'One: Foo. Two: Bar',
            $errorFormatter->getErrorMessage('Foo|Bar'),
        );
    }

    public function testErrorMessageWithTooFewParts(): void
    {
        $errorFormatter = new TwoPartErrorMessageFormatter();
        $this->assertErrorMessageParseException(
            $errorFormatter,
            'Foo',
            2,
            1,
        );
    }

    public function testErrorMessageWithTooManyParts(): void
    {
        $errorFormatter = new TwoPartErrorMessageFormatter();
        $this->assertErrorMessageParseException(
            $errorFormatter,
            'Foo|Bar|Baz',
            2,
            3,
        );
    }

    public function testWithDifferentSeparator(): void
    {
        $errorFormatter = new ThreePartErrorMessageFormatterWithHashSeperator();
        $this->assertSame(
            'One: Foo. Two: Bar. Three: Baz',
            $errorFormatter->getErrorMessage('Foo#Bar#Baz'),
        );
    }

    private function assertErrorMessageParseException(
        ErrorMessageFormatter $errorFormatter,
        string $input,
        int $expectedNumberOfParts,
        int $actualNumberOfParts
    ): void {
        $this->expectException(ErrorMessageParseException::class);
        $this->expectExceptionMessage(
            sprintf(
                'Expected %d parts in error message but got %d',
                $expectedNumberOfParts,
                $actualNumberOfParts,
            )
        );
        $errorFormatter->getErrorMessage($input);
    }
}
