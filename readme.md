# dentelis/php-json-structure-validator

Validator is a lightweight PHP library for validating the structure of data retrieved from an external json API.

The library was originally created for use in acceptance api tests, but can be used anywhere else as well.

See [dentelis/phpunit-json-assert](https://github.com/dentelis/phpunit-json-assert) for PhpUnit support.

## Specific

The library was made to test json compatible data structures. Array key validation is not supported.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install Validator.

```bash
composer require dentelis/php-json-structure-validator
```

## Usage

You can use library with(or without) any testing framework you want.

```php
//object example
$user = (new ObjectType())
    ->addProperty('name', (new StringType())->assertNotEmpty())
    ->addProperty('email', (new StringType())->assertEmail());
    
$data = json_decode('{"name":"user", "email":"user@example.com"}');
try {
    $user->validate($data);
} catch (ValidationException $e) {
    //do smth
    
}

//array of objects
$users = (new ArrayType())
    ->assertNotEmpty()
    ->assertType($user);

$data = json_decode('[{"name":"user", "email":"user@example.com"},{"name":"user", "email":"user@example.com"}]');
try {
    $users->validate($data);
} catch (ValidationException $e) {
    //do smth

}


```

See [examples](https://github.com/dentelis/validator/tree/master/examples) directory for full example.

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
