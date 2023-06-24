# ExceptionsInspector
In order to track global app exceptions like model or page not found you need to add the ExceptionInspector in your app exception handler class
```
// App/Exceptions/Hander.php

use Yormy\TripwireLaravel\Services\ExceptionInspector;

public function render($request, Throwable $exception)
{
    ...
    ExceptionInspector::inspect($exception);
    ...
```
