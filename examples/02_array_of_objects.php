<?php
declare(strict_types=1);

use Dentelis\Validator\Type\ArrayType;
use Dentelis\Validator\Type\ObjectType;
use Dentelis\Validator\Type\StringType;

//object example
$users = (new ArrayType())
    ->assertNotEmpty()
    ->assertType((new ObjectType())
        ->addProperty('name', (new StringType())->assertNotEmpty())
        ->addProperty('email', (new StringType())->assertEmail())
    );

//run validation
$data = json_decode('[{"name":"user", "email":"user@example.com"},{"name":"user", "email":"user@example.com"}]');
try {
    $users->validate($data);
} catch (\Throwable $e) {
    //do smth

}