<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\AddVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/examples',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withSkip([
        \Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class, //lower code readability
        \Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector::class, //breaks code
        ]);
