<?php
declare(strict_types=1);

namespace tests;

use EntelisTeam\Validator\Enum\_simpleType;
use EntelisTeam\Validator\Exception\EmptyValueException;
use EntelisTeam\Validator\Exception\InvalidTypeException;
use EntelisTeam\Validator\Exception\InvalidValueException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[
    CoversClass(_simpleType::class),
]
final class SimpleTypeValidateTest extends TestCase
{

    public static function SuccessProvider(): array
    {
        return [
            [_simpleType::INT, 1],
            [_simpleType::INT, 0],
            [_simpleType::INT, -1],
            [_simpleType::INT, 100000],

            [_simpleType::FLOAT, 1],
            [_simpleType::FLOAT, 0],
            [_simpleType::FLOAT, -1],
            [_simpleType::FLOAT, 100000],
            [_simpleType::FLOAT, 0.001],
            [_simpleType::FLOAT, 999.001],

            [_simpleType::BOOL, true],
            [_simpleType::BOOL, false],

            [_simpleType::STRING, 'foobar'],
            [_simpleType::STRING, ''],
            [_simpleType::STRING, 'true'],
            [_simpleType::STRING, 'false'],
            [_simpleType::STRING, '1.1'],
            [_simpleType::STRING, '123456', '~^(\d+)$~'],

            [_simpleType::STRING_NOT_EMPTY, 'foobar'],
            [_simpleType::STRING_NOT_EMPTY, 'true'],
            [_simpleType::STRING_NOT_EMPTY, 'false'],
            [_simpleType::STRING_NOT_EMPTY, '1.1'],
            [_simpleType::STRING_NOT_EMPTY, '123456', '~^(\d+)$~'],

            [_simpleType::STRING_URl, 'https://entelis.team'],

            [_simpleType::NULL, null]
        ];
    }


    #[DataProvider('SuccessProvider')]
    public function testSuccess(_simpleType $type, mixed $value, ?string $regexp = null): void
    {
        try {
            $type->validate($value, '', $regexp);
        } catch (\Throwable $e) {
            $this->assertNull($e);
        }
        $this->expectNotToPerformAssertions();
    }


    public static function FailProvider(): array
    {
        return [
            [_simpleType::INT, '', InvalidTypeException::class],
            [_simpleType::INT, 's', InvalidTypeException::class],
            [_simpleType::INT, '0', InvalidTypeException::class],
            [_simpleType::INT, '100', InvalidTypeException::class],
            [_simpleType::INT, '1.1', InvalidTypeException::class],
            [_simpleType::INT, 1.1, InvalidTypeException::class],
            [_simpleType::INT, true, InvalidTypeException::class],
            [_simpleType::INT, false, InvalidTypeException::class],

            [_simpleType::FLOAT, '', InvalidTypeException::class],
            [_simpleType::FLOAT, 's', InvalidTypeException::class],
            [_simpleType::FLOAT, '0', InvalidTypeException::class],
            [_simpleType::FLOAT, '100', InvalidTypeException::class],
            [_simpleType::FLOAT, '1.1', InvalidTypeException::class],
            [_simpleType::FLOAT, true, InvalidTypeException::class],
            [_simpleType::FLOAT, false, InvalidTypeException::class],

            [_simpleType::BOOL, '', InvalidTypeException::class],
            [_simpleType::BOOL, 's', InvalidTypeException::class],
            [_simpleType::BOOL, '0', InvalidTypeException::class],
            [_simpleType::BOOL, '100', InvalidTypeException::class],
            [_simpleType::BOOL, '1.1', InvalidTypeException::class],
            [_simpleType::BOOL, 1.1, InvalidTypeException::class],
            [_simpleType::BOOL, 'true', InvalidTypeException::class],
            [_simpleType::BOOL, 'false', InvalidTypeException::class],
            [_simpleType::BOOL, 0, InvalidTypeException::class],
            [_simpleType::BOOL, -1, InvalidTypeException::class],
            [_simpleType::BOOL, 1, InvalidTypeException::class],

            [_simpleType::STRING, 1, InvalidTypeException::class],
            [_simpleType::STRING, -1, InvalidTypeException::class],
            [_simpleType::STRING, 0, InvalidTypeException::class],
            [_simpleType::STRING, true, InvalidTypeException::class],
            [_simpleType::STRING, false, InvalidTypeException::class],
            [_simpleType::STRING, 100.1, InvalidTypeException::class],

            [_simpleType::STRING, '123x432',  InvalidValueException::class, '~^(\d+)$~'],

            [_simpleType::STRING_NOT_EMPTY, 1, InvalidTypeException::class],
            [_simpleType::STRING_NOT_EMPTY, -1, InvalidTypeException::class],
            [_simpleType::STRING_NOT_EMPTY, 0, InvalidTypeException::class],
            [_simpleType::STRING_NOT_EMPTY, true, InvalidTypeException::class],
            [_simpleType::STRING_NOT_EMPTY, false, InvalidTypeException::class],
            [_simpleType::STRING_NOT_EMPTY, 100.1, InvalidTypeException::class],
            [_simpleType::STRING_NOT_EMPTY, '',  EmptyValueException::class],
            [_simpleType::STRING_NOT_EMPTY, '123x432',  InvalidValueException::class, '~^(\d+)$~'],

            [_simpleType::STRING_URl, '',  EmptyValueException::class],
            [_simpleType::STRING_URl, 'sfsdfsd',  InvalidValueException::class],


        ];
    }
    #[DataProvider('FailProvider')]
    public function testFailure(_simpleType $type, mixed $value, string $exceptionClass, ?string $regexp = null): void
    {
        $e = null;
        try {
            $type->validate($value, '',$regexp);
        } catch (\Throwable $e) {
            $this->assertInstanceOf($exceptionClass, $e);
        } finally {
            $this->assertNotNull($e, sprintf('Value "%s" with regexp "%s" MUST throw "%s" exception',
                ...[
                    $value,
                    $regexp,
                    $exceptionClass
                ]
            ));
        }
    }



}
