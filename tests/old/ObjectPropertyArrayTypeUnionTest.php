<?php
declare(strict_types=1);

namespace tests\old;

use EntelisTeam\Validator\Enum\_simpleType;
use EntelisTeam\Validator\Structure\_object;
use EntelisTeam\Validator\Structure\_property_array;
use EntelisTeam\Validator\Structure\_property_object;
use EntelisTeam\Validator\Structure\_property_simple;
use EntelisTeam\Validator\Structure\_simple;
use EntelisTeam\Validator\Structure\_struct;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use tests\old\structs\StructFactory;

final class ObjectPropertyArrayTypeUnionTest extends TestCase
{


    public static function ComplexArrayProvider(): array
    {
        $color = (object)['r' => 0, 'g' => 0, 'b' => 10];
        return [
            [
                'data' => (object)[
                    'title' => 'bmw',
                    'details' => [
                    ]
                ]
            ],
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

        $struct = new _object([
            'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false),
            'details' => new _property_array([$colorStruct, $yearsStruct, $nameStruct], false, true),
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


}
