# Routes
Sometimes you might want to exclude one or more middelwares from a route.
You can add the ```withoutMiddleware([])``` to you route group to not apply that middleware checker 

## Removing a group of middlewares
```php
Route::prefix('')
    ->withoutMiddleware(['tripwire.main'])
    ->group(function () {
```

## Removing a single middleware
```php
Route::prefix('')
    ->withoutMiddleware(['tripwire.honeypot'])
    ->group(function () {
```
