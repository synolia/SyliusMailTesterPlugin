<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonySetList;

return RectorConfig::configure()
    ->withPaths([
        \dirname(__DIR__) . '/src',
        \dirname(__DIR__) . '/tests/PHPUnit',
    ])
    ->withPHPStanConfigs([__DIR__ . '/phpstan.neon'])
    ->withPhpSets(php82: true)
    ->withAttributesSets(symfony: true, doctrine: true)
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        doctrineCodeQuality: true,
        symfonyConfigs: true,
    )
    ->withTypeCoverageLevel(0)
    ->withSets([
        SymfonySetList::SYMFONY_71,
        SymfonySetList::SYMFONY_72,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
    ]);
