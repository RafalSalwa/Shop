<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Alias\NoAliasFunctionsFixer;
use PhpCsFixer\Fixer\Alias\NoAliasLanguageConstructCallFixer;
use PhpCsFixer\Fixer\Alias\NoMixedEchoPrintFixer;
use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use PhpCsFixer\Fixer\ArrayNotation\NoMultilineWhitespaceAroundDoubleArrowFixer;
use PhpCsFixer\Fixer\ArrayNotation\NormalizeIndexBraceFixer;
use PhpCsFixer\Fixer\Casing\IntegerLiteralCaseFixer;
use PhpCsFixer\Fixer\Casing\LowercaseStaticReferenceFixer;
use PhpCsFixer\Fixer\Casing\MagicConstantCasingFixer;
use PhpCsFixer\Fixer\Casing\MagicMethodCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeFunctionCasingFixer;
use PhpCsFixer\Fixer\Casing\NativeTypeDeclarationCasingFixer;
use PhpCsFixer\Fixer\CastNotation\NoShortBoolCastFixer;
use PhpCsFixer\Fixer\CastNotation\NoUnsetCastFixer;
use PhpCsFixer\Fixer\Comment\SingleLineCommentStyleFixer;
use PhpCsFixer\Fixer\ControlStructure\IncludeFixer;
use PhpCsFixer\Fixer\ControlStructure\NoAlternativeSyntaxFixer;
use PhpCsFixer\Fixer\FunctionNotation\LambdaNotUsedImportFixer;
use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use PhpCsFixer\Fixer\Import\OrderedImportsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\CombineConsecutiveUnsetsFixer;
use PhpCsFixer\Fixer\LanguageConstruct\SingleSpaceAroundConstructFixer;
use PhpCsFixer\Fixer\ListNotation\ListSyntaxFixer;
use PhpCsFixer\Fixer\NamespaceNotation\CleanNamespaceFixer;
use PhpCsFixer\Fixer\Operator\NoSpaceAroundDoubleColonFixer;
use PhpCsFixer\Fixer\Operator\ObjectOperatorWithoutWhitespaceFixer;
use PhpCsFixer\Fixer\Operator\StandardizeNotEqualsFixer;
use PhpCsFixer\Fixer\PhpTag\LinebreakAfterOpeningTagFixer;
use PhpCsFixer\Fixer\ReturnNotation\NoUselessReturnFixer;
use PhpCsFixer\Fixer\ReturnNotation\ReturnAssignmentFixer;
use PhpCsFixer\Fixer\Semicolon\MultilineWhitespaceBeforeSemicolonsFixer;
use PhpCsFixer\Fixer\Semicolon\NoEmptyStatementFixer;
use PhpCsFixer\Fixer\Strict\DeclareStrictTypesFixer;
use PhpCsFixer\Fixer\StringNotation\SingleQuoteFixer;
use PhpCsFixer\Fixer\Whitespace\BlankLineBeforeStatementFixer;
use PhpCsFixer\Fixer\Whitespace\NoExtraBlankLinesFixer;
use PhpCsFixer\Fixer\Whitespace\TypesSpacesFixer;
use Symplify\CodingStandard\Fixer\Commenting\ParamReturnAndVarTagMalformsFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPaths(
        [
            __DIR__ . '/../../config',
            __DIR__ . '/../../public',
            __DIR__ . '/../../reports',
            __DIR__ . '/../../src',
            __DIR__ . '/../../tests',
        ],
    )

    // add a single rule
    ->withRules(
        [
            NoUnusedImportsFixer::class,
            ArraySyntaxFixer::class,
            TypesSpacesFixer::class,
            NoUselessReturnFixer::class,
            LinebreakAfterOpeningTagFixer::class,
            StandardizeNotEqualsFixer::class,
            NoSpaceAroundDoubleColonFixer::class,
            CleanNamespaceFixer::class,
            ListSyntaxFixer::class,
            SingleSpaceAroundConstructFixer::class,
            LambdaNotUsedImportFixer::class,
            NoAlternativeSyntaxFixer::class,
            NoUnsetCastFixer::class,
            NoShortBoolCastFixer::class,
            NativeTypeDeclarationCasingFixer::class,
            NativeFunctionCasingFixer::class,
            MagicMethodCasingFixer::class,
            MagicConstantCasingFixer::class,
            LowercaseStaticReferenceFixer::class,
            IntegerLiteralCaseFixer::class,
            NormalizeIndexBraceFixer::class,
            NoMultilineWhitespaceAroundDoubleArrowFixer::class,
            BlankLineBeforeStatementFixer::class,
            CombineConsecutiveUnsetsFixer::class,
            DeclareStrictTypesFixer::class,
            IncludeFixer::class,
            MultilineWhitespaceBeforeSemicolonsFixer::class,
            NoAliasFunctionsFixer::class,
            NoAliasLanguageConstructCallFixer::class,
            NoEmptyStatementFixer::class,
            NoMixedEchoPrintFixer::class,
            ObjectOperatorWithoutWhitespaceFixer::class,
            ParamReturnAndVarTagMalformsFixer::class,
            ReturnAssignmentFixer::class,
            SingleQuoteFixer::class,
        ],
    )
    ->withConfiguredRule(
        OrderedImportsFixer::class,
        [
            'imports_order' => ['class', 'function', 'const'],
        ],
    )
    ->withConfiguredRule(
        SingleLineCommentStyleFixer::class,
        [
            'comment_types' => ['hash'],
        ],
    )
    ->withConfiguredRule(
        NoExtraBlankLinesFixer::class,
        [
            'tokens' => [
                'curly_brace_block',
                'extra',
                'parenthesis_brace_block',
                'square_brace_block',
                'throw',
                'use',
            ],
        ],
    )
    // add sets - group of rules
    ->withPreparedSets(psr12: true, common: true, symplify: true, strict: true, cleanCode: true)
    ->withPhpCsFixerSets();
