<?php
declare(strict_types=1);

$data = (object)[
    'name' => 'user',
    'email' => 'user@example.com',
];

$validator = (new ObjectType())
    ->addProperty('str', (new StringType())->assertNotEmpty())
    ->addProperty('email', (new StringType())->assertEmail());

try {
    $validator->validate($data);
} catch (\Throwable $e) {
    //do smth
}