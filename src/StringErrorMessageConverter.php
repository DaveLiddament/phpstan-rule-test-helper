<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper;

use DaveLiddament\PhpstanRuleTestHelper\Internal\PlaceHolderSubstituter;

final class StringErrorMessageConverter extends ErrorMessageFormatter
{
    public function __construct(
        private string $errorMessage,
    ) {
    }

    public function getErrorMessage(string $errorContext): string
    {
        return PlaceHolderSubstituter::substitute(
            $this->errorMessage,
            $this->getErrorMessageAsParts($errorContext),
        );
    }
}
