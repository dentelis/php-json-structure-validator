<?php
declare(strict_types=1);

namespace tests\Scalar;

use Dentelis\StructureValidator\Type\StringType;
use Dentelis\StructureValidator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(StringType::class),
]
final class StringTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            ['foo', (new StringType())],
            ['foo', (new StringType())->assertValue('foo')],
            ['foo bar', (new StringType())],
            ['', (new StringType())],
            ['123', (new StringType())],
            ['123456', (new StringType())->assertRegexp('~^(\d+)$~')],
            ['foo', (new StringType())->assertLength(3, 3)],
            ['foo', (new StringType())->assertNotEmpty()],
            [' ', (new StringType())->assertNotEmpty()],
            ['foo', (new StringType())->assertLength(0, 100)],
            ['bar', (new StringType())->assertValueIn(['foo', 'bar'])],
            ['bar', (new StringType())->assertValueIn(['bar'])],
            ['', (new StringType())->assertValueIn(['bar', ''])],
            ['user@example.com', (new StringType())->assertEmail()],
            ['https://example.com', (new StringType())->assertUrl()],
            ['test foo bar', (new StringType())->addCustom(fn($value) => (str_contains($value, 'foo')))],
            [null, (new StringType())->setNullAllowed()],
            [null, (new StringType())->setNullAllowed()->assertEmail()],
            [null, (new StringType())->setNullAllowed()->assertUrl()],
            [null, (new StringType())->setNullAllowed()->assertRegexp('~^(\d+)$~')],
            [null, (new StringType())->setNullAllowed()->assertLength(3, 3)],
            [null, (new StringType())->setNullAllowed()->assertValueIn([])],
            [null, (new StringType())->setNullAllowed()->assertValueIn(['foo'])],
        ];
    }

    public static function failProvider(): array
    {
        return [
            [1, (new StringType())],
            [-1, (new StringType())],
            [0, (new StringType())],
            [100, (new StringType())],
            [100.11, (new StringType())],
            [true, (new StringType())],
            [false, (new StringType())],
            [['foo' => 'bar'], (new StringType())],
            [(object)['foo' => 'bar'], (new StringType())],
            ['foobar', (new StringType())->assertRegexp('~^(\d+)$~')],
            [null, (new StringType())],
            ['foo', (new StringType())->assertValue('bar')],
            ['', (new StringType())->assertValue('bar')],
            ['foo', (new StringType())->assertValue('')],
            ['', (new StringType())->assertLength(1)],
            ['', (new StringType())->assertNotEmpty()],
            ['foobar', (new StringType())->assertLength(max: 3)],
            ['foo', (new StringType())->assertEmail()],
            ['', (new StringType())->assertEmail()],
            ['example.com', (new StringType())->assertUrl()],
            ['', (new StringType())->assertUrl()],
            ['user@example.com', (new StringType())->assertEmail()->assertLength(max: 6)],
            ['foo', (new StringType())->assertValueIn(['bar'])],
            ['foo', (new StringType())->assertValueIn([])],
            ['test bar', (new StringType())->addCustom(fn($value) => (str_contains($value, 'foo')))],
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
