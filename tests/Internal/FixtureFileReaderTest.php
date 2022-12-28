<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests\Internal;

use DaveLiddament\PhpstanRuleTestHelper\Internal\FixtureFileReader;
use DaveLiddament\PhpstanRuleTestHelper\Internal\InvalidFixtureFile;
use PHPUnit\Framework\TestCase;

final class FixtureFileReaderTest extends TestCase
{
    private FixtureFileReader $fixtureFileReader;
    private MessageFormatter $messageFormatter;

    protected function setUp(): void
    {
        $this->fixtureFileReader = new FixtureFileReader();
        $this->messageFormatter = new MessageFormatter();
    }

    public function testFixtureWithNoErrorMessages(): void
    {
        $this->assertSame(
            [],
            $this->fixtureFileReader->getExpectedErrors(
                __DIR__.'/../../fixtures/ValidFileWithNoExpectedIssues.php',
                $this->messageFormatter,
            )
        );
    }

    public function testFixtureWith2ErrorMessages(): void
    {
        $this->assertSame(
            [
                [
                    'Can not call Item::updateName from SomeCode',
                    8,
                ],
                [
                    'Can not call Item::anotherMethod from SomeCode',
                    13,
                ],
            ],
            $this->fixtureFileReader->getExpectedErrors(
                __DIR__.'/../../fixtures/ValidFileWith2ExpectedIssues.php',
                $this->messageFormatter,
            ),
        );
    }

    public function testInvalidFileFixtureName(): void
    {
        $this->expectException(InvalidFixtureFile::class);
        $this->expectExceptionMessage('Can not read fixture file ['.__DIR__.'/../../fixtures/InvalidFileName.php]. Does it exist?');
        $this->fixtureFileReader->getExpectedErrors(
            __DIR__.'/../../fixtures/InvalidFileName.php',
            $this->messageFormatter,
        );
    }

    public function testInvalidErrorMessageInFixture(): void
    {
        $this->expectException(InvalidFixtureFile::class);
        $this->expectExceptionMessage('Could not parse error message in fixture file ['.__DIR__.'/../../fixtures/InvalidErrorMessage.php] on line 8. Expected 2 parts in error message but got 1.');
        $this->fixtureFileReader->getExpectedErrors(
            __DIR__.'/../../fixtures/InvalidErrorMessage.php',
            $this->messageFormatter,
        );
    }
}
