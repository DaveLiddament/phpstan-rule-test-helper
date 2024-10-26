<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper;

use DaveLiddament\PhpstanRuleTestHelper\Internal\ErrorMessageParseException;

abstract class ErrorMessageFormatter
{
    /**
     * This method returns the error message for an error in a fixture file.
     *
     * `$errorContext` is everything after `\\ERROR` in the fixture file
     *
     * @throws ErrorMessageParseException
     */
    abstract public function getErrorMessage(string $errorContext): string;

    /**
     * Helper method for splitting the error context into parts.
     *
     * @param non-empty-string $separator
     * @param int|null $expectedNumberOfParts if this is specified, then the number of parts of context must match this number
     *
     * @throws ErrorMessageParseException
     *
     * @return list<string>
     */
    final protected function getErrorMessageAsParts(
        string $errorContext,
        ?int $expectedNumberOfParts = null,
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
