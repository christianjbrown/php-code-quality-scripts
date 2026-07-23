<?php

// phpcs:ignoreFile -- deliberately-imperfect fixture for RequireStaticPrivateMethodRuleTest.

declare(strict_types=1);

namespace ChristianBrown\CodeQualityScripts\Tests\PhpStan\data;

final class ExampleClass
{
    private int $state = 1;

    private function __construct()
    {
    }

    private function __destruct()
    {
    }

    public function publicHelper(): int
    {
        return $this->state;
    }

    private static function alreadyStatic(): int
    {
        return 1;
    }

    private function stateless(): int
    {
        return 1;
    }

    private function usesThis(): int
    {
        return $this->state;
    }
}
