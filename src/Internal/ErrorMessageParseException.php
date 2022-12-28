<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Internal;

/**
 * @internal
 */
final class ErrorMessageParseException extends \Exception
{
    public function __construct(
        int $expectedNumberOfParts,
        int $actualNumberOfParts,
    ) {
        parent::__construct(
            sprintf(
                'Expected %d parts in error message but got %d.',
                $expectedNumberOfParts,
                $actualNumberOfParts,
            )
        );
    }
}
