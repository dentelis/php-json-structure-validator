<?php
declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\Type\ArrayType;
use Dentelis\StructureValidator\Type\IntersectionType;
use Dentelis\StructureValidator\Type\ObjectType;
use Dentelis\StructureValidator\Type\StringType;

$data = [
    (object)[
        'name' => 'user1',
        'contact' => 'user@example.com',
    ],
    (object)[
        'name' => 'user1',
        'contact' => (object)[
            'address' => '123456, city, street',
            'phone' => '123123123',
        ],
    ],
];

//define structure
$user = (new ObjectType())
    ->addProperty('name', (new StringType())->assertNotEmpty())
    ->addProperty('contact', (new IntersectionType([
        (new StringType())->assertEmail(),
        (new ObjectType())
            ->addProperty('address', (new StringType()))
            ->addProperty('phone', (new StringType()))
    ])));

$users = (new ArrayType())
    ->assertNotEmpty()
    ->assertType($user);

//run validation
try {
    $users->validate($data);
} catch (ValidationException $e) {
    //do smth
    throw $e;
}