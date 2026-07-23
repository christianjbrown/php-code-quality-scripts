<?php

// phpcs:ignoreFile -- deliberately-imperfect fixture for RequireStaticPrivateMethodRuleTest.

declare(strict_types=1);

namespace ChristianBrown\CodeQualityScripts\Tests\PhpStan\data;

use PHPUnit\Framework\TestCase;

final class ExampleTestClass extends TestCase
{
    private function statelessTestHelper(): int
    {
        return 1;
    }
}
