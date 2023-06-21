# Setup

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

# Wiring - Setting up the wires
There are many different middleware wires that can be applied, or applied as a group.


## Global middleware
```
    ChecksumCalculate::class,   // Calculates the checksum of the request, needs to be the very first in your middleware set before modifying any request item
    HoneypotsWire::class,       // trips when honeypots are detected
    ...
    ChecksumValidateWire::class, // Trips when the calculated checksum does not match the frontend posted value (if provided)
```

## Web/Api middleware
The web/api middleware consists of 2 types.
Wires and Blockers.
* Wires will detect malicious requests
* Blockers will handle the actual blocking of users and ips

## Blockers
There is a blocker that block a user on their userType and userId (TripwireBlockHandlerUser) this only works after a user has logged in
There is a blocker on IP address (TripwireBlockHandlerIp)
There is a blocker on a browserFingerprint (if your frontend supplies that) (TripwireBlockHandlerBrowser)
and a blocker that is a combination of user, ip and browser (TripwireBlockHandlerAll). This will perform as TripwireBlockHandlerIp, TripwireBlockHandlerBrowser and TripwireBlockHandlerUser in one go


Adding blockers:
```
    protected $middlewareGroups = [
        'web' => [
            ...
            TripwireBlockHandlerAll::class,
            ...
        ],

        'api' => [
            ...
            TripwireBlockHandlerUser::class // must be added after a user has been authenticated and a userId is available
            ...
        ],
```

# Wires
There are many different wires that you can apply.
Either individually by class name, or as a group defined in your config
Order of middleware is when it trips, the first tripped wire will explore, the rest will not be checked
Adding wires:
```
    protected $middlewareGroups = [
        'web' => [
            ...
            'tripwire.all'  // Uses all tripwires defined in the group 'all' in the config
            Xss::class      // or just apply 1 wire
            ...
        ],

        'api' => [
            ...
            'tripwire.all' // Uses all tripwires defined in the group 'all' in the config
            ...
        ],
```



====


# Catch Model Binding????
When a hacker tries to change your routes and tries to access impproper missing models then you can catch this by

Add to your model that you want to protect
```
use TripwireModelBindingTrait
```
