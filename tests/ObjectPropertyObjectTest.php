<?php
declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use tests\structs\StructFactory;

final class ObjectPropertyObjectTest extends TestCase
{

    public static function provider(): array
    {
        return [
            ['data' => (object)['model' => 'bmw', 'color' => (object)['r' => 0, 'g' => 0, 'b' => 10]]],
            ['data' => (object)['model' => 'mercedes', 'color' => (object)['g' => 0, 'b' => 10, 'r' => 0]]],
            ['data' => (object)['color' => (object)['g' => 0, 'b' => 10, 'r' => 0], 'model' => 'audi']],
        ];
    }

    #[DataProvider('provider')]
    public function testSuccess(object $data): void
    {

        $struct = StructFactory::carClass();

        try {
            $struct->validate($data);
        } catch (Throwable $e) {
            $this->assertNull($e);
        }
        $this->expectNotToPerformAssertions();
    }


}
