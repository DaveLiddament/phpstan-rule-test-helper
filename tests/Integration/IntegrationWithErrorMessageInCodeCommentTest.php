<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests\Integration;

use DaveLiddament\PhpstanRuleTestHelper\AbstractRuleTestCase;
use PHPStan\Rules\Rule;

/** @extends AbstractRuleTestCase<NoGotoPhpstanRule> */
class IntegrationWithErrorMessageInCodeCommentTest extends AbstractRuleTestCase
{
    public function testRule(): void
    {
        $this->assertIssuesReported(__DIR__.'/../../fixtures/gotoWithMessageInComment.php');
    }

    protected function getRule(): Rule
    {
        return new NoGotoPhpstanRule();
    }
}
