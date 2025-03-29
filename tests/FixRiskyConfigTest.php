<?php

// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Runner\Parallel\ParallelConfig;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 *
 * @see ../config/fix-risky.php
 */
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
        self::assertIsArray($rules);
        self::assertArrayHasKey('@DoctrineAnnotation', $rules);
        self::assertTrue($rules['@DoctrineAnnotation']);
        self::assertArrayHasKey('@PHP80Migration', $rules);
        self::assertTrue($rules['@PHP80Migration']);
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
        self::assertArrayHasKey('visibility_required', $rules);
        self::assertTrue($rules['visibility_required']);
    }
}
