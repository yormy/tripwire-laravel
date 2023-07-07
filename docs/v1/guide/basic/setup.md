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

# Blocking Users or Blocking Early
There is a fundamental choice you must make in when to block requests
* Option 1: Block as soon as possible in the request cycle
* Option 2 (recommended): Block a userId if the request came from a recognized user.

## Option 1: Block as soon as possible
This will block a request as soon as possible, but that includes also before a userId is known.
Consequences, there will be no block on userId, only on IP or browserFingerprint
### Setup
```php
protected $middlewareGroups = [
  'web' => [
        TripwireBlockHandlerAll::class,
        'tripwire.main'
        // ... rest of the middleware
    ]

    'api' => [
        TripwireBlockHandlerAll::class,
        'tripwire.main'
        // ... rest of the middleware
    ]
```

or even just in the root middleware
```php
protected $middleware = [
    TripwireBlockHandlerAll::class,
    'tripwire.main'
    // ... rest of the middleware
```

## Option 2 (recommended) : Block a userId if present
Consequences: the block will happen a bit later in the request cycle, but if a user it blocked then if they use a different IP they are still blocked on a userId level
This is a more secure block
```php
protected $middlewareGroups = [
  'web' => [
        // ... middleware for authentications
        TripwireBlockHandlerAll::class,
        'tripwire.main'
        // ... rest of the middleware
    ]

    'api' => [
        // ... middleware for authentications
        TripwireBlockHandlerAll::class,
        'tripwire.main'
        // ... rest of the middleware
    ]
```
