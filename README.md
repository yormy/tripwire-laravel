# Tripwire Laravel

[![Latest Version on Packagist](https://img.shields.io/packagist/v/yormy/tripwire-laravel.svg?style=flat-square)](https://packagist.org/packages/yormy/tripwire-laravel)
[![Total Downloads](https://img.shields.io/packagist/dt/yormy/tripwire-laravel.svg?style=flat-square)](https://packagist.org/packages/yormy/tripwire-laravel)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/facade/ignition/run-php-tests?label=Tests)
![Alt text](./coverage.svg)

# Tripwire
```a wire stretched close to the ground, working a trap, explosion, or alarm when disturbed and serving to detect or prevent people or animals entering an area.```

```a comparatively weak military force employed as a first line of defence, engagement with which will trigger the intervention of stronger forces.```


# Installation
Tripwire-Laravel is an easy yet comprehensive and extendable way to add a security layer around laravel. All in order to prevent hackers getting into your system and frustrates the heck out of them while trying
```
composer require yormy/tripwire-laravel

php artisan migrate
```

# ExceptionsInspector
Add to your App/Exceptions/Hander
To allow taking action based on some system exceptions
```
public function render($request, Throwable $exception)
{
...
ExceptionInspector::inspect($exception);
...
```

# Middleware Setup
Order of middleware is when it trips, the first tripped wire will explore, the rest will not be checked

### Start of request cycle
```
    ChecksumCalculate::class, // need to be the very first in your middleware set before modifying any request item
    HoneypotsCheck::class,
    ...
    ChecksumValidate::class, // validate that the frontend checksum matches the posted values
```

### General middleware
These 2 will block traffic based on their IP address or on the browser fingerprint generated by the frontend
Note for this to work you do not need to know the user. So it can be placed earlier in the request cycle
```
    TripwireBlockHandlerIp::class,
    TripwireBlockHandlerBrowser::class
```

### Api middleware
the middleware that all api traffic is flowing through
Note that these needs to be added after a user has been validated
add
```
TripwireBlockHandlerUser::class
```
This will block api traffic for blocked users  

### Web middleware
Note that these needs to be added after a user has been validated
add
```
TripwireBlockHandlerUser::class
```
This will block web traffic for blocked users







## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Yormy](https://gitlab.com/yormy)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
