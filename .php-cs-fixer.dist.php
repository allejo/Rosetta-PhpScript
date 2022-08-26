<?php declare(strict_types=1);

/*
 * (c) Vladimir "allejo" Jimenez <me@allejo.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 */

$finder = PhpCsFixer\Finder::create()
    ->append(['.php-cs-fixer.dist.php'])
    ->in('bin')
    ->in('scripts')
    ->in('src')
    ->notPath('JavaScript')
    ->in('tests')
    ->notPath('fixtures/php')
;

$license = <<<'HEADER'
(c) Vladimir "allejo" Jimenez <me@allejo.io>

For the full copyright and license information, please view the
LICENSE.md file that was distributed with this source code.
HEADER;

$config = new PhpCsFixer\Config();
$config
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_after_opening_tag' => false,
        'braces' => [
            'position_after_control_structures' => 'next',
        ],
        'cast_spaces' => [
            'space' => 'none',
        ],
        'concat_space' => ['spacing' => 'one'],
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => $license,
            'comment_type' => 'comment',
            'location' => 'after_declare_strict',
            'separate' => 'both',
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'new_line_for_chained_calls',
        ],
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'no_useless_return' => true,
        'no_whitespace_in_blank_line' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => [
                'const',
                'class',
                'function',
            ],
        ],
        'phpdoc_add_missing_param_annotation' => [
            'only_untyped' => true,
        ],
        'phpdoc_no_empty_return' => false,
        'phpdoc_order' => true,
        'phpdoc_var_without_name' => false,
        'php_unit_fqcn_annotation' => false,
        'ternary_to_null_coalescing' => true,
        'yoda_style' => [
            'equal' => false,
            'identical' => false,
        ],
        PhpCsFixerCustomFixers\Fixer\DataProviderNameFixer::name() => [
            'prefix' => 'dataProvider_test',
            'suffix' => '',
        ],
        PhpCsFixerCustomFixers\Fixer\DeclareAfterOpeningTagFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\NoImportFromGlobalNamespaceFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\PhpdocSingleLineVarFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\PhpdocTypesTrimFixer::name() => true,
    ])
    ->setFinder($finder)
;

return $config;
