<?php
declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\Type\ArrayType;
use Dentelis\StructureValidator\Type\ObjectType;
use Dentelis\StructureValidator\Type\StringType;

$data = [
    (object)[
        'name' => 'user1',
        'contact_type' => 'email',
        'contact' => 'user@example.com',
    ],
    (object)[
        'name' => 'user1',
        'contact_type' => 'address',
        'contact' => (object)[
            'address' => '123456, city, street',
            'phone' => '123123123',
        ],
    ],
];

//define structure
$user = (new ObjectType())
    ->addProperty('name', (new StringType())->assertNotEmpty())
    ->addProperty('contact_type', (new StringType())->assertValueIn(['email', 'address']))
    ->addProperty('contact', function ($user) {
        return match ($user->contact_type) {
            'email' => (new StringType())->assertEmail(),
            'address' => (new ObjectType())
                ->addProperty('address', (new StringType()))
                ->addProperty('phone', (new StringType())),
        };
    });

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