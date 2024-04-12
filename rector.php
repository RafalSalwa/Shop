<?php

declare(strict_types=1);

use Rector\Caching\ValueObject\Storage\FileCacheStorage;
use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\If_\RemoveAlwaysTrueIfConditionRector;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Naming\Rector\Assign\RenameVariableToMatchMethodCallReturnTypeRector;
use Rector\Naming\Rector\Class_\RenamePropertyToMatchTypeRector;
use Rector\Naming\Rector\ClassMethod\RenameParamToMatchTypeRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths(
        [
            __DIR__ . '/config',
            __DIR__ . '/public',
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ],
    )
    ->withSkip(
        [
            __DIR__ . '/src/Protobuf',
            __DIR__ . '/migrations',
            ClassPropertyAssignToConstructorPromotionRector::class,
            SimplifyBoolIdenticalTrueRector::class,
            RenamePropertyToMatchTypeRector::class,
            RenameParamToMatchTypeRector::class,
            RemoveAlwaysTrueIfConditionRector::class,
            RenameVariableToMatchMethodCallReturnTypeRector::class,
            FlipTypeControlToUseExclusiveTypeRector::class,
        ],
    )
    ->withPhpSets(php83: true)
    ->withAttributesSets(symfony: true, doctrine: true, phpunit: true, jms: true, sensiolabs: true)
    ->withSets(
        [
            PHPUnitSetList::PHPUNIT_100,
            PHPUnitSetList::PHPUNIT_CODE_QUALITY,
            SymfonySetList::SYMFONY_63,
            SymfonySetList::CONFIGS,
            SymfonySetList::SYMFONY_CODE_QUALITY,
            SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
            DoctrineSetList::DOCTRINE_CODE_QUALITY,
            DoctrineSetList::DOCTRINE_ORM_214,
        ],
    )
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: true,
        instanceOf: true,
        earlyReturn: true,
        strictBooleans: true,
    )
    ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml')
    ->withSymfonyContainerPhp(__DIR__ . '/tests/symfony-container.php')
    ->withRules(
        [
            AddVoidReturnTypeWhereNoReturnRector::class,
        ],
    )
    ->withCache(cacheDirectory: 'var/cache/dev/rector', cacheClass: FileCacheStorage::class);
