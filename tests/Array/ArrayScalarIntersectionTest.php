<?php
declare(strict_types=1);

namespace tests\Array;

use Dentelis\Validator\Type\ArrayType;
use Dentelis\Validator\Type\IntersectionType;
use Dentelis\Validator\Type\StringType;
use Dentelis\Validator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(ArrayType::class),
    CoversClass(IntersectionType::class),
]
final class ArrayScalarIntersectionTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [
                [
                    'user@email.com',
                    'https://example.com',
                ],
                (new ArrayType())
                    ->assertType(new IntersectionType([
                        (new StringType())->assertEmail(),
                        (new StringType())->assertUrl(),
                    ]))
            ],

        ];
    }

    public static function failProvider(): array
    {
        return [
            [
                [
                    'user', //fail
                    'https://example.com',
                ],
                (new ArrayType())
                    ->assertType(new IntersectionType([
                        (new StringType())->assertEmail(),
                        (new StringType())->assertUrl(),
                    ]))
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
