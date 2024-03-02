## Interface Binding

You can bind an interface to a specific implementation using the `bind` method:
```php
$container->bind('YourVendor\Interfaces\SomeInterface', 'YourVendor\Implementations\SomeImplementation');
```

Now, whenever you request YourVendor\Interfaces\SomeInterface from the container, it will resolve to YourVendor\Implementations\SomeImplementation.

## Shared Binding

To mark an implementation as shared (singleton), you can use the `shared` method:
```php
// Bind an interface to a concrete implementation as a singleton
$container->singleton('YourVendor\Interfaces\SomeInterface', 'YourVendor\Implementations\SomeImplementation');
```

This ensures that the same instance of the implementation is returned on subsequent requests.

## Autowiring

This container supports autowiring, allowing automatic resolution of dependencies without manual configuration.

```php
use YourVendor\Container\Container;

// Create a new container instance
$container = new Container();

// Register a class without explicit bindings
$dependency = $container->get('YourVendor\SomeClass');
$dependency->doSomething();
```

## Parameter Resolution

You can also resolve dependencies with parameters:

```php
// Create a new container instance
$container = new Container();

// Define a class with constructor parameters
class SomeClassWithParameters
{
    public function __construct($param1, $param2)
    {
        // ...
    }
}

// Resolve the class with specified parameters
$dependency = $container->make('SomeClassWithParameters',
    [
        'param1' => 'value1',
        'param2' => 'value2'
    ]
);
```
