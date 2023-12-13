<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper;

use DaveLiddament\PhpstanRuleTestHelper\Internal\ErrorMessageParseException;

abstract class ErrorMessageFormatter
{
    /** @throws ErrorMessageParseException */
    abstract public function getErrorMessage(string $errorContext): string;

    /**
     * @param non-empty-string $separator
     *
     * @throws ErrorMessageParseException
     *
     * @return list<string>
     */
    final protected function getErrorMessageAsParts(
        string $errorContext,
        int $expectedNumberOfParts = null,
        string $separator = '|',
    ): array {
        $parts = explode($separator, $errorContext);
        if ((null !== $expectedNumberOfParts) && (count($parts) !== $expectedNumberOfParts)) {
            $message = sprintf(
                'Expected %d parts in error message but got %d.',
                $expectedNumberOfParts,
                count($parts),
            );

            throw new ErrorMessageParseException($message);
        }

        return $parts;
    }
}
