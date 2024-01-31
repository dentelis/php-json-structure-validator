<?php
declare(strict_types=1);

namespace tests\Object;

use Dentelis\Validator\Type\BooleanType;
use Dentelis\Validator\Type\FloatIntegerType;
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
]
final class ObjectTest extends TestCase
{

    public static function successProvider(): array
    {

        return [
            //#0
            [
                (object)[
                    'str' => 'foo',
                    'email' => 'user@example.com',
                    'int' => 100,
                    'bool' => true,
                    'nullableString' => null,
                ],
                (new ObjectType())
                    ->addProperty('str', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail())
                    ->addProperty('int', (new IntegerType())->assertInterval(min: 1))
                    ->addProperty('bool', (new BooleanType()))
                    ->addProperty('nullableString', (new StringType())->setNullAllowed())
            ],
            //#1
            [
                null,
                (new ObjectType())->setNullAllowed(),
            ],
            //#2
            [
                (object)[
                    'str' => 'foo',
                    'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->setExtensible()
                    ->addProperty('str', (new StringType()))
            ],
            //#3 object in object
            [
                (object)[
                    'model' => 'bmw',
                    'engine' => (object)[
                        'power' => 333,
                        'size' => 3.0,
                    ],
                ],
                (new ObjectType())
                    ->addProperty('model', (new StringType()))
                    ->addProperty('engine',
                        (new ObjectType())
                            ->addProperty('power', (new IntegerType())->assertPositive())
                            ->addProperty('size', (new FloatIntegerType())->addCustom(fn($value) => ($value >= 0)))
                    )
            ],
            //#4 nullable object in object
            [
                (object)[
                    'model' => 'bmw',
                    'engine' => null,
                ],
                (new ObjectType())
                    ->addProperty('model', (new StringType())->assertValueIn(['bmw', 'audi', 'mercedes']))
                    ->addProperty('engine',
                        (new ObjectType())
                            ->setNullAllowed()
                            ->addProperty('power', (new IntegerType()))
                            ->addProperty('size', (new FloatIntegerType())->assertPositive())
                    )
            ],

        ];
    }

    public static function failProvider(): array
    {
        return [
            //#0 - check property not exist in object
            [
                (object)[
                    'str' => 'foo',
                    #'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->addProperty('str', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail())
            ],
            //#1 - check object extended by unexpected property
            [
                (object)[
                    'str' => 'foo',
                    'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->addProperty('str', (new StringType()))
            ],
            //#2 - check type mismatch
            [
                (object)[
                    'str' => 'foo',
                    'email' => 'user', //email expected
                ],
                (new ObjectType())
                    ->addProperty('str', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail())
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
