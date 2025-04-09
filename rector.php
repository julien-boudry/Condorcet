<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Foreach_\UnusedForeachValueToArrayKeysRector;
use Rector\Config\RectorConfig;
use Rector\DeadCode\Rector\ClassMethod\{RemoveUselessParamTagRector, RemoveUselessReturnTagRector};
use Rector\Instanceof_\Rector\Ternary\FlipNegatedTernaryInstanceofRector;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\TypeDeclaration\Rector\While_\WhileNullableToInstanceofRector;

$rectorLevel = 17;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Benchmarks',
        __DIR__ . '/Dev',
        __DIR__ . '/Examples',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    ->withIndent(indentChar: ' ', indentSize: 4)
    ->withPhpSets()
    ->withTypeCoverageLevel($rectorLevel)
    ->withDeadCodeLevel($rectorLevel)
    ->withCodeQualityLevel($rectorLevel)

    ->withRules([
        FlipNegatedTernaryInstanceofRector::class,
        WhileNullableToInstanceofRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,
        RemoveUselessReturnTagRector::class,
        RemoveUselessParamTagRector::class,
    ])

    ->withSkip([
        ClassPropertyAssignToConstructorPromotionRector::class,
        NullToStrictStringFuncCallArgRector::class,
        RestoreDefaultNullToNullableTypePropertyRector::class,
        UnusedForeachValueToArrayKeysRector::class,
    ])
;
