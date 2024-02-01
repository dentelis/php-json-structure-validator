<?php
declare(strict_types=1);

namespace tests\Scalar;

use Dentelis\Validator\Type\IntegerType;
use Dentelis\Validator\Type\IntersectionType;
use Dentelis\Validator\Type\StringType;
use Dentelis\Validator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(IntersectionType::class),
]
final class IntersectionTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [
                'foo',
                self::getIntersection(),
            ],
            [
                100,
                self::getIntersection(),
            ],
        ];
    }

    public static function getIntersection(): IntersectionType
    {
        return new IntersectionType([
            (new IntegerType())->assertPositive(),
            (new StringType())->assertValue('foo'),
        ]);
    }

    public static function failProvider(): array
    {
        return [
            [
                'bar', //#fail
                self::getIntersection(),
            ],
            [
                0, //#fail
                self::getIntersection(),
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
