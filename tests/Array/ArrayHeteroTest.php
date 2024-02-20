<?php
declare(strict_types=1);

namespace tests\Array;

use Dentelis\StructureValidator\Type\ArrayType;
use Dentelis\StructureValidator\Type\IntegerType;
use Dentelis\StructureValidator\Type\ObjectType;
use Dentelis\StructureValidator\Type\StringType;
use Dentelis\StructureValidator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(ArrayType::class),
]
final class ArrayHeteroTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [
                [
                    (object)['type' => 'string', 'value' => 'foo'],
                    (object)['type' => 'int', 'value' => 123],
                ],
                self::getArrayType()
            ],
        ];
    }

    protected static function getArrayType(): TypeInterface
    {
        return (new ArrayType())
            ->assertType(fn($object) => match ($object?->type) { //not the best code
                'string' => (new ObjectType())
                    ->addProperty('type', (new StringType())->assertValueIn(['string', 'int']))
                    ->addProperty('value', (new StringType())),
                'int' => (new ObjectType())
                    ->addProperty('type', (new StringType())->assertValueIn(['string', 'int']))
                    ->addProperty('value', (new IntegerType())),
            });
    }

    public static function failProvider(): array
    {

        //@todo

        return [
            [
                [
                    (object)['type' => 'string', 'value' => 123],
                    (object)['type' => 'int', 'value' => 123],
                ],
                self::getArrayType()
            ],
            [
                [
                    (object)['type' => 'string', 'value' => 'foo'],
                    (object)['type' => 'int', 'value' => '123'],
                ],
                self::getArrayType()
            ],
            [
                [
                    (object)['type' => 'bool', 'value' => 'foo'],
                    (object)['type' => 'int', 'value' => 123],
                ],
                self::getArrayType()
            ],
            [
                [
                    (object)['type' => true, 'value' => 'foo'],
                    (object)['type' => 'int', 'value' => 123],
                ],
                self::getArrayType()
            ],
        ];

    }

    #[DataProvider('successProvider')]
    public function testSuccess(mixed $value, TypeInterface $type): void
    {
        try {
            $type->validate($value);
        } catch (Throwable $e) {
            $this->assertNull($e);
        }
        $this->expectNotToPerformAssertions();
    }

    #[DataProvider('failProvider')]
    public function testFail(mixed $value, TypeInterface $type): void
    {
        $e = null;
        try {
            $type->validate($value);
        } catch (Throwable $e) {
            $this->assertInstanceOf(Throwable::class, $e);
        } finally {
            $this->assertNotNull($e, sprintf('Value <%s> MUST throw an exception', (is_scalar($value) || $value instanceof Stringable ? $value : '...')));
        }
    }


}
