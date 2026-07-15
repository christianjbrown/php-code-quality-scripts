<?php

declare(strict_types=1);

namespace ChristianBrown\CodeQualityScripts\Tests;

use PhpCsFixer\Config;
use PhpCsFixer\Runner\Parallel\ParallelConfig;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @see ../config/Risky.php
 */
#[CoversNothing]
final class FixRiskyConfigTest extends TestCase
{
    public function test(): void
    {
        $config = include __DIR__.'/../config/Risky.php';

        self::assertInstanceOf(Config::class, $config);
        self::assertTrue($config->getRiskyAllowed());
        self::assertFalse($config->getUsingCache());
        $parallelConfig = $config->getParallelConfig();
        self::assertInstanceOf(ParallelConfig::class, $parallelConfig);

        $rules = $config->getRules();
        self::assertArrayHasKey('@DoctrineAnnotation', $rules);
        self::assertTrue($rules['@DoctrineAnnotation']);
        self::assertArrayHasKey('@PHP8x0Migration', $rules);
        self::assertTrue($rules['@PHP8x0Migration']);
        self::assertArrayHasKey('array_syntax', $rules);
        self::assertSame(['syntax' => 'short'], $rules['array_syntax']);
        self::assertArrayHasKey('binary_operator_spaces', $rules);
        self::assertTrue($rules['binary_operator_spaces']);
        self::assertArrayHasKey('blank_line_after_namespace', $rules);
        self::assertTrue($rules['blank_line_after_namespace']);
        self::assertArrayHasKey('class_definition', $rules);
        self::assertTrue($rules['class_definition']);
        self::assertArrayHasKey('concat_space', $rules);
        self::assertTrue($rules['concat_space']);
        self::assertArrayHasKey('declare_strict_types', $rules);
        self::assertTrue($rules['declare_strict_types']);
        self::assertArrayHasKey('no_unused_imports', $rules);
        self::assertTrue($rules['no_unused_imports']);
        self::assertArrayHasKey('single_quote', $rules);
        self::assertTrue($rules['single_quote']);
        self::assertArrayHasKey('modifier_keywords', $rules);
        self::assertTrue($rules['modifier_keywords']);
    }
}
