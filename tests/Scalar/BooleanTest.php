<?php
declare(strict_types=1);

namespace tests\Scalar;

use Dentelis\StructureValidator\Type\BooleanType;
use Dentelis\StructureValidator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(BooleanTest::class),
]
final class BooleanTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [true, (new BooleanType())],
            [false, (new BooleanType())],
            [true, (new BooleanType())->assertValueIn([true, false])],
            [true, (new BooleanType())->assertValueIn([true])],
            [false, (new BooleanType())->assertValueIn([true, false])],
            [false, (new BooleanType())->assertValueIn([false])],
            [null, (new BooleanType())->setNullAllowed()],
            [null, (new BooleanType())->setNullAllowed()->assertValueIn([true, false])],
        ];
    }

    public static function failProvider(): array
    {
        return [
            ['0', (new BooleanType())],
            ['true', (new BooleanType())],
            ['false', (new BooleanType())],
            [['foo' => 'bar'], (new BooleanType())],
            [(object)['foo' => 'bar'], (new BooleanType())],
            [1.1, (new BooleanType())],
            [null, (new BooleanType())],
            [true, (new BooleanType())->assertValueIn([false])],
            [false, (new BooleanType())->assertValueIn([true])],
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
