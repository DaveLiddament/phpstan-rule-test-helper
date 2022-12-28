<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests;

use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;

final class TwoPartErrorMessageFormatter extends ErrorMessageFormatter
{
    public function getErrorMessage(string $errorContext): string
    {
        $parts = $this->getErrorMessageAsParts($errorContext, 2);

        return "One: {$parts[0]}. Two: {$parts[1]}";
    }
}
