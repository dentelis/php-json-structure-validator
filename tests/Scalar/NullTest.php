<?php
declare(strict_types=1);

namespace tests\Scalar;

use Dentelis\StructureValidator\Type\NullType;
use Dentelis\StructureValidator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(NullType::class),
]
final class NullTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [null, (new NullType())],
            [null, (new NullType())->setNullAllowed()],
        ];
    }

    public static function failProvider(): array
    {
        return [
            [1, (new NullType())],
            [-1, (new NullType())],
            [0, (new NullType())],
            [100, (new NullType())],
            [100.11, (new NullType())],
            [true, (new NullType())],
            [false, (new NullType())],
            [['foo' => 'bar'], (new NullType())],
            [(object)['foo' => 'bar'], (new NullType())],
            ['foo', (new NullType())->assertValueIn(['bar'])],
            ['foo', (new NullType())->assertValueIn([])],
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
