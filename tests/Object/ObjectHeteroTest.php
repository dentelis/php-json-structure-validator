<?php
declare(strict_types=1);

namespace tests\Object;

use Dentelis\Validator\Exception\ValidationException;
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
final class ObjectHeteroTest extends TestCase
{


    public static function successProvider(): array
    {
        return [
            [
                (object)[
                    'id' => 100,
                    'details' => (object)[
                        'type' => 'car',
                        'color' => 'red',
                    ]
                ],
                self::objectWithHeteroProperty(),
            ],
            [
                (object)[
                    'id' => 1000,
                    'details' => (object)[
                        'type' => 'engine',
                        'power' => 1000,
                    ]
                ],
                self::objectWithHeteroProperty(),
            ],
        ];
    }

    /**
     * {
     *     id:1,
     *     'details':{
     *         'type':'car'
     *         'color':'red'
     *     }
     * }
     * {
     *     id:2,
     *     'details':{
     *         'type':'engine'
     *         'power':333
     *     }
     * }
     */
    protected static function objectWithHeteroProperty(): ObjectType
    {
        return (new ObjectType())
            ->addProperty('id', (new IntegerType())->assertPositive())
            ->addProperty('details', fn($object) => isset($object->details->type) ? match ($object->details->type) {
                'car' => (new ObjectType())
                    ->addProperty('type', (new StringType())->assertValue('car'))
                    ->addProperty('color', (new StringType())->assertNotEmpty()),
                'engine' => (new ObjectType())
                    ->addProperty('type', (new StringType())->assertValue('engine'))
                    ->addProperty('power', (new IntegerType())->assertPositive()),
                default => throw new ValidationException('details.type', 'car|engine', $object->details->type), //optional
            } : throw new ValidationException('details.type', 'car|engine', 'null'));
    }

    public static function failProvider(): array
    {
        return [
            [
                (object)[
                    'id' => '100', //#fail
                    'details' => (object)[
                        'type' => 'car',
                        'color' => 'red',
                    ]
                ],
                self::objectWithHeteroProperty(),
            ],
            [
                (object)[
                    'id' => 100,
                    'details' => null,
                ],
                self::objectWithHeteroProperty(),
            ],
            [
                (object)[
                    'id' => 100,
                    'details' => 'foo'
                ],
                self::objectWithHeteroProperty(),
            ],
            [
                (object)[
                    'id' => 100,
                    'details' => (object)[
                        'type' => 'engine',
                        'color' => 'red',
                    ]
                ],
                self::objectWithHeteroProperty(),
            ],
            [
                (object)[
                    'id' => 100,
                    'details' => (object)[
                        'type' => 'ship',
                        'color' => 'red',
                    ]
                ],
                self::objectWithHeteroProperty(),
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
