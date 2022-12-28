<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper;

abstract class ConstantStringErrorMessageFormatter extends ErrorMessageFormatter
{
    protected function __construct(
        private string $errorMessage,
    ) {
    }

    final public function getErrorMessage(string $errorMessageContext): string
    {
        return $this->errorMessage;
    }
}
