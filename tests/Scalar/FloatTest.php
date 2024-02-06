<?php
declare(strict_types=1);

namespace tests\Scalar;

use Dentelis\StructureValidator\Type\FloatType;
use Dentelis\StructureValidator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(FloatType::class),
]
final class FloatTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [1.1, (new FloatType())],
            [0.0, (new FloatType())],
            [-1.1, (new FloatType())],
            [0.01, (new FloatType())->assertPositive()],
            [-0.01, (new FloatType())->assertNegative()],
            [100.0, (new FloatType())->assertInterval(0, 100)],
            [100.0, (new FloatType())->assertInterval(0, 1000)],
            [-1000.0, (new FloatType())->assertInterval(-1000, 1000)],
            [-100.0, (new FloatType())->assertInterval(-1000, 1000)],
            [50.0, (new FloatType())->assertValueIn([0, 50, 100])],
            [50.0, (new FloatType())->assertValueIn([50])],
            [null, (new FloatType())->setNullAllowed()],
            [null, (new FloatType())->setNullAllowed()->assertInterval(0, 100)],
        ];
    }

    public static function failProvider(): array
    {
        return [
            ['0', (new FloatType())],
            ['1', (new FloatType())],
            [1, (new FloatType())],
            [100, (new FloatType())],
            [0, (new FloatType())],
            ['1.1', (new FloatType())],
            ['true', (new FloatType())],
            [true, (new FloatType())],
            [false, (new FloatType())],
            [['foo' => 'bar'], (new FloatType())],
            [(object)['foo' => 'bar'], (new FloatType())],
            [null, (new FloatType())],
            [0.0, (new FloatType())->assertInterval(1, 100)],
            [-1.0, (new FloatType())->assertInterval(1, 100)],
            [101.0, (new FloatType())->assertInterval(1, 100)],
            [40.0, (new FloatType())->assertValueIn([30, 50])],
            [-0.01, (new FloatType())->assertPositive()],
            [0.01, (new FloatType())->assertNegative()],
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
