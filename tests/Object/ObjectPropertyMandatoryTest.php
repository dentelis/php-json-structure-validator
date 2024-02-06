<?php
declare(strict_types=1);

namespace tests\Object;

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
final class ObjectPropertyMandatoryTest extends TestCase
{

    public static function successProvider(): array
    {
        return [
            //#0
            [
                (object)[
                    'title' => 'foo',
                    'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->addProperty('title', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail(), false)
            ],
            //#1
            [
                (object)[
                    'title' => 'foo',
                    #'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->addProperty('title', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail(), false)
            ],
            //#2
            [
                (object)[
                    'title' => 'foo',
                    'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->setExtensible()
                    ->addProperty('title', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail(), false)
            ],
            //#3
            [
                (object)[
                    'title' => 'foo',
                    #'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->setExtensible()
                    ->addProperty('title', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail(), false)
            ],
        ];
    }

    public static function failProvider(): array
    {
        return [
            //#0
            [
                (object)[
                    #'title' => 'foo',
                    'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->addProperty('title', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail(), false)
            ],
            //#1
            [
                (object)[
                    'title' => 'foo',
                    #'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->addProperty('title', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail())
            ],
            //#2
            [
                (object)[
                    #'title' => 'foo',
                    'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->setExtensible()
                    ->addProperty('title', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail(), false)
            ],
            //#3
            [
                (object)[
                    'title' => 'foo',
                    #'email' => 'user@example.com',
                ],
                (new ObjectType())
                    ->setExtensible()
                    ->addProperty('title', (new StringType()))
                    ->addProperty('email', (new StringType())->assertEmail())
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
