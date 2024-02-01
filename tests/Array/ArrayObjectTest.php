<?php
declare(strict_types=1);

namespace tests\Array;

use Dentelis\Validator\Type\ArrayType;
use Dentelis\Validator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use tests\_dto\UserDTO;
use Throwable;

#[
    CoversClass(ArrayType::class),
]
final class ArrayObjectTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            [
                [
                    new UserDTO(1, 'user@example.com'),
                    new UserDTO(2, 'admin@mail.com'),
                ],
                (new ArrayType())
                    ->assertNotEmpty()
                    ->assertType(UserDTO::getDefinition())
            ],

        ];
    }

    public static function failProvider(): array
    {
        return [
            [
                [
                    new UserDTO(1, 'user@example.com'),
                    new UserDTO(2, 'notemail'),
                ],
                (new ArrayType())
                    ->assertNotEmpty()
                    ->assertType(UserDTO::getDefinition())
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
