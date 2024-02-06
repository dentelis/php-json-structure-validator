<?php
declare(strict_types=1);

namespace tests\Scalar;

use Dentelis\StructureValidator\Type\IntegerType;
use Dentelis\StructureValidator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(IntegerType::class),
]
final class IntegerTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [0, (new IntegerType())],
            [0, (new IntegerType())->assertValue(0)],
            [1, (new IntegerType())->assertPositive()],
            [1000, (new IntegerType())],
            [-1000, (new IntegerType())],
            [-1000, (new IntegerType())->assertValue(-1000)],
            [-1000, (new IntegerType())->assertNegative()],
            [100, (new IntegerType())->assertInterval(0, 100)],
            [100, (new IntegerType())->assertInterval(0, 1000)],
            [-1000, (new IntegerType())->assertInterval(-1000, 1000)],
            [-100, (new IntegerType())->assertInterval(-1000, 1000)],
            [50, (new IntegerType())->assertValueIn([0, 50, 100])],
            [50, (new IntegerType())->assertValueIn([50])],
            [null, (new IntegerType())->setNullAllowed()],
            [null, (new IntegerType())->setNullAllowed()->assertInterval(0, 100)],
        ];
    }

    public static function failProvider(): array
    {
        return [
            ['0', (new IntegerType())],
            ['true', (new IntegerType())],
            [true, (new IntegerType())],
            [false, (new IntegerType())],
            [['foo' => 'bar'], (new IntegerType())],
            [(object)['foo' => 'bar'], (new IntegerType())],
            [1.1, (new IntegerType())],
            [null, (new IntegerType())],
            [0, (new IntegerType())->assertInterval(1, 100)],
            [-1, (new IntegerType())->assertInterval(1, 100)],
            [101, (new IntegerType())->assertInterval(1, 100)],
            [101, (new IntegerType())->assertValue(100)],
            [40, (new IntegerType())->assertValueIn([30, 50])],
            [-1, (new IntegerType())->assertPositive()],
            [1, (new IntegerType())->assertNegative()],
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
