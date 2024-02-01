<?php
declare(strict_types=1);

namespace tests\Array;

use Dentelis\Validator\Type\ArrayType;
use Dentelis\Validator\Type\IntegerType;
use Dentelis\Validator\Type\ObjectType;
use Dentelis\Validator\Type\StringType;
use Dentelis\Validator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(ArrayType::class),
]
final class ArrayObjectTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [
                [
                    (object)['id' => 1, 'email' => 'user@example.com'],
                    (object)['id' => 2, 'email' => 'admin@mail.com'],
                ],
                (new ArrayType())
                    ->assertNotEmpty()
                    ->assertType((new ObjectType())
                        ->addProperty('id', (new IntegerType())->assertPositive())
                        ->addProperty('email', (new StringType())->assertEmail())
                    )
            ],

        ];
    }

    public static function failProvider(): array
    {
        return [
            [
                [
                    (object)['id' => 1, 'email' => 'user@example.com'],
                    (object)['id' => 2, 'email' => 'wrong'],
                    (object)['id' => 3, 'email' => 'user@example.com'],
                ],
                (new ArrayType())
                    ->assertNotEmpty()
                    ->assertType((new ObjectType())
                        ->addProperty('id', (new IntegerType())->assertPositive())
                        ->addProperty('email', (new StringType())->assertEmail())
                    )
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
