<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: false, //@todo
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        naming: false, //change variable names to class names
        instanceOf: true, //unclear
        earlyReturn: false, //need review
        strictBooleans: true,
        carbon: false, //https://carbon.nesbot.com/docs/
    )
    ->withSkip([
        \Rector\Php74\Rector\Closure\ClosureToArrowFunctionRector::class, //lower code readability
        \Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector::class, //breaks code
    ]);
