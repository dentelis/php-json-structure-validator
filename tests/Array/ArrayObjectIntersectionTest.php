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
use tests\_dto\CarDTO;
use tests\_dto\UserDTO;
use Throwable;

#[
    CoversClass(ArrayType::class),
    CoversClass(IntersectionType::class),
]
final class ArrayObjectIntersectionTest extends TestCase
{

    public static function successProvider(): array
    {
        //@todo

        return [
            [
                [
                    new UserDTO(1, 'user@example.com'),
                    new CarDTO('bmw', 'x5'),
                ],
                (new ArrayType())
                    ->assertType(new IntersectionType([
                        UserDTO::getDefinition(),
                        CarDTO::getDefinition(),
                    ]))
            ],

        ];
    }

    public static function failProvider(): array
    {

        //@todo

        return [
            [
                [
                    new UserDTO(1, 'user@example.com'),
                    new CarDTO('bmw', 'x5'),
                    new UserDTO(1, 'user@example.com'),
                ],
                (new ArrayType())
                    ->assertType(new IntersectionType([
                        UserDTO::getDefinition(),
                        UserDTO::getDefinition(),
                        #CarDTO::getDefinition(),
                    ]))
            ],
            [
                [
                    new UserDTO('string', 'user@example.com'),
                    new CarDTO('bmw', 'x5'),
                ],
                (new ArrayType())
                    ->assertType(new IntersectionType([
                        UserDTO::getDefinition(),
                        CarDTO::getDefinition(),
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
