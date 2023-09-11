<?php
declare(strict_types=1);

use EntelisTeam\Validator\Enum\_simpleType;
use EntelisTeam\Validator\Structure\_object;
use EntelisTeam\Validator\Structure\_property_array;
use EntelisTeam\Validator\Structure\_property_object;
use EntelisTeam\Validator\Structure\_property_simple;
use EntelisTeam\Validator\Structure\_simple;
use EntelisTeam\Validator\Structure\_struct;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use tests\structs\StructFactory;

final class ObjectPropertyArraySimpleTypeTest extends TestCase
{

    public static function simpleProvider(): array
    {
        $color = (object)['r' => 0, 'g' => 0, 'b' => 10];
        return [
            ['data' => (object)['title' => 'bmw', 'colors' => [$color, $color]]],
            ['data' => (object)['title' => 'audi', 'colors' => [$color,]]],
        ];
    }

    #[DataProvider('simpleProvider')]
    public function testSimple(object $data): void
    {
        $struct = StructFactory::classWithArrayOfClass();
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
