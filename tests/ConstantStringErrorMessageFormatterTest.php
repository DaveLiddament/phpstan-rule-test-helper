<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests;

use PHPUnit\Framework\TestCase;

final class ConstantStringErrorMessageFormatterTest extends TestCase
{
    public function testGetErrorMessage(): void
    {
        $constantStringErrorFormatter = new TestConstantStringErrorFormatter();
        $this->assertSame('Test error message', $constantStringErrorFormatter->getErrorMessage(''));
    }
}
