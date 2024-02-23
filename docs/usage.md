# Usage

This following example shows basic usage of the container:

```php
// Sets a new service into container
$container->set('foo', $some_service);

// Gets a service
$container->get('foo') // instanceof $some_service

// Define a new service factory
$container->factory('bar', function (\Charon\Container\ContainerInterface $container) {
    return new \stdClass(
        $container->get('foo')
    )
});
```
