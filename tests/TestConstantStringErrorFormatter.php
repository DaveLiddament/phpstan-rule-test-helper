<?php

declare(strict_types=1);

namespace DaveLiddament\PhpstanRuleTestHelper\Tests;

use DaveLiddament\PhpstanRuleTestHelper\ConstantStringErrorMessageFormatter;

final class TestConstantStringErrorFormatter extends ConstantStringErrorMessageFormatter
{
    public function __construct()
    {
        parent::__construct('Test error message');
    }
}
