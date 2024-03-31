<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use Symfony\Component\Finder\Finder;

try {
$finder = new Finder();
$finder
    ->files()
    ->name('/\.php$/')
    ->in(__DIR__ . '/../..')
    ->exclude('config')
    ->exclude('var')
    ->exclude('public/bundles')
    ->exclude('public/build')
    ->exclude('migrations')
    ->exclude('node_modules')
    ->exclude('vendor')
    ->exclude('src/Protobuf')
    // exclude files generated by Symfony Flex recipes
    ->notPath('bin/console')
    ->notPath('public/index.php');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules(
        [
            '@Symfony' => true,
            '@Symfony:risky' => true,
            '@PHP82Migration' => true,
            '@PHP80Migration:risky' => true,
            '@PHPUnit100Migration:risky' => true,
            '@PhpCsFixer' => true,
            '@PhpCsFixer:risky' => true,
            '@PSR12' => true,
            'array_syntax' => ['syntax' => 'short'],
            'binary_operator_spaces' => ['default' => 'at_least_single_space'],
            'blank_line_after_opening_tag' => true,
            'blank_line_between_import_groups' => false,
            'blank_lines_before_namespace' => true,
            'braces_position' => false,
            'cast_spaces' => ['space' => 'none'],
            'compact_nullable_type_declaration' => true,
            'concat_space' => ['spacing' => 'one'],
            'declare_equal_normalize' => true,
            'declare_strict_types' => true,
            'final_class' => true,
            'fully_qualified_strict_types' => ['leading_backslash_in_global_namespace' => false],
            'general_phpdoc_annotation_remove' => ['annotations' => ['expectedDeprecation']],
            'global_namespace_import' => [
                'import_classes' => true,
                'import_constants' => true,
                'import_functions' => true,
            ],
            'linebreak_after_opening_tag' => true,
            'lowercase_cast' => true,
            'lowercase_static_reference' => true,
            'mb_str_functions' => true,
            'multiline_whitespace_before_semicolons' => false,
            'modernize_strpos' => true,
            'new_with_parentheses' => true,
            'no_blank_lines_after_class_opening' => true,
            'no_extra_blank_lines' => false,
            'no_leading_import_slash' => true,
            'no_php4_constructor' => true,
            'no_unreachable_default_argument_value' => true,
            'no_useless_concat_operator' => ['juggle_simple_strings' => true],
            'no_useless_else' => true,
            'no_useless_return' => true,
            'no_whitespace_in_blank_line' => true,
            'octal_notation' => false,
            'ordered_imports' => ['sort_algorithm' => 'alpha', 'imports_order' => ['class', 'function', 'const']],
            'phpdoc_add_missing_param_annotation' => ['only_untyped' => true],
            'php_unit_strict' => true,
            'php_unit_test_case_static_method_calls' => false,
            'php_unit_internal_class' => false,
            'php_unit_test_class_requires_covers' => false,
            'phpdoc_order' => true,
            'phpdoc_separation' => false,
            'phpdoc_to_param_type' => false,
            'phpdoc_to_return_type' => false,
            'phpdoc_types_order' => ['null_adjustment' => 'always_last'],
            'return_type_declaration' => true,
            'strict_comparison' => true,
            'semicolon_after_instruction' => true,
            'single_import_per_statement' => true,
            'single_line_empty_body' => false,
            'single_trait_insert_per_statement' => true,
            'strict_param' => true,
            'ternary_to_null_coalescing' => true,
            'ternary_operator_spaces' => true,
            'types_spaces' => false,
            'visibility_required' => true,
        ],
    )
    ->setFinder($finder)
    ->setCacheFile(__DIR__ . '/../../var/cache/dev/.php-cs-fixer.cache');
} catch (Symfony\Component\Finder\Exception\DirectoryNotFoundException $directoryNotFoundException) {
    error_log($directoryNotFoundException->getMessage());
}
