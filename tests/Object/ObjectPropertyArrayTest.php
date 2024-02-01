<?php
declare(strict_types=1);

namespace tests\Object;

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
    CoversClass(ObjectType::class),
    CoversClass(ArrayType::class),
]
final class ObjectPropertyArrayTest extends TestCase
{
    public static function successProvider(): array
    {
        return [
            [
                (object)[
                    'id' => 1,
                    'emails' => [
                        'user@example.com',
                    ]
                ],
                self::objectWithScalarArray()
            ],
        ];
    }

    /**
     * {'id':1, 'data':['user@example.com', ...]}
     */
    protected static function objectWithScalarArray(): ObjectType
    {
        return (new ObjectType())
            ->addProperty('id', (new IntegerType())->assertPositive())
            ->addProperty(
                'emails',
                (new ArrayType())
                    ->assertNotEmpty()
                    ->assertType((new StringType())->assertEmail())
            );
    }

    public static function failProvider(): array
    {
        return [
            [
                (object)[
                    'id' => 1,
                    'emails' => [
                        #'user@example.com', //empty
                    ]
                ],
                self::objectWithScalarArray()
            ],
            [
                (object)[
                    'id' => 1,
                    'emails' => [
                        'user@example.com',
                        123,
                    ]
                ],
                self::objectWithScalarArray()
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
