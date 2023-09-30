<?php

declare(strict_types=1);

use PhpCsFixer\Config;

$rules = [
    '@DoctrineAnnotation' => true,
    '@PER' => true,
    '@PER:risky' => true,
    '@PHP80Migration' => true,
    '@PHP80Migration:risky' => true,
    '@PHP81Migration' => true,
    '@PHP82Migration' => true,
    '@PHPUnit100Migration:risky' => true,
    '@PSR1' => true,
    '@PSR12' => true,
    '@PSR12:risky' => true,
    '@PSR2' => true,
    '@PhpCsFixer' => true,
    '@PhpCsFixer:risky' => true,
    '@Symfony' => true,
    '@Symfony:risky' => true,
    'array_push' => true,
    'braces_position' => true,
    'control_structure_continuation_position' => true,
    'date_time_create_from_format_call' => true,
    'date_time_immutable' => true,
    'final_class' => true,
    'global_namespace_import' => ['import_classes' => true, 'import_constants' => true, 'import_functions' => true],
    'mb_str_functions' => true,
    'native_function_invocation' => false,
    'no_multiline_whitespace_around_double_arrow' => true,
    'no_multiple_statements_per_line' => true,
    'no_whitespace_before_comma_in_array' => true,
    'nullable_type_declaration_for_default_null_value' => true,
    'ordered_class_elements' => ['sort_algorithm' => 'alpha', 'order' => ['use_trait', 'case', 'constant_public', 'constant_protected', 'constant_private', 'property_public', 'property_protected', 'property_private', 'construct', 'destruct', 'magic', 'phpunit', 'method_public', 'method_protected', 'method_private']],
    'ordered_interfaces' => true,
    'php_unit_size_class' => true,
    'php_unit_test_class_requires_covers' => true,
    'phpdoc_line_span' => true,
    'phpdoc_tag_casing' => true,
    'phpdoc_to_param_type' => true,
    'phpdoc_to_property_type' => true,
    'phpdoc_to_return_type' => true,
    'regular_callable_call' => true,
    'return_assignment' => false,
    'self_static_accessor' => true,
    'simplified_null_return' => true,
    'single_line_comment_style' => true,
    'single_line_empty_body' => false,
    'statement_indentation' => true,
    'static_lambda' => true,
    'trim_array_spaces' => true,
];

$config = new Config('risky');
$config->setRiskyAllowed(true);
$config->setUsingCache(false);
$config->setRules($rules);

return $config;
