# Basic setup
There are a few steps involved to setup Tripwire.
* Malicious requests need to be recognized and responded to
* Malicious users need to be blocked

<!--@include: ../../definitions.md-->

## Recognizing and blocking malicious requests
Recognizing malicious requests happen through the so called wires. When a wire is tripped, actions will be taken.
These actions can be defined (see configuration). But we first need to define which wires to use.
There are many different wires and wire groups but start with the basics

In your ```kernel.php``` make the following additions 

```php
protected $middleware = [ // [!code focus]
    HoneypotsWire::class,  // [!code focus] // will recognize and block on honeypot traps
    \App\Http\Middleware\TrustProxies::class,
    \Illuminate\Http\Middleware\HandleCors::class,
    SetDefaultLocale::class,
```

```php
protected $middlewareGroups = [ // [!code focus]
    'web' => [ // [!code focus]
        \App\Http\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        TripwireBlockHandlerAll::class, // [!code focus] // will block malicious ips/users
        'tripwire.main'  // [!code focus] // will recognize and action on malicious requests
        ...
```

If you also have an api section, you need to add it there too
```php
protected $middlewareGroups = [ // [!code focus]
    'api' => [ // [!code focus]
        \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        TripwireBlockHandlerAll::class, // [!code focus] // will block malicious ips/users
        'tripwire.main'  // [!code focus] // will recognize and action on malicious requests

    ],
```

:::warning Order is important
First include your blockhandler (ie: TripwireBlockHandlerAll::class) and then your tripwires (ie 'tripwire.main). This will ensure that no blocked user is getting trough to your tripwire handlers. This makes the response faster
:::
