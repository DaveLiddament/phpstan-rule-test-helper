<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests\Integration;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use DaveLiddament\PhpstanRuleTestHelper\ErrorMessageFormatter;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<NoGotoPhpstanRule> */
class IntegrationWithStringFormatterTest extends AbstractRuleTestCase
{
    public function testRule(): void
    {
        $this->assertIssuesReported(__DIR__.'/../../fixtures/goto.php');
    }

    protected function getRule(): Rule
    {
        return new NoGotoPhpstanRule();
    }

    protected function getErrorFormatter(): ErrorMessageFormatter
    {
        return new GotoRuleErrorFormatter();
    }
}
