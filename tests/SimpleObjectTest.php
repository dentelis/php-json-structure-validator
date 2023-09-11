<?php
declare(strict_types=1);

use tests\structs\StructFactory;
use PHPUnit\Framework\TestCase;

final class SimpleObjectTest extends TestCase
{

    public function testSuccess(): void
    {
        $data = (object)['id' => 1, 'title' => 'lorem ipsum'];

        $struct = StructFactory::simpleClass();

        try {
            $struct->validate($data, '');
        } catch (\Throwable $e) {
            $this->assertNull($e);
        }
        $this->expectNotToPerformAssertions();
    }


}
