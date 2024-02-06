<?php
declare(strict_types=1);

namespace tests\Object;

use Dentelis\StructureValidator\Type\BooleanType;
use Dentelis\StructureValidator\Type\IntegerType;
use Dentelis\StructureValidator\Type\NullType;
use Dentelis\StructureValidator\Type\ObjectType;
use Dentelis\StructureValidator\Type\StringType;
use Dentelis\StructureValidator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(ObjectType::class),
]
final class ObjectLogicTest extends TestCase
{


    public static function successProvider(): array
    {
        return [
            [
                (object)[
                    'id' => 100,
                    'sharable' => true,
                    'url' => 'https://example.com',
                ],
                self::objectWithLogic(),
            ],
            [
                (object)[
                    'id' => 100,
                    'sharable' => false,
                    'url' => null,
                ],
                self::objectWithLogic(),
            ],
        ];
    }

    /**
     * {'id':1, 'sharable':true, 'url':'https://...'}
     * {'id':1, 'sharable':false, 'url':null}
     */
    protected static function objectWithLogic(): ObjectType
    {
        return (new ObjectType())
            ->addProperty('id', (new IntegerType())->assertPositive())
            ->addProperty('sharable', (new BooleanType()))
            ->addProperty('url', fn($object) => ($object->sharable === true ? (new StringType())->assertUrl() : (new NullType())));
    }

    public static function failProvider(): array
    {
        return [
            [
                (object)[
                    'id' => 100,
                    'sharable' => false,
                    'url' => 'https://example.com',
                ],
                self::objectWithLogic(),
            ],
            [
                (object)[
                    'id' => 100,
                    'sharable' => true,
                    'url' => null,
                ],
                self::objectWithLogic(),
            ],
            [
                (object)[
                    'id' => 100,
                    'url' => null,
                ],
                self::objectWithLogic(),
            ],
            [
                [
                    'id' => 100,
                    'sharable' => true,
                    'url' => 'https://example.com',
                ],
                self::objectWithLogic(),
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
