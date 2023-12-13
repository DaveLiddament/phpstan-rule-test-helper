<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Internal;

use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;

/**
 * @internal
 */
final class FixtureFileReader
{
    private const ERROR_MARKER = '// ERROR';

    /**
     * @throws InvalidFixtureFile
     *
     * @return list<array{string, int}>
     *
     * @internal
     */
    public function getExpectedErrors(string $fileName, ErrorMessageFormatter $errorFormatter): array
    {
        try {
            $fixtureFileContents = file_get_contents($fileName);
        } catch (\Throwable) {
            throw InvalidFixtureFile::invalidFileName($fileName);
        }

        if (false === $fixtureFileContents) {
            throw InvalidFixtureFile::invalidFileName($fileName);
        }

        $expectedErrors = [];
        $lines = explode("\n", $fixtureFileContents);

        foreach ($lines as $index => $line) {
            $lineNumber = $index + 1;

            $errorMarkerPosition = strpos($line, self::ERROR_MARKER);
            if (false === $errorMarkerPosition) {
                continue;
            }

            $errorMessageContext = trim(substr($line, $errorMarkerPosition + strlen(self::ERROR_MARKER)));

            try {
                $errorMessage = $errorFormatter->getErrorMessage($errorMessageContext);
            } catch (ErrorMessageParseException $e) {
                throw InvalidFixtureFile::parseError($fileName, $lineNumber, $e);
            }

            $expectedErrors[] = [
                $errorMessage,
                $lineNumber,
            ];
        }

        return $expectedErrors;
    }
}
