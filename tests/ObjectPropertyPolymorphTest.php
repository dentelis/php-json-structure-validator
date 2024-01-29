<?php
declare(strict_types=1);

use EntelisTeam\Validator\Enum\_simpleType;
use EntelisTeam\Validator\Structure\_object;
use EntelisTeam\Validator\Structure\_polymorph;
use EntelisTeam\Validator\Structure\_property_array;
use EntelisTeam\Validator\Structure\_property_object;
use EntelisTeam\Validator\Structure\_property_simple;
use EntelisTeam\Validator\Structure\_simple;
use EntelisTeam\Validator\Structure\_struct;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use tests\structs\StructFactory;

final class ObjectPropertyPolymorphTest extends TestCase
{


    public static function ComplexArrayProvider(): array
    {
        $color = (object)['r' => 0, 'g' => 0, 'b' => 10];
        return [
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details' => [
                        static::getComplexStruct('color', $color),
                        static::getComplexStruct('years', [2022, 2023]),
                        static::getComplexStruct('name', 'dim'),
                        static::getComplexStruct('name', 'foo bar'),
                    ]
                ]
            ],
        ];
    }

    protected static function getComplexStruct(string $type, mixed $data): object
    {
        return (object)['type' => $type, 'data' => $data];
    }

    public static function ComplexPropertyProvider(): array
    {
        $color = (object)['r' => 0, 'g' => 0, 'b' => 10];
        return [
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details' => static::getComplexStruct('color', $color),
                ]
            ],
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details' => static::getComplexStruct('years', [2022, 2023]),
                ]
            ],
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details' => static::getComplexStruct('name', 'Dim'),
                ]
            ],
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details' => static::getComplexStruct('name', 'foo bar'),
                ]
            ],
        ];
    }

    public static function ComplexBadPropertyProvider(): array
    {
        $color = (object)['r' => 0, 'g' => 0, 'b' => 10];
        return [
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details2' => static::getComplexStruct('color', $color),
                ]
            ],
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details' => static::getComplexStruct('years', 'ddd'),
                ]
            ],
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details' => static::getComplexStruct('name', [2022, 2023]),
                ]
            ],
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details' => null,
                ]
            ],
        ];
    }

    /**
     * проверяем работу нелинейныйх структур вида
     * [
     *    { type:"color", data: ColorObject }
     *    { type:"model", data: ModelObject }
     * ]
     */
    #[DataProvider('ComplexArrayProvider')]
    public function testComplexArray(object $data): void
    {

        $colorStruct = new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: false, possibleValues: ['color']),
            'data' => new _property_object(StructFactory::colorClass(), false),
        ]);

        $yearsStruct = new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: false, possibleValues: ['years']),
            'data' => new _property_array(new _simple(_simpleType::INT, false), false, false)
        ]);

        $nameStruct = new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: false, possibleValues: ['name']),
            'data' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);

        $f = function (mixed $data) use ($colorStruct, $yearsStruct, $nameStruct) {
            return match ($data?->type) {
                'color' => $colorStruct,
                'years' => $yearsStruct,
                'name' => $nameStruct,
                default => throw new Exception('Unexpected match value'),
            };
        };

        $struct = new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'details' => new _property_array(new _polymorph($f), false, true),
        ]);

        $this->check($struct, $data);

    }

    protected function check(_struct $struct, mixed $data): void
    {
        try {
            $struct->validate($data);
        } catch (Throwable $e) {
            $this->assertNull($e);
        }
        $this->expectNotToPerformAssertions();
    }

    /**
     * проверяем работу нелинейныйх структур вида
     *   data : { type:"color", data: ColorObject }
     *   data :  { type:"model", data: ModelObject }
     */
    #[DataProvider('ComplexPropertyProvider')]
    public function testComplexProperty(object $data): void
    {

        $colorStruct = new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: false, possibleValues: ['color']),
            'data' => new _property_object(StructFactory::colorClass(), false),
        ]);

        $yearsStruct = new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: false, possibleValues: ['years']),
            'data' => new _property_array(new _simple(_simpleType::INT, false), false, false)
        ]);

        $nameStruct = new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: false, possibleValues: ['name']),
            'data' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);

        $f = function (mixed $data) use ($colorStruct, $yearsStruct, $nameStruct) {
            return match ($data?->type) {
                'color' => $colorStruct,
                'years' => $yearsStruct,
                'years' => $yearsStruct,
                'name' => $nameStruct,
                default => throw new Exception('Unexpected match value'),
            };
        };

        $struct = new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'details' => new _property_object(new _polymorph($f), false),
        ]);

        $this->check($struct, $data);

    }

    /**
     * проверяем работу нелинейныйх структур вида
     *   data : { type:"color", data: ColorObject }
     *   data :  { type:"model", data: ModelObject }
     */
    #[DataProvider('ComplexBadPropertyProvider')]
    public function testBadComplexProperty(object $data): void
    {

        $colorStruct = new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: false, possibleValues: ['color']),
            'data' => new _property_object(StructFactory::colorClass(), false),
        ]);

        $yearsStruct = new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: false, possibleValues: ['years']),
            'data' => new _property_array(new _simple(_simpleType::INT, false), false, false)
        ]);

        $nameStruct = new _object([
            'type' => new _property_simple(simpleType: _simpleType::STRING, nullAllowed: false, possibleValues: ['name']),
            'data' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
        ]);

        $f = function (mixed $data) use ($colorStruct, $yearsStruct, $nameStruct) {
            return match ($data?->type) {
                'color' => $colorStruct,
                'years' => $yearsStruct,
                'name' => $nameStruct,
                default => throw new Exception('Unexpected match value'),
            };
        };

        $struct = new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'details' => new _property_object(new _polymorph($f), false),
        ]);

        $this->checkFails($struct, $data);

    }

    protected function checkFails(_struct $struct, mixed $data): void
    {
        $this->expectExceptionCode(0);
        $struct->validate($data);
    }

}
