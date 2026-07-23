<?php

declare(strict_types=1);

namespace ChristianBrown\CodeQualityScripts\Tests\PhpStan;

use ChristianBrown\CodeQualityScripts\PhpStan\RequireStaticPrivateMethodRule;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<RequireStaticPrivateMethodRule>
 */
final class RequireStaticPrivateMethodRuleTest extends RuleTestCase
{
    public function testExemptsPrivateMethodsInTestCaseSubclasses(): void
    {
        $this->analyse([__DIR__.'/data/ExampleTestClass.php'], []);
    }

    public function testFlagsAStatelessPrivateMethodAndExemptsTheRest(): void
    {
        // Exempt: publicHelper (not private), alreadyStatic (static),
        // __construct / __destruct, usesThis ($this). Flagged: stateless.
        $this->analyse([__DIR__.'/data/ExampleClass.php'], [
            ['Private method stateless() does not use $this and should be static.', 31],
        ]);
    }

    protected function getRule(): Rule
    {
        return new RequireStaticPrivateMethodRule();
    }
}
