<?php
declare(strict_types=1);

namespace tests\ErrorPath;

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\Type\ObjectType;
use Dentelis\StructureValidator\Type\StringType;
use Dentelis\StructureValidator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use Throwable;

#[
    CoversClass(ObjectType::class),
]
final class ObjectTest extends TestCase
{

    public static function failProvider(): array
    {
        return [
            //#0 - check property not exist in object
            [
                (object)[
                    'str' => 'foo',
                    #'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->addProperty('str', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail()),
                'object.email'
            ],
            //#1 - check object extended by unexpected property
            [
                (object)[
                    'str' => 'foo',
                    'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->addProperty('str', (new StringType())),
                'object.email'
            ],
            //#2 - check type mismatch
            [
                (object)[
                    'str' => 'foo',
                    'email' => 'user', //email expected
                ],
                (new ObjectType())
                    ->addProperty('str', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail()),
                'object.email'
            ],
            //#3 type
            [
                [
                    'str' => 'foo',
                    'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->addProperty('str', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail()),
                'object'
            ],
        ];

    }

    #[DataProvider('failProvider')]
    public function testFail(mixed $value, TypeInterface $type, string $path): void
    {
        $e = null;
        try {
            $type->validate($value);
        } catch (Throwable $e) {
            $this->assertInstanceOf(ValidationException::class, $e);
            $this->assertEquals($path, $e->path);
        } finally {
            $this->assertNotNull($e, sprintf('Value <%s> MUST throw an exception', (is_scalar($value) || $value instanceof Stringable ? $value : '...')));
        }
    }


}
