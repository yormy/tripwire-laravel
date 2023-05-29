# Removing blocks
Sometimes you need an ability to remove the block and logs. One of the use cases is that you are blocked yourself, 
or that you want security researchers to pentest your system (...they will constantly be blocked).
One way of doing that is to disable Tripwire, but a much better way is to give them a reset url.

With a reset-url someone can access the page to remove all blocks/logs with their ip.
They will be instantly unblocked and have access to the site again.

For this to work you need to
* Register the reset url
* Enable the reset
* Specify how many minutes the reset url will be available. After that time the reset-url will do nothing. 

In your app routes you need to add this line under the grouping and ```guest``` middlewares. These routes need to be publicly available without any tripwire middleware.
```php
Route::prefix('myprefix')
    ->name('my-name.')
    ->group(function () {
        Route::TripwireResetRoutes(); //[!code focus] //  path: myprefix/guest/reset ; name: 'my-name.tripwire.guest.logs.reset'
    });
```

and update your ```.env```
```php
TRIPWIRE_RESET_ENABLED=true
TRIPWIRE_RESET_LINK_EXPIRATION_MINUTES=60*24*30     // how many minutes will this reset-url be available
```

## Hard delete blocks
By default the logs and blocks will be soft-deleted from your database. If you want to hard delete them add the following to your .env
```php
TRIPWIRE_RESET_DELETE_SOFT=false;
```

## Persistent Blocks
The use case is that sometimes you want to keep certain ips to remain blocked no matter what. So that even if there is a cleanup of the logs, these persistent blocks remain.
As long as a block is persistent, it will not be removed by the reset-url.

::: danger Persistent Blocks
The database hold a field called: ```persistent_block``` when this flag is set, this block is not affected by the reset-url.
These flags need to be cleared before the blocks can be removed with a reset-url
:::

## Generate a reset url
In your routes file specify the prefix/middleware/group and register the admin tripwire routes

This way you can specify where the routes are in your path and namespaces and what middleware you want to apply

These routes should be only accessible by admins
```php
    Route::prefix('')
        ->middleware(['web','auth', 'admin'])
        ->group(function () {
            Route::TripwireAdminRoutes(); // [!code focus] // make sure only admins have access to this route
        });
```



