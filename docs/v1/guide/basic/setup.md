# Basic setup
There are a few steps involved to setup Tripwire.
* Malicious requests need to be recognized and responded to
* Malicious users need to be blocked


<!--@include: ../../definitions.md-->

## Recognizing and blocking malicious requests
Recognizing malicious requests happen through the so called wires. When a wire is tripped, actions will be taken.
These actions can be defined (see configuration). But we first need to define which wires to use.
There are many different wires, but start with the basics

In your ```kernal.php``` make the following additions 

```php
    protected $middleware = [
        HoneypotsWire::class,  // [!code focus] // will recognize and block on honeypot traps
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        SetDefaultLocale::class,
```

```php
    protected $middlewareGroups = [
        'web' => [ // [!code focus]
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            TripwireBlockHandlerAll::class, // [!code focus] // will block malicious ips/users
            'tripwire.all'  // [!code focus] // will recognize and action on malicious requests
            ...
```

If you also have an api section, you need to add it there too
```php
        'api' => [ // [!code focus]
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            TripwireBlockHandlerAll::class, // [!code focus] // will block malicious ips/users
            'tripwire.all'  // [!code focus] // will recognize and action on malicious requests

        ],
```