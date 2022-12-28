<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests\Internal;

use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;

final class MessageFormatter extends ErrorMessageFormatter
{
    public function getErrorMessage(string $errorContext): string
    {
        $parts = $this->getErrorMessageAsParts($errorContext, 2);

        return sprintf('Can not call %s from %s', $parts[0], $parts[1]);
    }
}
