<?php
declare(strict_types=1);

namespace tests\Object;

use Dentelis\Validator\Type\ObjectType;
use Dentelis\Validator\TypeInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Stringable;
use tests\_dto\WorkerDTO;
use Throwable;

#[
    CoversClass(ObjectType::class),
]
final class ObjectRecursiveTest extends TestCase
{

    public static function successProvider(): array
    {

        //@todo
        return [
            [
                (new WorkerDTO('john')),
                WorkerDTO::getDefinition(),
            ],
            [
                (new WorkerDTO('john'))
                    ->setBoss((new WorkerDTO('mark'))),
                WorkerDTO::getDefinition(),
            ],
            [
                (new WorkerDTO('john'))
                    ->setBoss(
                        (new WorkerDTO('mark'))->setBoss(new WorkerDTO('karl'))
                    ),
                WorkerDTO::getDefinition(),
            ],
        ];
    }

    public static function failProvider(): array
    {

        return [
            //#0 - check property not exist in object
            [
                null,
                WorkerDTO::getDefinition(),
            ]
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
