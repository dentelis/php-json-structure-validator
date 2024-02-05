<?php
declare(strict_types=1);

namespace tests\Object;

use Dentelis\Validator\Type\IntegerType;
use Dentelis\Validator\Type\IntersectionType;
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
    CoversClass(IntersectionType::class),
]
final class ObjectPropertyIntersectionTest extends TestCase
{
    public static function successProvider(): array
    {
        return [
            [
                (object)[
                    'id' => 100,
                    'data' => 'https://example.com',
                ],
                self::objectWithIntersection(),
            ],
            [
                (object)[
                    'id' => 100,
                    'data' => 'user@example.com',
                ],
                self::objectWithIntersection(),
            ],
        ];
    }

    /**
     * {'id':1, 'data':'https://...'}
     * {'id':1, 'data':'user@example.com'}
     */
    protected static function objectWithIntersection(): ObjectType
    {
        return (new ObjectType())
            ->addProperty('id', (new IntegerType())->assertPositive())
            ->addProperty('data', (new IntersectionType([
                (new StringType())->assertEmail(),
                (new StringType())->assertUrl(),
            ])));
    }

    public static function failProvider(): array
    {
        return [
            [
                (object)[
                    'id' => 100,
                    'data' => '',
                ],
                self::objectWithIntersection(),
            ],
            [
                (object)[
                    'id' => 100,
                    'data' => 'foo',
                ],
                self::objectWithIntersection(),
            ],
            [
                (object)[
                    'id' => 100,
                    'data' => null,
                ],
                self::objectWithIntersection(),
            ],
            [
                (object)[
                    'id' => 100,
                ],
                self::objectWithIntersection(),
            ],
            [
                [
                    'id' => 100,
                    'data' => 'https://example.com',
                ],
                self::objectWithIntersection(),
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
