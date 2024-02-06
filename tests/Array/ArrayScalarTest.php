<?php
declare(strict_types=1);

namespace tests\Array;

use Dentelis\StructureValidator\Type\ArrayType;
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
final class ArrayScalarTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [
                [
                    'user@email.com',
                    'user@example.com',
                ],
                (new ArrayType())
                    ->assertType((new StringType())->assertEmail())
                    ->assertNotEmpty()
            ],
            [
                [],
                //array can be empty
                (new ArrayType())
                    ->assertType((new StringType())->assertEmail())
            ],

            //count
            [
                [
                    'user@email.com',
                    123,
                    false,
                    ['foo'],
                    (object)['foo' => 'bar'],
                ],
                (new ArrayType())
                    ->assertNotEmpty()
            ],
            [
                [],
                (new ArrayType())
            ],
            [
                ['foo', 'ber'],
                (new ArrayType())
                    ->assertCount(2)
            ],
            [
                ['foo', 'ber'],
                (new ArrayType())
                    ->assertCountIn([2, 3])
            ],
            [
                ['foo', 'ber'],
                (new ArrayType())
                    ->assertNotEmpty()
            ],
            [
                [],
                (new ArrayType())
                    ->assertEmpty()
            ],

        ];
    }

    public static function failProvider(): array
    {
        return [
            [
                [
                    'user@email.com',
                    'user', #fail
                    'user@mail.com',
                ],
                (new ArrayType())
                    ->assertType((new StringType())->assertEmail())
                    ->assertNotEmpty()
            ],

            //count
            [
                [],
                (new ArrayType())
                    ->assertNotEmpty()
            ],
            [
                ['foo', 'ber'],
                (new ArrayType())
                    ->assertCount(3)
            ],
            [
                ['foo', 'ber'],
                (new ArrayType())
                    ->assertCountIn([3, 4])
            ],
            [
                ['foo', 'ber'],
                (new ArrayType())
                    ->assertCountInterval(max: 1)
            ],
            [
                ['foo'],
                (new ArrayType())
                    ->assertEmpty()
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
