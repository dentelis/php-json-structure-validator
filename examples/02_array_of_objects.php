<?php
declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use Dentelis\StructureValidator\Exception\ValidationException;
use Dentelis\StructureValidator\Type\ArrayType;
use Dentelis\StructureValidator\Type\ObjectType;
use Dentelis\StructureValidator\Type\StringType;

$data = json_decode('[{"name":"user", "email":"user@example.com"},{"name":"user", "email":"user@example.com"}]');

//define structure
$users = (new ArrayType())
    ->assertNotEmpty()
    ->assertType((new ObjectType())
        ->addProperty('name', (new StringType())->assertNotEmpty())
        ->addProperty('email', (new StringType())->assertEmail())
    );

//run validation
try {
    $users->validate($data);
} catch (ValidationException $e) {
    //do smth

}