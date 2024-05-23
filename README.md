Event Register
===============

Use a modern way to centrally register events in your CraftCMS app

Requirements
------------
+ PHP 8.0.2 or later.

Installation
------------

Then tell Composer to load the library

```bash
composer require panlatent/craft-event-register
```

Usages
------

### Events Register

The Events Register provides a configuration with annotations to register event handlers in a unified way.

1 Add `events.php` to `config` directory. This configuration file supports 3 methods:

Function array:
```php
<?php
return [
  #[RegisterComponentTypes(Elements::class, Elements::EVENT_REGISTER_ELEMENT_TYPES)]
  function(): array {
      return [YourElement::class];
  },
];
```
    
Class object
```php
<?php
return new class {
  #[RegisterComponentTypes(Elements::class, Elements::EVENT_REGISTER_ELEMENT_TYPES)]
  public function registerElements(): array {
      return [YourElement::class];
  },
};  // or return new YourClass()
```
    
Class config by `Yii::createObject()` / `Craft::createObject()`
```php
<?php
return ['class' => YourClass::class]
```
2. Register event using annotations [class demo](demo/EventsConfig.php)

+ [#Bootstrap](src/Bootstrap.php)
+ [#DefineBehaviors](src/DefineBehaviors.php)
+ [#RegisterComponentTypes](src/RegisterComponentTypes.php)
+ [#RegisterEvent](src/RegisterEvent.php)

License
-------
The project is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
