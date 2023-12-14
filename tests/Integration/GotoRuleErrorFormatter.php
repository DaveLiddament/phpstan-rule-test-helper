<?php

namespace DaveLiddament\PhpstanRuleTestHelper\Tests\Integration;

use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;

final class GotoRuleErrorFormatter extends ErrorMessageFormatter
{
    public function getErrorMessage(string $errorContext): string
    {
        return "goto statement is not allowed. Label: {$errorContext}";
    }
}
