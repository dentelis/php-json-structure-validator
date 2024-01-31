# dentelis/validator

Validator is a PHP library for validation data structure.

## Specific
The library was made to test json compatible data structures. Arrays with keys are not supported.

## Installation

Use the package manager [composer](https://getcomposer.org/) to install Validator.

```bash
composer require dentelis/validator
```

## Usage
You can use library with(or without) any testing framework you want.
```php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ObjectSimpleTest extends TestCase
{

   public function testSuccess(): void
   {
   
      $data = (object)['id' => 1, 'title' => 'lorem ipsum'];

      $struct = new _object([
          'id' => new _property_simple(_simpleType::INT, false),
          'title' => new _property_simple(_simpleType::STRING_NOT_EMPTY, false, regexp: '~^(.+\s.+)$~'),
      ]);
      
      try {
          $struct->validate($data);
      } catch (Throwable $e) {
          $this->assertNull($e);
      }
      
      $this->expectNotToPerformAssertions();
   }
}
```


## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)


# Ideas

# что-то (верхний уровень) - не уверен что нужно
   - тип = простой / объект / массив
   

## простой-тип (возможно разворачиваем в пачку разных объектов string/bool/etc)
   - реальный_тип (int/float/string/... )
   - расширенные условия 
     - все: возможные значения (перечисления)
     - все: возможные значения не содержат (перечисления)
     - строка: удовлетворяет регулярке
     - строка: не пустая
     - строка: email / url / etc
   - nullable?

## объект
  - набор свойств
  - nullable?
  - расширенные условия 
    - могут ли приходить новый свойства (def false)
    - функция валидатор

### свойство объекта
  - название
  - тип (простой тип / объект / массив)
  - расширенные условия
     - может ли отсутствовать (def false)


# массив
  - тип (простой тип / объект / массив)
  - расширенные условия? (ограничение на количество элементов сверху/снизу)
  - nullable?

? идея - верхнеуровнево переопределять дефолтные значения для условий
   - можно ли расширять объекты?
   - могут ли отсутствовать свойства?

? идея - везде где передаем тип - позволяем передавать туда функцию, которая вернет данные от даты

? идея - делаем у каждого объекта функции которые добавляют требования. удалять требования нельзя
nullable вероятно перемещаем в расширенные тоже

? идея - не делать отдельно простой тип, считать все эти string/int/bool и тд - 
аналогичными вещами как объект/массив
тогда сможем сделать расширения например в string типа isUrl, matchRegexp, notEmpty, и тд

? путаница на каком уровне храним nullable

? нужен ли верхний уровень (что-то)?

? обдумать - что происходит в случае ошибки валидации - exception в котором есть какой-то путь?

? обдумать - не останавливаться на первом расхождении

! перевод phpdoc на английский