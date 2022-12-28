<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper;

final class DefaultErrorMessageFormatter extends ErrorMessageFormatter
{
    public function getErrorMessage(string $errorContext): string
    {
        return $errorContext;
    }
}
