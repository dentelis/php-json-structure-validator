<?php
declare(strict_types=1);

require_once(__DIR__ . '/../vendor/autoload.php');

use Dentelis\Validator\Type\ObjectType;
use Dentelis\Validator\Type\StringType;

//object example
$user = (new ObjectType())
    ->addProperty('name', (new StringType())->assertNotEmpty())
    ->addProperty('email', (new StringType())->assertEmail());

//run validation
$data = json_decode('{"name":"user", "email":"user@example.com"}');
try {
    $user->validate($data);
} catch (\Throwable $e) {
    //do smth

}