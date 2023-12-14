<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper;

/**
 * @deprecated From v 0.3.0 this is no longer needed.`
 *
 * Before 0.3.0 `getErrorFormatter` had to return an instance of `ErrorMessageFormatter`.
 *
 * public function getErrorFormatter() {
 *    return new ConstantStringErrorMessageFormatter('My error message');
 * }
 *
 * Since 0.3.0 you can return a string or an instance of `ErrorMessageFormatter`.
 *
 * public function getErrorFormatter() {
 *   return 'My error message';
 * }
 */
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
