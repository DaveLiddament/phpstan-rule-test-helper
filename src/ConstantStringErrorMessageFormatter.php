<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper;

class ConstantStringErrorMessageFormatter extends ErrorMessageFormatter
{
    public function __construct(
        private string $errorMessage,
    ) {
    }

    final public function getErrorMessage(string $errorMessageContext): string
    {
        return $this->errorMessage;
    }
}
