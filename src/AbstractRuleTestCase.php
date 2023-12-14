<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper;

use DaveLiddament\PhpstanRuleTestHelper\Internal\FixtureFileReader;
use DaveLiddament\PhpstanRuleTestHelper\Internal\InvalidFixtureFile;
use PHPStan\Testing\RuleTestCase;

/**
 * @template TRule of \PHPStan\Rules\Rule
 *
 * @extends RuleTestCase<TRule>
 */
abstract class AbstractRuleTestCase extends RuleTestCase
{
    /** @throws InvalidFixtureFile */
    final protected function assertIssuesReported(string ...$fixtureFiles): void
    {
        $fixtureFileReader = new FixtureFileReader();
        $errorFormatter = $this->getErrorFormatter();

        if (is_string($errorFormatter)) {
            $errorFormatter = new StringErrorMessageConverter($errorFormatter);
        }

        $expectedErrors = [];
        foreach ($fixtureFiles as $fixture) {
            $expectedErrors = array_merge(
                $expectedErrors,
                $fixtureFileReader->getExpectedErrors($fixture, $errorFormatter),
            );
        }

        $this->analyse($fixtureFiles, $expectedErrors);
    }

    protected function getErrorFormatter(): ErrorMessageFormatter|string
    {
        return new DefaultErrorMessageFormatter();
    }
}
