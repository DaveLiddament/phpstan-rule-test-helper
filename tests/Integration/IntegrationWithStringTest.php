<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests\Integration;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<NoGotoPhpstanRule> */
class IntegrationWithStringTest extends AbstractRuleTestCase
{
    public function testRule(): void
    {
        $this->assertIssuesReported(__DIR__.'/../../fixtures/goto.php');
    }

    protected function getRule(): Rule
    {
        return new NoGotoPhpstanRule();
    }

    protected function getErrorFormatter(): string
    {
        return 'goto statement is not allowed. Label: {0}';
    }
}
