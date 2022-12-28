<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests\Integration;

use PhpParser\Node;
use PhpParser\Node\Stmt\Goto_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;

/** @implements Rule<Goto_> */
final class NoGotoPhpstanRule implements Rule
{
    public function getNodeType(): string
    {
        return Goto_::class;
    }

    public function processNode(Node $node, Scope $scope): array
    {
        return ['goto statement is not allowed'];
    }
}
