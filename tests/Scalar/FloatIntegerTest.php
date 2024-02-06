<?php
declare(strict_types=1);

namespace tests\Scalar;

use Dentelis\StructureValidator\Type\FloatIntegerType;
use Dentelis\StructureValidator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(FloatIntegerType::class),
]
final class FloatIntegerTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [1.1, (new FloatIntegerType())],
            [0.0, (new FloatIntegerType())],
            [-1.1, (new FloatIntegerType())],
            [100.0, (new FloatIntegerType())->assertInterval(0, 100)],
            [100.0, (new FloatIntegerType())->assertInterval(0, 1000)],
            [-1000.0, (new FloatIntegerType())->assertInterval(-1000, 1000)],
            [-100.0, (new FloatIntegerType())->assertInterval(-1000, 1000)],
            [50.0, (new FloatIntegerType())->assertValueIn([0, 50, 100])],
            [50.0, (new FloatIntegerType())->assertValueIn([50])],
            [0.01, (new FloatIntegerType())->assertPositive()],
            [-0.01, (new FloatIntegerType())->assertNegative()],
            [1, (new FloatIntegerType())],
            [0, (new FloatIntegerType())],
            [-1, (new FloatIntegerType())],
            [100, (new FloatIntegerType())->assertInterval(0, 100)],
            [100, (new FloatIntegerType())->assertInterval(0, 1000)],
            [-1000, (new FloatIntegerType())->assertInterval(-1000, 1000)],
            [-100, (new FloatIntegerType())->assertInterval(-1000, 1000)],
            [50, (new FloatIntegerType())->assertValueIn([0, 50, 100])],
            [50, (new FloatIntegerType())->assertValueIn([50])],
            [1, (new FloatIntegerType())->assertPositive()],
            [-1, (new FloatIntegerType())->assertNegative()],
            [0, (new FloatIntegerType())->assertValue(0)],
            [0.0, (new FloatIntegerType())->assertValue(0.0)],
            [null, (new FloatIntegerType())->setNullAllowed()],
            [null, (new FloatIntegerType())->setNullAllowed()->assertInterval(0, 100)],
        ];
    }

    public static function failProvider(): array
    {
        return [
            ['0', (new FloatIntegerType())],
            ['1', (new FloatIntegerType())],
            ['1.1', (new FloatIntegerType())],
            ['true', (new FloatIntegerType())],
            [true, (new FloatIntegerType())],
            [false, (new FloatIntegerType())],
            [['foo' => 'bar'], (new FloatIntegerType())],
            [(object)['foo' => 'bar'], (new FloatIntegerType())],
            [null, (new FloatIntegerType())],
            [0.0, (new FloatIntegerType())->assertInterval(1, 100)],
            [-1.0, (new FloatIntegerType())->assertInterval(1, 100)],
            [101.0, (new FloatIntegerType())->assertInterval(1, 100)],
            [40.0, (new FloatIntegerType())->assertValueIn([30, 50])],
            [0, (new FloatIntegerType())->assertInterval(1, 100)],
            [-1, (new FloatIntegerType())->assertInterval(1, 100)],
            [101, (new FloatIntegerType())->assertInterval(1, 100)],
            [40, (new FloatIntegerType())->assertValueIn([30, 50])],
            [-0.01, (new FloatIntegerType())->assertPositive()],
            [0.01, (new FloatIntegerType())->assertNegative()],
            [-1, (new FloatIntegerType())->assertPositive()],
            [1, (new FloatIntegerType())->assertNegative()],
            [0, (new FloatIntegerType())->assertValue(0.0)],
            [0.0, (new FloatIntegerType())->assertValue(0)],
            [-1, (new FloatIntegerType())->assertValue(1)],
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
            $this->assertNotNull($e, sprintf('Value "%s" MUST throw an exception', (is_scalar($value) || $value instanceof Stringable ? $value : '...')));
        }
    }


}
