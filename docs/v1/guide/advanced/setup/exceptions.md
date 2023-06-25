# Exceptions Inspector
In order to track and act upon global app exceptions like model or page not found you need to add the ExceptionInspector in your app exception handler class

This is needed for wires like ModelNotFound and PageNotFound

Add the ExceptionInspector to your render function of your exception handler
```php
// App/Exceptions/Hander.php

use Yormy\TripwireLaravel\Services\ExceptionInspector;

public function render($request, Throwable $exception)
{
    ...
    ExceptionInspector::inspect($exception); // [!code focus]
    ...
```
