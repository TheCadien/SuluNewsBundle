<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\PHPUnit\Rector\Class_\AddProphecyTraitRector;
use Rector\PHPUnit\Set\PHPUnitLevelSetList;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;
use Sulu\Rector\Set\SuluLevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/Admin',
        __DIR__ . '/Api',
        __DIR__ . '/Content',
        __DIR__ . '/Controller',
        __DIR__ . '/DependencyInjection',
        __DIR__ . '/Entity',
        __DIR__ . '/Event',
        __DIR__ . '/Preview',
        __DIR__ . '/Repository',
        __DIR__ . '/Routing',
        __DIR__ . '/Service',
        __DIR__ . '/Tests/Functional',
        __DIR__ . '/Tests/Unit',
    ]);

    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');

    // basic rules
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);

    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        LevelSetList::UP_TO_PHP_81,
    ]);

    $rectorConfig->sets([
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);

    // doctrine rules
    $rectorConfig->sets([
        DoctrineSetList::DOCTRINE_CODE_QUALITY,
    ]);

    $rectorConfig->skip(
        [AddProphecyTraitRector::class]
    );

    // phpunit rules
    $rectorConfig->sets([
        PHPUnitLevelSetList::UP_TO_PHPUNIT_90,
        PHPUnitSetList::PHPUNIT_91,
    ]);

    // sulu rules
    $rectorConfig->sets([
        SuluLevelSetList::UP_TO_SULU_25,
    ]);
};
