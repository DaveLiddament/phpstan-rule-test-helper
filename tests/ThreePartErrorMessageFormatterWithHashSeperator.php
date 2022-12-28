<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests;

use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;

final class ThreePartErrorMessageFormatterWithHashSeperator extends ErrorMessageFormatter
{
    public function getErrorMessage(string $errorContext): string
    {
        $parts = $this->getErrorMessageAsParts($errorContext, 3, '#');

        return "One: {$parts[0]}. Two: {$parts[1]}. Three: {$parts[2]}";
    }
}
