# dentelis/validator

Validator is a PHP library for validation data structure received from external json API.

## Specific

The library was made to test json compatible data structures. Array key validation is not supported.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install Validator.

```bash
composer require dentelis/validator
```

## Usage

You can use library with(or without) any testing framework you want.

```php
//setup structure for validation

//object example
$user = (new ObjectType())
    ->addProperty('name', (new StringType())->assertNotEmpty())
    ->addProperty('email', (new StringType())->assertEmail());
    
$data = json_decode('{"name":"user", "email":"user@example.com"}');
try {
    $user->validate($data);
} catch (\Throwable $e) {
    //do smth
    
}

//array of objects
$users = (new ArrayType())
    ->assertNotEmpty()
    ->assertType($user);

$data = json_decode('[{"name":"user", "email":"user@example.com"},{"name":"user", "email":"user@example.com"}]');
try {
    $users->validate($data);
} catch (\Throwable $e) {
    //do smth

}


```

See examples directory for full example.

## Todo

- [ ] more examples
- [ ] proper path in exceptions
- [ ] do not fail on first exception
- [ ] comments translate to english
- [ ] create TypeInterface from Classname (what to do with arrays)

## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)
