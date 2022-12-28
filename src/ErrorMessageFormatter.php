<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper;

use DaveLiddament\PhpstanRuleTestHelper\Internal\ErrorMessageParseException;

abstract class ErrorMessageFormatter
{
    abstract public function getErrorMessage(string $errorContext): string;

    /**
     * @return list<string>
     */
    final protected function getErrorMessageAsParts(
        string $errorContext,
        int $expectedNumberOfParts,
        string $separator = '|',
    ): array {
        if ('' === $separator) {
            throw new ErrorMessageParseException($expectedNumberOfParts, 0);
        }

        $parts = explode($separator, $errorContext);
        if (count($parts) !== $expectedNumberOfParts) {
            throw new ErrorMessageParseException($expectedNumberOfParts, count($parts));
        }

        return $parts;
    }
}
