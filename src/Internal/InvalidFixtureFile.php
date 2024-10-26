<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Internal;

/**
 * @internal
 */
final class InvalidFixtureFile extends \Exception
{
    public static function invalidFileName(string $fileName): self
    {
        return new self(sprintf('Can not read fixture file [%s]. Does it exist?', $fileName));
    }

    public static function parseError(
        string $fixtureFileContents,
        int $lineNumber,
        ErrorMessageParseException $e,
    ): self {
        return new self(
            sprintf(
                'Could not parse error message in fixture file [%s] on line %d. %s',
                $fixtureFileContents,
                $lineNumber,
                $e->getMessage()
            )
        );
    }
}
