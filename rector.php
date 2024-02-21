<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths(
        [
            __DIR__ . '/config',
            __DIR__ . '/public',
            __DIR__ . '/reports',
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ],
    )
    ->withSkip(
        [
            __DIR__ . '/src/Protobuf',
        ],
    )
    ->withPhpSets( php83: true)
    ->withSets(
        [
            PHPUnitSetList::PHPUNIT_100,
            PHPUnitSetList::PHPUNIT_CODE_QUALITY,
            SymfonySetList::SYMFONY_63,
            SymfonySetList::CONFIGS,
            SymfonySetList::SYMFONY_CODE_QUALITY,
            SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
            DoctrineSetList::DOCTRINE_CODE_QUALITY,
            DoctrineSetList::DOCTRINE_ORM_214
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
        strictBooleans: true
    )
    // if typeDeclaration is too high use this
//    ->withTypeCoverageLevel(1)
//    ->withDeadCodeLevel(1)
    ->withSymfonyContainerXml(__DIR__ . '/var/cache/dev/App_KernelDevDebugContainer.xml')
    ->withSymfonyContainerPhp(__DIR__ . '/tests/symfony-container.php')
    ->withRules(
        [
            AddVoidReturnTypeWhereNoReturnRector::class,
        ],
    );
